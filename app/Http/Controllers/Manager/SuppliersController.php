<?php

namespace App\Http\Controllers\Manager;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers   =   \App\Models\Supplier::where('company_id',Auth::user()->company_id)->get();
        return view('manager.supplier.index',compact('suppliers'));
    }

    public function create()
    {
        return view('manager.supplier.create');
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'suppliers_name'=>'required',
            'suppliers_description'=>'required',
            'suppliers_email'=>'required',
            'suppliers_phone'=>'required',
        ]);

        $supplier   =   new \App\Models\Supplier();

        $supplier->name =   $request->suppliers_name;
        $supplier->description =   $request->suppliers_description;
        $supplier->email =   $request->suppliers_email;
        $supplier->phone =   $request->suppliers_phone;
        $supplier->company_id   =   Auth::user()->company_id;

        try {
            $supplier->save();
            return redirect()->route('manager.suppliers')->with('successMsg','Supplier Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
