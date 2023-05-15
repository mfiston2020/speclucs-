<?php

namespace App\Http\Controllers\Manager;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        $payment_methods   =   \App\Models\PaymentMethod::all();
        return view('manager.paymentMethod.index',compact('payment_methods'));
    }

    public function create()
    {
        return view('manager.paymentMethod.create');
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'description'=>'required',
        ]);

        $PaymentMethod   =   new \App\Models\PaymentMethod();

        $PaymentMethod->name =   $request->name;
        $PaymentMethod->company_id =   Auth::user()->company_id;
        $PaymentMethod->description =   $request->description;

        try {
            $PaymentMethod->save();
            return redirect()->route('manager.paymentMethods')->with('successMsg','Payment Method Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
