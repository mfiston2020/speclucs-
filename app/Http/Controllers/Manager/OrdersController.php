<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Notifications\OrderPlaced;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;

class OrdersController extends Controller
{
    public function index()
    {
        $orders =   \App\Models\Order::where('company_id', Auth::user()->company_id)->orderBy('created_at', 'desc')->get();

        return view('manager.myorder.index', compact('orders'));
    }

    public function add()
    {
        // $invoice_number =   count(\App\Models\Order::where('company_id',Auth::user()->company_id)->get());
        $suppliers  =   \App\Models\User::where('role', 'manager')->where('supplier_state', '1')->select('*')->get();
        $order_count =   (int)count(\App\Models\Order::where('company_id', Auth::user()->company_id)->get()) + 1;

        $order_number   =   sprintf('%04d', $order_count);

        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index      =   \App\Models\PhotoIndex::all();
        $frames     =   Product::where('category_id', 2)->get();

        return view('manager.myorder.create', compact('suppliers', 'lens_types', 'chromatics', 'coatings', 'index', 'order_number', 'frames'));
    }

    public function placeOrder(Request $request)
    {
        $company_id =   Auth()->user()->company_id;
        $product_from_stock =   $this->fetchSupplierProduct($request);

        // return $product_from_stock;

        // if ($product_from_stock) {
        //     return 'heree';
        // }

        // if (!$request->pid) {
        //     return redirect()->back()->withInput()->with('warningMsg', 'this product was not found to this suplier!');
        // }
        
        $this->validate($request, [
            'order_number' => 'unique:orders',
            'patient_number' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $sphere     =    0;
        $cylinder   =    0;
        $lenPower   =    '';

        // if ($request->type=='2')
        // {
        //     $sphere =   $request->sphere_both;
        //     $cylinder =   $request->cylinder_both;
        // }
        // else
        // {

        $sphere     =   $request->sphere_right;
        $cylinder   =   $request->cylinder_right;
        // }
        $order  =   new \App\Models\Order();

        $order->company_id      =   Auth::user()->company_id;
        // $order->supplier_id     =   $request->supplier;
        $order->supplier_id     =   Auth::user()->company_id;
        $order->type_id         =   $request->type;
        $order->coating_id      =   $request->coating;
        $order->product_id      =   $request->pid;
        $order->index_id        =   $request->coating;
        $order->chromatic_id    =   $request->chromatic;
        $order->order_number    =   $request->order_number;
        $order->patient_number  =   $request->patient_number;
        $order->firstname       =   $request->firstname;
        $order->lastname        =   $request->lastname;
        $order->invoice_date    =   date('Y-m-d');
        $order->prescription    =   $request->prescription_hospital;
        $order->order_cost      =   $request->cost;
        $order->frame_id        =   $request->frame;
        $order->status          =   'submitted';
        $order->quantity        =   '1';

        $order->sphere_r    =   $request->sphere_right;
        $order->cylinder_r  =   $request->cylinder_right;
        $order->axis_r      =   $request->axis_right;
        $order->addition_r  =   $request->addition_right;

        $order->sphere_l    =   $request->sphere_left;
        $order->cylinder_l  =   $request->cylinder_left;
        $order->axis_l      =   $request->axis_left;
        $order->addition_l  =   $request->addition_left;
        $order->comment     =   $request->comment;

        try {
            $power          =   \App\Models\Power::where(['product_id' => $request->pid])->select('*')->first();
            $company        =   \App\Models\CompanyInformation::find($company_id);
            $this_company   =   \App\Models\CompanyInformation::find(Auth::user()->company_id);

            $product           =   \App\Models\Product::find($request->pid);

            if (initials($product->product_name) == 'SV') {
                $lenPower   =   $product->description . " " . $power->sphere . " / " . $power->cylinder;
            } else {
                $lenPower   =   $product->description . " " . $power->sphere . " / " . $power->cylinder . " * " . $power->axis . " " . $power->add;
            }

            // Notification::route('mail', $company->company_email)->notify(new OrderPlaced($this_company->company_name,$lenPower));
            $order->save();

            $notification   =   new \App\Models\SupplierNotify();
            $notification->company_id   =   Auth::user()->company_id;
            $notification->supplier_id  =   $company_id;
            $notification->order_id     =   $order->id;
            $notification->notification  =   'New Order Received';
            $notification->save();

            return redirect()->route('manager.order')->with('successMsg', ' Order Successfully Placed! the supplier will notify you at any change!');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg', 'Something Went Wrong!' . $th);
        }
    }

    public function cancelOrder($id)
    {
        $order  =   \App\Models\Order::find(Crypt::decrypt($id));
        $order->status  =   'canceled';
        try {
            $order->save();
            $notification   =   \App\Models\SupplierNotify::where('order_id', Crypt::decrypt($id));
            $notification->delete();
            return redirect()->back()->with('successMsg', 'Order Successfully Canceled!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Something Went Wrong!');
        }
    }

    public function fetchSupplierProduct(Request $request)
    {
        $product    =   null;
        $company_id =   Auth()->user()->company_id;
        $type           =   \App\Models\LensType::find($request->type);

        if (initials($type->name) == 'SV') {
            $product_id     =   \App\Models\Power::where('type_id', $type->id)
                ->where('index_id', $request->index)
                ->where('chromatics_id', $request->chromatic)
                ->where('coating_id', $request->coating)
                ->where('sphere', format_values($request->sphere_right))
                ->where('cylinder', format_values($request->cylinder_right))
                ->where('eye', 'any')
                ->where('company_id', $company_id)
                ->pluck('product_id')->first();

                $product    =   \App\Models\Product::find($product_id);

                return response()->json($product);
        } else {
            $product_id     =   \App\Models\Power::where('type_id', $type->id)
                ->where('index_id', $request->index)
                ->where('chromatics_id', $request->chromatics)
                ->where('coating_id', $request->coating)
                ->where('sphere', format_values($request->sphere))
                ->where('cylinder', format_values($request->cylinder))
                ->where('axis', format_values($request->axis))
                ->where('add', format_values($request->add))
                ->where('eye', $request->eye)
                ->where('company_id', Auth::user()->company_id)
                ->select('product_id')->first();

                $product    =   \App\Models\Product::find($product_id);

                return response()->json($product);
            // $product    =   $request->all();
        }
        // return response()->json($product);
    }

    public function receivedOrder($id)
    {
        $order  =   \App\Models\Order::find(Crypt::decrypt($id));

        $order->status  =   'received';
        try {
            $order->save();

            if ($order->supplier_id == Auth::user()->company_id) {
                $patientNumber  =   $order->patient_number;
                // $patientNumber  =   \App\Models\Patient::where('patient_number',$order->patient_number)->select('*')->first();
                $company_name   =   \App\Models\CompanyInformation::find(Auth::user()->company_id);
                // return $patientNumber;

                if ($company_name->sms_quantity > 0) {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.mista.io/sms',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS
                        => array('to' => $patientNumber, 'from' => 'SPECLUCS', 'unicode' => '0', 'sms'
                        => 'Dear ' . $order->firstname . ', ' . $company_name->company_name . ' would like to inform you that your glasses are ready, You can passby anytime and collect them Thank you!', 'action'
                        => 'send-sms'),
                        CURLOPT_HTTPHEADER => array(
                            'x-api-key: ecb697cc-99f3-913e-a618-aae6038c4613-5f82c0d9'
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    $company_name->sms_quantity =   $company_name->sms_quantity - 1;
                    $company_name->save();
                }
            }
            return redirect()->back()->with('successMsg', 'Thank you for using Speclucs! ');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Something Went Wrong!' . $th);
        }
    }
}
