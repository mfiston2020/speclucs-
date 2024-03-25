<?php

namespace App\Http\Controllers\Manager;

use App\Notifications\QuotationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $categories =   \App\Models\Category::all();
        return view('manager.purchaseOrder.index', compact('categories'));
    }

    public function proceed(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        // $suppliers  =   \App\Models\Supplier::where('company_id',Auth::user()->company_id)->get();
        $suppliers  =   \App\Models\User::where('role', 'manager')->where('supplier_state', '1')
            ->where('status', 'active')->where('id', '<>', Auth::user()->id)->get();
        $products   =   \App\Models\Product::where('category_id', $request->category)->where('company_id', Auth::user()->company_id)->get();
        $category   =   $request->category;
        $from   =   $request->from;
        $to     =   $request->to;

        $products_array =   array();
        $pro_array =   array();

        foreach ($products as $product) {
            // array_push($pro_array,$product->id);
            $sold   =   \App\Models\SoldProduct::where('product_id', $product->id)->where('company_id', userInfo()->company_id)
                        ->whereBetween('created_at',[date('Y-m-d', strtotime($request->from)),date('Y-m-d', strtotime($request->to))])
                // ->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from . '-1day')))
                // ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to . '+1day')))
                ->get();

            foreach ($sold as $key => $value) {
                array_push($pro_array, $value->product_id);
            }
        }
        $products_array =   array_unique($pro_array);

        // dd($products_array);

        return view('manager.purchaseOrder.results', compact('products_array', 'suppliers', 'category', 'from', 'to'));
    }

    public function quotation(Request $request)
    {
        $quotation_number_   =   0;

        $quotation_number   =   \App\Models\PurchaseOrder::latest('quotation_number')->where('company_id', Auth::user()->company_id)->first();

        foreach ($request->product_id as $key => $product) {
            if ($quotation_number != null) {
                $quotation_number_   =   $quotation_number_   +   1;
            } else {
                $quotation_number_   =   $quotation_number_   +   1;
            }
            $purchase_order     =   new \App\Models\PurchaseOrder();

            $purchase_order->quotation_number   =   $quotation_number_;
            $purchase_order->product_id         =   $product;
            $purchase_order->company_id         =   Auth::user()->company_id;
            $purchase_order->supplier_id        =   $request->supplier;
            $purchase_order->current_stock      =   $request->stock[$key];
            $purchase_order->sales              =   $request->modal_stock[$key];
            $purchase_order->addition           =   $request->addition[$key];
            $purchase_order->model_stock        =   $request->modal_stock[$key];
            $purchase_order->forecast           =   $request->forecast[$key];
            $purchase_order->from_date          =   date('Y-m-d', strtotime($request->from));
            $purchase_order->to_date            =   date('Y-m-d', strtotime($request->to));

            try {
                $purchase_order->save();
            } catch (\Throwable $th) {
                return redirect()->back()->with('errorMsg', 'Something Went Wrong!');
            }
        }

        $quotation  =   new \App\Models\Quotation();
        $quotation->company_id          =   Auth::user()->company_id;
        $quotation->quotation_number    =   $quotation_number_;
        $quotation->supplier_id         =   $request->supplier;
        $quotation->status              =   'request';

        try {

            $comp               =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
            // $supplier        =   \App\Models\Supplier::find($request->supplier);
            $supplier_company   =   \App\Models\User::find($request->supplier);
            $supplier           =   \App\Models\CompanyInformation::find($supplier_company->company_id);

            Notification::route('mail', $supplier->company_email)->notify(new QuotationRequest($comp->company_name));

            $quotation->save();

            // $notification   =   new \App\Models\SupplierNotify();
            // $notification->company_id   =   Auth::user()->company_id;
            // $notification->supplier_id  =   $request->supplier;
            // // $notification->order_id     =   $order->id;
            // $notification->notification  =   'New Quotation Request Received';
            // $notification->save();

            return redirect()->route('manager.quations')->with('successMsg', ' Your Purchase order successfully sent to Supplier');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Something Went Wrong!');
        }
    }
}
