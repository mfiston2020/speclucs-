<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers   =   \App\Models\Supplier::where('company_id', Auth::user()->company_id)->get();
        return view('manager.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('manager.supplier.create');
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'suppliers_name' => 'required',
            'suppliers_description' => 'required',
            'suppliers_email' => 'required',
            'suppliers_phone' => 'required',
        ]);

        $supplier   =   new \App\Models\Supplier();

        $supplier->name         =   $request->suppliers_name;
        $supplier->description  =   $request->suppliers_description;
        $supplier->email        =   $request->suppliers_email;
        $supplier->phone        =   $request->suppliers_phone;
        $supplier->company_id   =   Auth::user()->company_id;

        try {
            $supplier->save();
            return redirect()->route('manager.suppliers')->with('successMsg', 'Supplier Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
        }
    }

    public function edit($id)
    {
        $supplier   =   Supplier::find(Crypt::decrypt($id));
        return view('manager.supplier.edit', compact('supplier'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'suppliers_name' => 'required',
            'suppliers_description' => 'required',
            'suppliers_email' => 'required |unique:suppliers,email',
            'suppliers_phone' => 'required |unique:suppliers,phone',
        ]);

        $supplier   =   Supplier::find($request->supplier_id);

        $supplier->name =   $request->suppliers_name;
        $supplier->description =   $request->suppliers_description;
        $supplier->email =   $request->suppliers_email;
        $supplier->phone =   $request->suppliers_phone;
        $supplier->company_id   =   Auth::user()->company_id;

        try {
            $supplier->save();
            return redirect()->route('manager.suppliers')->with('successMsg', 'Supplier Updated Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
        }
    }
}
