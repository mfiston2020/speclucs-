<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PendingOrder;
use App\Models\Product;
use App\Models\SoldProduct;
use App\Models\UnavailableProduct;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PendingOrderController extends Controller
{
    protected $productRepo;

    public function __construct()
    {
        $this->productRepo  =   new ProductRepo();
    }

    function orderStatus(){
        $lens_type          =   \App\Models\LensType::all();
        $index              =   \App\Models\PhotoIndex::all();
        $coatings           =   \App\Models\PhotoCoating::all();
        $chromatics         =   \App\Models\PhotoChromatics::all();
        $other_orders   =   Invoice::where('company_id',userInfo()->company_id)->orderBy('created_at','desc')->paginate(100);
        return view('manager.sales.order-status',compact('other_orders','lens_type','index','coatings','chromatics','other_orders'));
    }

    function index()
    {
        // $pendingOrders  =   PendingOrder::where('company_id', userInfo()->company_id)->orderBy('created_at', 'desc')->get();

        $sales_requested  =   Invoice::where('company_id', Auth::user()->company_id)->where('status', 'requested')->orderBy('created_at', 'DESC')->get();

        $sales_priced  =   Invoice::where('company_id', Auth::user()->company_id)->where('status', 'priced')->orderBy('created_at', 'DESC')->get();

        $sales_delivered  =   Invoice::where('company_id', Auth::user()->company_id)->where('status', 'delivered')->orderBy('created_at', 'DESC')->get();

        $sales_sent_to_lab  =   Invoice::where('company_id', Auth::user()->company_id)->where('status', 'sent to lab')->orderBy('created_at', 'DESC')->get();

        $other_orders  =   Invoice::where('company_id', userInfo()->company_id)->whereNotIn('status', ['delivered', 'sent to lab', 'in production', 'completed'])->orderBy('created_at', 'desc')->with('unavailableproducts')->with('client')->with('soldproduct')->get();

        return view('manager.sales.pending', compact('sales_requested', 'sales_priced', 'sales_delivered', 'sales_sent_to_lab','other_orders'));
    }

    function setOrderPrice(Request $request)
    {
        if ($request->cost <= 0 || $request->price <= 0) {

            return redirect()->back()->with('warningMsg', 'The ' . ($request->cost == 0 ? 'Cost' : 'Price') . ' can\'t be zero!')->withInput();
        }

        try {
            PendingOrder::where('id', Crypt::decrypt($request->thisName))->update([
                'order_cost' => $request->cost,
                'order_price' => $request->price,
                'status' => 'approved',
            ]);
            return redirect()->back()->with('successMsg', 'Pricing Updated Successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Oops! Something went wrong!');
        }
    }

    function clientRequestfeedback(Request $request)
    {
        $productDetail  =   array();
        try {
            $product    =   PendingOrder::findOrFail(Crypt::decrypt($request->thisName));
            $productDetail = ([
                'cost' => $product->order_cost,
                'price' => $product->order_price,
            ]);

            $productInfo    =   $this->productRepo->saveProduct($product->toArray(), '1', $productDetail);

            $placeOrderMessage  =   $this->productRepo->makeLabOrder($product->toArray(), $productInfo->id);

            PendingOrder::where('id', Crypt::decrypt($request->thisName))->update([
                'status' => 'paid',
            ]);

            return redirect()->back()->with('successMsg', $placeOrderMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Oops! Something went wrong!' . $th);
        }
    }

    function sellPendingOrder(Request $request)
    {
        $product  =   Order::where('pending_order_id', Crypt::decrypt($request->thisName))->select('id', 'order_cost', 'product_id', 'firstname', 'lastname', 'patient_number')->first()->toArray();

        $invoice_id =   $this->productRepo->sellPendingOrder($product);

        PendingOrder::where('id', Crypt::decrypt($request->thisName))->update([
            'status' => 'sold',
        ]);

        return redirect()->route('manager.sales.edit', Crypt::encrypt($invoice_id))->with('successMsg', 'The product has been disposed of! ');
    }

    function cancelOrder(Request $request)
    {

        $id = Crypt::decrypt($request->thisName);

        try {
            $order  =   PendingOrder::find($id);
            $order->delete();

            return redirect()->back()->with('successMsg', 'Order Canceled!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('errorMsg', 'Oops! Something Went Wrong!');
        }
    }

    function adjustPrice(Request $request)
    {

        $order  =   UnavailableProduct::findOrFail(Crypt::decrypt($request->thisName));
        $order->price   =   $request->price;
        $order->cost    =   $request->cost;
        $order->status  =   'approved';
        try {
            $order->save();
            return redirect()->back()->with('successMsg', 'Price set!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('errorMsg', 'Something went Wrong!');
        }
    }

    function sellProduct(Request $request)
    {

        $order  =   UnavailableProduct::findOrFail(Crypt::decrypt($request->thisName));
        try {

            $newProduct = $this->productRepo->saveProduct($order->toArray(), '1', $order->toArray(), true);

            $sold               =   new SoldProduct();
            $sold->invoice_id   =   $order->invoice_id;
            $sold->product_id   =   $newProduct->id;
            $sold->quantity     =   $order->quantity;
            $sold->unit_price   =   $order->price;
            $sold->discount     =   '0';
            $sold->total_amount =   $order->price;
            $sold->company_id   =   Auth::user()->company_id;
            $sold->save();

            $order->status      =   'sold';
            $order->product_id  =   $newProduct->id;
            $order->save();

            return redirect()->back()->with('successMsg', 'Product Sold!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('errorMsg', 'Something went Wrong!' . $th);
        }
    }

    function sellProductOff($id)
    {
        $naProducts =   UnavailableProduct::where('invoice_id', Crypt::decrypt($id))->get();

        foreach ($naProducts as $key => $value) {

            $product_stock          =   Product::find($value->product_id);
            $product_stock->stock   =   $product_stock->stock - $value->quantity;
            try {
                $product_stock->save();

                $value->status =   'completed';
                $value->save();
            } catch (\Throwable $th) {
                return redirect()->back()->with('errorMsg', 'Something Went Wrong!');
            }
        }

        return redirect()->back()->with('successMsg', 'Product Sold Off!');
    }
}
