<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
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
        try {
            PendingOrder::where('id',Crypt::decrypt($request->thisName))->update([
                'status'=>'paid',
            ]);
            $product    =   PendingOrder::findOrFail(Crypt::decrypt($request->thisName));

            $this->productRepo->saveProduct($product->toArray());

            return redirect()->back()->with('successMsg','Pricing Updated Successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Oops! Something went wrong!'.$th);
        }
    }
}
