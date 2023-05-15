<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        $payment_methods   =   \App\Models\PaymentMethod::all();
        return view('admin.paymentMethod.index',compact('payment_methods'));
    }

    public function create()
    {
        return view('admin.paymentMethod.create');
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
            return redirect()->route('admin.paymentMethods')->with('successMsg','Payment Method Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function edit($id)
    {
        $payment_methods    =   \App\Models\PaymentMethod::find(Crypt::decrypt($id));
        return view('admin.paymentMethod.edit',compact('payment_methods'));
    }
    public function update(Request $request, $id)
    {
        $PaymentMethod  =   \App\Models\PaymentMethod::find(Crypt::decrypt($id));

        $PaymentMethod->name            =   $request->name;
        $PaymentMethod->description     =   $request->description;

        try {
            $PaymentMethod->save();
            return redirect(route('admin.paymentMethods'))->with('successMsg','Payment Method successfully Updated!');
        } catch (\Throwable $th)
        {
            return redirect()->back()->with('errorMsg','Soemthing Went Wrong!');
        }

    }

    public function delete($id)
    {
        $PaymentMethod  =   \App\Models\PaymentMethod::find(Crypt::decrypt($id));
        try {
            $PaymentMethod->delete();
            return redirect()->back()->with('successMsg','Payment Method successfully Deleted!');
        } catch (\Throwable $th)
        {
            return redirect()->back()->with('errorMsg','Soemthing Went Wrong!');
        }
    }
}
