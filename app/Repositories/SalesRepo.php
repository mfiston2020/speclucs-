<?php

namespace App\Repositories;

use App\Interface\SalesInterface;
use App\Models\Invoice;
use App\Models\SoldProduct;
use App\Models\TrackOrderRecord;
use Illuminate\Support\Facades\Auth;

class SalesRepo implements SalesInterface{

   function addProductToInvoice(array $request){

      //   $sold   =   new SoldProduct();

        // $sold->invoice_id   =   $request['invoice_id'];
        // $sold->product_id   =   $request['product'];
        // $sold->quantity     =   $request['quantity'];
        // $sold->unit_price   =   $request['price'];
        // $sold->discount     =   $request['discount']??'0';
        // $sold->total_amount =   $request['total_amount'];
        // $sold->company_id   =   Auth::user()->company_id;
        // $sold->save();
   }


   function createInvoice(array $product){

    $invoiceStatus  =   null;
    $count          =   0;

    $reference  =   Invoice::where('company_id', userInfo()->company_id)->select('reference_number')->count();

    $productbooking =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $product['product']['product_id'])->withSum('soldproduct', 'quantity')->get();
    $prductbookingQuantity  =   $productbooking->sum('soldproduct_sum_quantity');

    if ($product['product']['stock'] < 1) {
        $invoiceStatus  =   'Confirmed';
    } else {
        if ($prductbookingQuantity>$product['product']['stock']-1) {
            $invoiceStatus  =   'Booked';
        } else {
            $invoiceStatus  =   'requested';
        }
    }
    if (!Invoice::where('transaction_id',$product['transaction']['transaction_id'])->exists()) {
    
        $invoice    =   new Invoice();

        $invoice->reference_number  =   $reference + 1;
        $invoice->status            =   'pending';
        $invoice->user_id           =   userInfo()->id;
        $invoice->total_amount      =   '0';
        $invoice->hospital_name     =   $product['transaction']['vision_center'];
        $invoice->company_id        =   userInfo()->company_id;
        $invoice->client_id         =   null;
        $invoice->client_name       =   null;
        $invoice->affiliate_names   =   null;
        $invoice->phone             =   null;
        $invoice->tin_number        =   null;
        $invoice->gender            =   null;
        $invoice->dateOfBirth       =   null;
        $invoice->insurance_id      =   null;
        $invoice->status            =   $invoiceStatus;
        $invoice->transaction_id    =   $product['transaction']['transaction_id'];
        $invoice->total_amount      =   0;
        $invoice->supplier_id       =   null;
        $invoice->insurance_card_number =   null;

        $invoice->save();

        TrackOrderRecord::create([
            'status'        =>  $invoiceStatus,
            'user_id'       =>  auth()->user()->id,
            'invoice_id'    =>  $invoice->id,
        ]);
        $this->saveProductOrder($invoice->id,$product);

    } else{
        $invoiceId  =   Invoice::where('transaction_id',$product['transaction']['transaction_id'])->pluck('id')->first();
        $this->saveProductOrder($invoiceId,$product);
    }
   }

   function saveProductOrder(string $invoiceId,array $productInfo){

        $total = 0;

        if (!is_null($productInfo['transaction']['eye'])) {

            $sold   =   new SoldProduct();

            $sold->company_id   =   userInfo()->company_id;
            $sold->product_id   =   $productInfo['transaction']['product_id'];
            $sold->invoice_id   =   $invoiceId;
            $sold->quantity     =   '1';
            $sold->discount     =   '0';
            $sold->eye          =   $productInfo['transaction']['eye'];
            $sold->unit_price   =   $productInfo['product']['price'];
            $sold->total_amount =   $productInfo['product']['price'];
            $sold->segment_h    =   '-';
            $sold->mono_pd      =   '-';
            $sold->axis         =   '-';
            $sold->is_private   =   null;
            $sold->insurance_id =   null;
            $sold->percentage   =   null;

            $sold->patient_payment   =   null;
            $sold->approved_amount   =   null;
            $sold->insurance_payment =   null;
            $sold->save();

            $total  += $sold->total_amount;
        } else {
            $sellOther   =   new SoldProduct();

            $sellOther->company_id   =   userInfo()->company_id;
            $sellOther->product_id   =   $productInfo['transaction']['product_id'];
            $sellOther->invoice_id   =   $invoiceId;
            $sellOther->quantity     =   '1';
            $sellOther->discount     =   '0';
            $sellOther->unit_price   =   $productInfo['product']['price'];
            $sellOther->total_amount =   $productInfo['product']['price']*1;
            $sellOther->is_private   =   null;
            $sellOther->insurance_id =   null;
            $sellOther->percentage   =   null;

            $sellOther->patient_payment   =   null;
            $sellOther->approved_amount   =   null;
            $sellOther->insurance_payment =   null;
            $sellOther->save();

            $total  += $sellOther->total_amount;
        }

        Invoice::where('id', $invoiceId)->update([
            'total_amount' => $total
        ]);
    }
}
