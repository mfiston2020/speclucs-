<?php

namespace App\Repositories;

use App\Interface\ProductInterface;
use App\Models\CompanyInformation;
use App\Models\LensType;
use App\Models\Order;
use App\Models\Power;
use App\Models\Product;
use App\Models\SoldProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ProductRepo implements ProductInterface{

    public String $message  =   '';
    public bool $showProductDetails  =   false;
    public $product =   [];

    function searchProduct(array $productDescription)
    {}

    // save products
    function saveProduct(array $request,string $category,array $pending)
    {
        if ($category=='1' && $category!=null)
        {
            // ============================================
            $product    =   new \App\Models\Product();

            $lens_type  =   \App\Models\LensType::find($request['type_id']);
            $index      =   \App\Models\PhotoIndex::find($request['index_id']);
            $chromatic  =   \App\Models\PhotoChromatics::find($request['chromatic_id']);
            $coating    =   \App\Models\PhotoCoating::find($request['coating_id']);


            $axis       =   $request['axis_r'] || $request['axis_l'];
            $sphere     =   $request['sphere_r'] || $request['sphere_l'];
            $cylinder   =   $request['cylinder_r']|| $request['cylinder_l'];
            $add        =   $request['addition_r'] || $request['addition_l'];
            $eye        =   $request['axis_r']?'right':'left';

            $description    =   initials($lens_type['name'])." ".$index['name']." ".$chromatic['name']." ".$coating['name'];

            // dd($pending);

            // checking the existance of the product
            $product_exists =   Power::where('type_id',$lens_type->id)
                                        ->where('index_id',$index->id)
                                        ->where('chromatics_id',$chromatic->id)
                                        ->where('coating_id',$coating->id)
                                        ->where('sphere',format_values($sphere))
                                        ->where('cylinder',format_values($cylinder))
                                        ->where('axis',format_values($axis))
                                        ->where('add',format_values($add))
                                        ->where('eye',initials($lens_type->name)=='SV'?'any':$eye)->first();


            $prdt   =   $product_exists==null?null:Product::where('id',$product_exists->id)->first();

            if ($product_exists && $prdt->cost==$request['lens_cost'])
            {
                return redirect()->route('manager.product')->with('warningMsg','Product already exists');
            }
            else{
                if (initials($lens_type->name)=='SV')
                {
                    $product->category_id       =   $category;
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description;
                    $product->stock             =   count($pending)>0?'1':$request['lens_stock'];
                    $product->deffective_stock  =   '0';
                    $product->price             =   count($pending)>0?$pending['price']:$request['lens_price'];
                    $product->cost              =   count($pending)>0?$pending['cost']:$request['lens_cost'];
                    $product->fitting_cost      =   count($pending)>0?'0':$request['fitting_cost'];
                    $product->company_id        =   userInfo()->company_id;
                    try {
                        $product->save();

                        $power                    =   new \App\Models\Power();
                        $power->product_id        =   $product->id;
                        $power->type_id           =   $lens_type->id;
                        $power->index_id          =   $index->id;
                        $power->chromatics_id     =   $chromatic->id;
                        $power->coating_id        =   $coating->id;
                        $power->sphere            =   format_values($sphere);
                        $power->cylinder          =   format_values($cylinder);
                        $power->axis              =   format_values($axis);
                        $power->add               =   format_values($add);
                        $power->eye               =   'any';
                        $power->company_id        =   userInfo()->company_id;
                        $power->save();
                    } catch (\Throwable $th) {
                        return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
                    }
                }
                else
                {

                    $product->category_id       =   $category;
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description.' - '.$eye;;
                    $product->stock             =   count($pending)>0?'1':$request['lens_stock'];
                    $product->deffective_stock  =   '0';
                    $product->price             =   count($pending)>0?$pending['price']:$request['lens_price'];
                    $product->cost              =   count($pending)>0?$pending['cost']:$request['lens_cost'];
                    $product->fitting_cost      =   count($pending)>0?'0':$request['fitting_cost'];
                    $product->company_id        =   userInfo()->company_id;

                    $product->save();

                    $power                    =   new \App\Models\Power();
                    $power->product_id        =   $product->id;
                    $power->type_id           =   $lens_type->id;
                    $power->index_id          =   $index->id;
                    $power->chromatics_id     =   $chromatic->id;
                    $power->coating_id        =   $coating->id;
                    $power->sphere            =   format_values($sphere);
                    $power->cylinder          =   format_values($cylinder);
                    $power->axis              =   format_values($axis);
                    $power->add               =   format_values($add);
                    $power->eye               =   $eye;
                    $power->company_id        =   userInfo()->company_id;
                    $power->save();
                }
            }
            return $product;
        }
    }

    // make lab order function
    function makeLabOrder(array $request, string $product_id)
    {
        $lens_type  =   \App\Models\LensType::find($request['type_id']);
        $index      =   \App\Models\PhotoIndex::find($request['index_id']);
        $chromatic  =   \App\Models\PhotoChromatics::find($request['chromatic_id']);
        $coating    =   \App\Models\PhotoCoating::find($request['coating_id']);


        $order  =   new \App\Models\Order();

        $order->company_id      =   userInfo()->company_id;
        // $order->supplier_id     =   $request->supplier;
        $order->pending_order_id=   $request['id'];
        $order->supplier_id     =   userInfo()->company_id;
        $order->product_id      =   $product_id;
        $order->type_id         =   $lens_type->id;
        $order->coating_id      =   $coating->id;
        $order->index_id        =   $index->id;
        $order->chromatic_id    =   $chromatic->id;
        // $order->order_number    =   $request['order_number'];
        $order->order_number    =   Order::where('company_id',userInfo()->company_id)->count() + 1;
        $order->patient_number  =   $request['patient_number'];
        $order->firstname       =   $request['firstname'];
        $order->lastname        =   $request['lastname'];
        $order->invoice_date    =   date('Y-m-d');
        $order->prescription    =   CompanyInformation::where('id',userInfo()->company_id) ->pluck('company_name')->first();
        // $order->prescription    =   $request['prescription_hospital'] || CompanyInformation::where('id',userInfo()->company_id) ->pluck('company_name')->first();
        $order->order_cost      =   $request['order_cost'];
        // $order->frame_id        =   $request['frame'];
        $order->status          =   'submitted';
        $order->quantity        =   '1';

        $order->sphere_r    =   $request['sphere_r'];
        $order->cylinder_r  =   $request['cylinder_r'];
        $order->axis_r      =   $request['axis_r'];
        $order->addition_r  =   $request['addition_r'];

        $order->sphere_l    =   $request['sphere_l'];
        $order->cylinder_l  =   $request['cylinder_l'];
        $order->axis_l      =   $request['axis_l'];
        $order->addition_l  =   $request['addition_l'];

        try {
            $power          =   \App\Models\Power::where(['product_id' => $product_id])->select('*')->first();
            // $company        =   \App\Models\CompanyInformation::find($company_id);
            // $this_company   =   \App\Models\CompanyInformation::find(userInfo()->company_id);

            $product           =   \App\Models\Product::find($product_id);

            if (initials($product->product_name) == 'SV') {
                $lenPower   =   $product->description . " " . $power->sphere . " / " . $power->cylinder;
            } else {
                $lenPower   =   $product->description . " " . $power->sphere . " / " . $power->cylinder . " * " . $power->axis . " " . $power->add;
            }

            // Notification::route('mail', $company->company_email)->notify(new OrderPlaced($this_company->company_name,$lenPower));
            $order->save();

            // $notification   =   new \App\Models\SupplierNotify();
            // $notification->company_id   =   userInfo()->company_id;
            // // $notification->supplier_id  =   $company_id;
            // $notification->order_id     =   $order->id;
            // $notification->notification  =   'New Order Received';
            // $notification->save();

            return 'Payment Processed and Order sent to Lab!';
        } catch (\Throwable $th) {
            return 'Something Went Wrong!' . $th;
        }
    }

    // selling orders & creating the invoice for the client
    function sellPendingOrder(array $product)
    {

        $reference  =   count(DB::table('invoices')->select('reference_number')->where('company_id',userInfo()->company_id)->get());
        $pending    =   count(DB::table('invoices')->select('*')->where('status','=','pending')->where('company_id',userInfo()->company_id)->where('user_id','=',userInfo()->id)->get());

        $invoice    =   new \App\Models\Invoice();

        $invoice->reference_number  =   $reference+1;
        $invoice->status            =   'pending';
        $invoice->user_id           =   userInfo()->id;
        $invoice->total_amount      =   '0';
        $invoice->company_id        =   userInfo()->company_id;

        $invoice->save();

        $sold   =   new \App\Models\SoldProduct();

        $sold->invoice_id   =   $invoice->id;
        $sold->product_id   =   $product['id'];
        $sold->quantity     =   '1';
        $sold->unit_price   =   $product['order_cost'];
        $sold->discount     =   '0';
        $sold->total_amount =   $product['order_cost'];
        $sold->company_id   =   Auth::user()->company_id;

        try {
            $sold->save();
            return $invoice->id;
        } catch (\Throwable $th) {
            return 'Sorry Something Went Wrong! ';
        }
    }
}
