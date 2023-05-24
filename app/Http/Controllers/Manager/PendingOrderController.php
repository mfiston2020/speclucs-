<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PendingOrder;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PendingOrderController extends Controller
{
    protected $productRepo;

    public function __construct() {
        $this->productRepo  =   new ProductRepo();
    }

    function index(){
        $pendingOrders  =   PendingOrder::where('company_id',userInfo()->company_id)->orderBy('created_at','desc')->get();
        return view('manager.sales.pending-sales',compact('pendingOrders'));
    }

    function setOrderPrice(Request $request){
        if ($request->cost <= 0 || $request->price <= 0) {

            return redirect()->back()->with('warningMsg','The '.($request->cost==0?'Cost':'Price').' can\'t be zero!')->withInput();
        }

        try {
            PendingOrder::where('id',Crypt::decrypt($request->thisName))->update([
                'order_cost'=>$request->cost,
                'order_price'=>$request->price,
                'status'=>'approved',
            ]);
            return redirect()->back()->with('successMsg','Pricing Updated Successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Oops! Something went wrong!');
        }
    }

    function clientRequestfeedback(Request $request){
        $productDetail  =   array();
        try {
            $product    =   PendingOrder::findOrFail(Crypt::decrypt($request->thisName));
            $productDetail=([
                'cost'=>$product->order_cost,
                'price'=>$product->order_price,
            ]);

            $productInfo    =   $this->productRepo->saveProduct($product->toArray(),'1',$productDetail);

            $placeOrderMessage  =   $this->productRepo->makeLabOrder($product->toArray(),$productInfo->id);

            PendingOrder::where('id',Crypt::decrypt($request->thisName))->update([
                'status'=>'paid',
            ]);

            return redirect()->back()->with('successMsg',$placeOrderMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Oops! Something went wrong!'.$th);
        }
    }

    function sellPendingOrder(Request $request){
        $product  =   Order::where('pending_order_id',Crypt::decrypt($request->thisName))->select('id','order_cost','product_id')->first()->toArray();

        $invoice_id =   $this->productRepo->sellPendingOrder($product);

        PendingOrder::where('id',Crypt::decrypt($request->thisName))->update([
            'status'=>'sold',
        ]);

        return redirect()->route('manager.sales.edit',Crypt::encrypt($invoice_id))->with('successMsg','The product has been disposed of! ');
    }

    function cancelOrder(Request $request){

        $id = Crypt::decrypt($request->thisName);

        try {
            $order  =   PendingOrder::find($id);
            $order->delete();

            return redirect()->back()->with('successMsg','Order Cancel!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('errorMsg','Oops! Something Went Wrong!'.$th);
        }
    }
}
