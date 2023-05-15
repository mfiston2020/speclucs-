<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ProformaController extends Controller
{
    function index()
    {
        $proformas  =   \App\Models\Proforma::join('insurances','proformas.insurance_id','insurances.id')
                                                ->where('proformas.company_id',Auth::user()->company_id)
                                                ->select('proformas.*','insurances.insurance_name')
                                                ->orderBy('created_at','DESC')->get();


        return view('manager.proforma.index',compact('proformas'));
    }

    function create()
    {
        return view('manager.proforma.create');
    }

    function create_new(Request $request)
    {

        // return $request->all();

        if ($request->category=='system')
        {
            $proforma   =   new \App\Models\Proforma();

            $proforma->company_id       =   Auth::user()->company_id;
            $proforma->patient_id       =   $request->customer;
            $proforma->insurance_id     =   $request->insurance;
            $proforma->patient_phone    =   $request->phone;
            $proforma->patient_email    =   $request->email;
            $proforma->patient_number   =   $request->patient_number;

            try {
                $proforma->save();
                return redirect()->route('manager.proforma.detail',Crypt::encrypt($proforma->id))->with('successMsg','Proforma successfully created!');
            } catch (\Throwable $th) {
                return  redirect()->back()->with('errorMsg','Something went wrong!');
            }
        }

        if ($request->category=='new')
        {
            $proforma   =   new \App\Models\Proforma();

            $proforma->company_id       =   Auth::user()->company_id;
            $proforma->insurance_id     =   $request->insurance;
            $proforma->patient_firstname=   $request->firstname;
            $proforma->patient_lastname =   $request->lastname;
            $proforma->patient_phone    =   $request->phone;
            $proforma->patient_email    =   $request->email;
            $proforma->patient_number   =   $request->patient_number;

            try {
                $proforma->save();
                return redirect()->route('manager.proforma.detail',Crypt::encrypt($proforma->id))->with('successMsg','Proforma successfully created!');
            } catch (\Throwable $th) {
                return  redirect()->back()->with('errorMsg','Something went wrong!');
            }
        }
    }

    function detail($id)
    {
        $proforma   =   \App\Models\Proforma::find(Crypt::decrypt($id));
        $proforma_products  =   \App\Models\ProformaProduct::where('proforma_id',$proforma->id)->get();

        return view('manager.proforma.detail',compact('proforma','proforma_products'));
    }

    function addProduct($id)
    {
        $proforma   =   Crypt::decrypt($id);
        return view('manager.proforma.addProduct',compact('proforma'));
    }

    function saveProduct(Request $request)
    {
        $product_check  =   \App\Models\ProformaProduct::where('company_id',Auth::user()->company_id)->where('proforma_id',$request->proforma_id)->where('product_id',$request->product_id)->first();

        if($product_check==null)
        {
            $proforma_product   =   new \App\Models\ProformaProduct();

            $proforma_product->company_id   =   Auth::user()->company_id;
            $proforma_product->proforma_id  =   $request->proforma_id;
            $proforma_product->product_id   =   $request->product_id;
            $proforma_product->quantity     =   $request->quantity;
            $proforma_product->unit_price   =   $request->unit_price;
            $proforma_product->insurance_percentage =   $request->percentage;
            $proforma_product->total_amount =   $request->quantity*$request->unit_price;

            try {
                $proforma_product->save();
                return redirect()->route('manager.proforma.detail',Crypt::encrypt($request->proforma_id))->with('successMsg','Proforma Product Added!!');
            } catch (\Throwable $th) {
                return  redirect()->back()->with('errorMsg','Something went wrong!');
            }
        }
        else{

            $proforma_product   =   \App\Models\ProformaProduct::find($product_check->id);

            $proforma_product->quantity     =   $product_check->quantity+$request->quantity;
            $proforma_product->total_amount =   ($product_check->quantity+$request->quantity)*$request->unit_price;

            try {

                $proforma_product->save();

                return redirect()->route('manager.proforma.detail',Crypt::encrypt($request->proforma_id))->with('successMsg','Proforma Product Added!!');
            } catch (\Throwable $th) {
                return  redirect()->back()->with('errorMsg','Something went wrong!');
            }
        }
    }

    function removeProduct($id)
    {
        $product    =   \App\Models\ProformaProduct::find(Crypt::decrypt($id));
        try {
            $product->delete();
            return redirect()->back()->with('successMsg','Proforma Product removed!!');
        } catch (\Throwable $th) {
            return  redirect()->back()->with('errorMsg','Something went wrong!');
        }
    }

    function finalizeProforma($id)
    {
        $proforma   =   \App\Models\Proforma::find(Crypt::decrypt($id));

        $proforma->status   =   'finalized';
        try
        {
            $proforma->save();
            return redirect()->back()->with('successMsg','Proforma Finalized!!');
        } catch (\Throwable $th)
        {
            return  redirect()->back()->with('errorMsg','Something went wrong!');
        }
    }

    function printProforma($id)
    {
        $insurance  =   0;
        $client     =   0;

        $proforma   =   \App\Models\Proforma::findOrFail(Crypt::decrypt($id));
        $proforma_detail   =   \App\Models\ProformaProduct::join('proformas','proforma_products.proforma_id','proformas.id')
                                                        ->join('products','proforma_products.product_id','products.id')
                                                        ->leftJoin('powers','powers.product_id','products.id')
                                                        ->where('proforma_products.proforma_id',Crypt::decrypt($id))
                                                        ->get();

        return view('manager.proforma.print',compact('proforma','proforma_detail','insurance','client'));
    }

    function editProduct($id)
    {
        $product    =   \App\Models\ProformaProduct::join('products','proforma_products.product_id','products.id')
                                                    ->where('proforma_products.id',Crypt::decrypt($id))
                                                    ->select('products.product_name','proforma_products.*')->first();

        return view('manager.proforma.edit',compact('product'));
    }

    function updateProduct(Request $request)
    {
        $product   =   \App\Models\ProformaProduct::find($request->proforma_id);

        $product->insurance_percentage  =   $request->percentage;
        $product->quantity              =   $request->quantity;
        $product->total_amount          =   $request->quantity*$request->unit_price;

        try {

            $product->save();

            return redirect()->route('manager.proforma.detail',Crypt::encrypt($product->proforma_id))->with('successMsg','Proforma Product Added!!');
        } catch (\Throwable $th) {
            return  redirect()->back()->with('errorMsg','Something went wrong!');
        }
    }

    public function approveProforma($id)
    {
        $product   =   \App\Models\Proforma::find(Crypt::decrypt($id));

        $product->status    =   'approved';

        try {

            $product->save();

            return redirect()->route('manager.proforma.detail',$id)->with('successMsg','Proforma PApproved!!');
        } catch (\Throwable $th) {
            return  redirect()->back()->with('errorMsg','Something went wrong!');
        }
    }

    public function declineProforma($id)
    {
        $product   =   \App\Models\Proforma::find(Crypt::decrypt($id));

        $product->status    =   'rejected';

        try {

            $product->save();

            return redirect()->route('manager.proforma.detail',$id)->with('successMsg','Proforma declined!!');
        } catch (\Throwable $th) {
            return  redirect()->back()->with('errorMsg','Something went wrong!'.$th);
        }
    }

    function insuranceProforma(){
        return view('manager.invoices.insurance-invoice');
    }
}
