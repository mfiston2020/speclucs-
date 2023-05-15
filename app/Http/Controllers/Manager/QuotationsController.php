<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class QuotationsController extends Controller
{
    public function index()
    {
        $quotations =   \App\Models\Quotation::where('company_id',Auth::user()->company_id)->get();
        return view('manager.quotations.index',compact('quotations'));
    }

    public function quations_detail($id)
    {
        $products   =   \App\Models\PurchaseOrder::where('quotation_number',Crypt::decrypt($id))->get();
        return view('manager.quotations.detail',compact('products'));
    }

    public function quations_order(Request $request)
    {
        return $request->all();
    }
}
