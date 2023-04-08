<?php

namespace App\Http\Livewire\Manager\Invoice;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Insurance;
use App\Models\Invoice;
use App\Models\SoldProduct;
use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InsuranceInvoice extends Component
{
    public $insurance,$months,$invoices,$allData;

    function mount(){
        $this->invoices     =   Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')->get();
        $this->insurance    =   Insurance::where('company_id',userInfo()->id)->select('id','insurance_name')->get();

        // foreach ($this->invoices as $key => $sale) {
        //     $client     =    Customer::where(['id'=>$sale->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first();

        //     $product    =   SoldProduct::where(['invoice_id'=>$sale->id])
        //                                         ->where('company_id',Auth::user()->company_id)
        //                                         ->select('product_id','insurance_id','insurance_payment','patient_payment')
        //                                         ->first();

        //     $amount_paid    =       Transactions::where('invoice_id',$sale->id)->select('amount')->sum('amount');

        //     $ins_due_amount =       SoldProduct::where(['invoice_id'=>$sale->id])
        //                                         ->where('company_id',Auth::user()->company_id)
        //                                         ->select('insurance_payment')
        //                                         ->sum('insurance_payment');

        //     $pt_due_amount =    SoldProduct::where(['invoice_id'=>$sale->id])
        //                                         ->where('company_id',Auth::user()->company_id)
        //                                         ->select('patient_payment')
        //                                         ->sum('patient_payment');
        //     $type           =   Category::where('id',$product->category_id)->pluck('name')->first();


        //     if ($product->insurance_id!=null)
        //     {
        //         $this->allData[]  =   array([
        //             'type'          =>  $type,
        //             'clients'       =>  $client!=null?$client:$sale->client_name,
        //             'product'       =>  $product,
        //             'amount_paid'   =>  $amount_paid,
        //             'pt_due_amount' =>  $pt_due_amount,
        //             'ins_due_amount'=>  $ins_due_amount,
        //         ]);
        //     }
        // }
        // dd($this->allData);
    }

    public function render()
    {
        $this->months   =   makeMonths();
        return view('livewire.manager.invoice.insurance-invoice');
    }
}
