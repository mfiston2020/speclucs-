<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class NewSalesController extends Controller
{
    function newOrder()
    {
        $reference  =   count(DB::table('invoices')->select('reference_number')->where('company_id',Auth::user()->company_id)->get());
        $pending    =   count(DB::table('invoices')->select('*')->where('status','=','pending')->where('company_id',Auth::user()->company_id)->where('user_id','=',Auth::user()->id)->get());

        if ($pending>0)
        {
            return redirect()->back()->with('warningMsg','Complete pending invoices first!');
        }
        else{
            $invoice    =   new \App\Models\Invoice();

            $invoice->reference_number  =   $reference+1;
            $invoice->status            =   'pending';
            $invoice->user_id           =   Auth::user()->id;
            $invoice->total_amount      =   '0';
            $invoice->company_id        =   Auth::user()->company_id;

            try {
                $invoice->save();
                $invoice_id =   $invoice->id;
                return view('manager.sales.newOrder',compact('invoice_id'));
                
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
            }
        }

    }
}
