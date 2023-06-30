<?php

namespace App\Repositories;

use App\Interface\SalesInterface;
use App\Models\SoldProduct;
use Illuminate\Support\Facades\Auth;

class SalesRepo implements SalesInterface{

   function addProductToInvoice(array $request){

        $sold   =   new SoldProduct();

        // $sold->invoice_id   =   $request['invoice_id'];
        // $sold->product_id   =   $request['product'];
        // $sold->quantity     =   $request['quantity'];
        // $sold->unit_price   =   $request['price'];
        // $sold->discount     =   $request['discount']??'0';
        // $sold->total_amount =   $request['total_amount'];
        // $sold->company_id   =   Auth::user()->company_id;
        // $sold->save();
   }

}
