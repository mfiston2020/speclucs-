<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt as FacadesCrypt;

class OrderCreditsController extends Controller
{
    public function index()
    {
        return view('manager.credits.index');
    }
    // ========================
    public function myCredits()
    {
        $credits =   \App\Models\InvoiceCredits::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')->get();
        return view('manager.credits.myCredits',compact('credits'));
    }
    // ===============================
    public function requestCredit($id)
    {
        $invoice =   \App\Models\OrderInvoice::find(FacadesCrypt::decrypt($id));
        return view('manager.credits.create',compact('invoice'));
    }
    // =========================================
    public function creditSave(Request $request)
    {
        $credit =   new \App\Models\InvoiceCredits();

        $credit->order_id       =   $request->order_id;
        $credit->invoice_id     =   $request->invoice_id;
        $credit->supplier_id    =   $request->supplier_id;
        $credit->company_id     =   Auth::user()->company_id;
        $credit->credit_amount  =   $request->amount;

        try {
            $credit->save();

            $notification   =   new \App\Models\SupplierNotify();
            $notification->company_id   =   Auth::user()->company_id;
            $notification->supplier_id  =   $request->supplier_id;
            $notification->credit_id    =   $credit->id;
            $notification->notification =   'New Credit Request';
            $notification->save();

            return redirect()->route('manager.credit')->with('successMsg','Your Credit Request successfully saved!');
        } catch (\Throwable $th) {

            return redirect()->back()->with('errorMsg','Something Went Wrong!' )->withInput();

            return redirect()->back()->with('errorMsg','Something Went Wrong!' .$th)->withInput();
            return redirect()->back()->with('errorMsg','Something Went Wrong!' )->withInput();

        }
    }

    public function requestdcredit()
    {
        $credits =   \App\Models\InvoiceCredits::where('supplier_id',Auth::user()->company_id)->orderBy('created_at','DESC')->get();
        return view('manager.credits.myrequest',compact('credits'));
    }

    public function acceptcredit(Request $request)
    {
        $credit =   \App\Models\InvoiceCredits::find($request->credit_id);

        $credit->additional_amount  =   $request->balance;
        $credit->status  =   'approved';

        try {
            $credit->save();

            $invoice    =   new \App\Models\OrderInvoice();

            $invoice->supplier_id   =   Auth::user()->company_id;
            $invoice->order_id   =   $credit->order_id;
            $invoice->company_id    =   $credit->company_id;
            $invoice->invoice_number=   'CREDIT';
            $invoice->cost          =   '-'.$credit->credit_amount+$credit->additional_amount;
            $invoice->save();

            return redirect()->back()->with('successMsg','Request scuccessfully approved');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something Went Wrong');
        }
    }

    public function declinecredit (Request $request)
    {
        $credit =   \App\Models\InvoiceCredits::find($request->credit_id);

        $credit->comment  =   $request->comment;
        $credit->status  =   'declined';

        try {
            $credit->save();
            return redirect()->back()->with('successMsg','Request scuccessfully Declined');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg','Something Went Wrong');
        }
    }
}
