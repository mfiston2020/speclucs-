<?php

namespace App\Http\Livewire\Manager\Invoice;

use App\Models\Insurance;
use App\Models\InsuranceInvoiceSumary;
use App\Models\InsuranceSumaryInvoices;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InsuranceInvoice extends Component
{
    public $insurances, $insurance, $invoices,$invoiced, $invoicesIds=[],$invoicesAmounts=[],$invoiceCredit=[], $credits = array(), $allData, $showData = false, $start_date, $end_date;


    protected $rules    =   [
        'start_date' => 'required',
        'end_date' => 'required',
        'insurance' => 'required',
    ];

    function updatedinvoicesIds(){
        $this->searchInformation();
    }

    function updatedinvoiceCredit(){
        $this->searchInformation();
    }

    function searchInformation()
    {
        $validated  =   $this->validate();
        $fromDate   =   date('Y-m-d', strtotime($validated['start_date']));
        $toDate     =   date('Y-m-d', strtotime($validated['end_date']));

        $this->invoices =   Invoice::where('company_id', Auth::user()->company_id)
            ->where('insurance_id', $validated['insurance'])
            ->whereNotNull('gender')
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->withsum('soldproduct', 'total_amount')
            ->withsum('soldproduct', 'insurance_payment')
            ->orderBy('created_at', 'DESC')->get();

        $this->invoiced =   $this->invoices->where('invoice_status','invoiced');;

        $this->showData =   true;
    }

    function addInvoiceCredit()
    {
        $this->searchInformation();

        // dd('ljsljelj');
    }

    function createInvoiceSummary(){


        if (!InsuranceInvoiceSumary::whereMonth('created_at',date('m-Y'))->whereYear('created_at',date('Y'))->exists()) {

            // dd('found');
            $summary =   InsuranceInvoiceSumary::create([
                'company_id'    =>  userInfo()->company_id,
                'user_id'   =>  userInfo()->id,
                'insurance_id'  =>  $this->insurance,
            ]);

            foreach ($this->invoicesIds as $key => $invoice) {
                if($invoice['chekboxId']!=false){
                    if (!InsuranceSumaryInvoices::where('invoice_sumary_id',$summary->id)->where('invoice_id',$invoice['chekboxId'])->exists()) {

                        $invoiceAmount = Invoice::where('id',$invoice['chekboxId'])->withsum('soldproduct','total_amount')->first();
                        $invoiceInsAmount = Invoice::where('id',$invoice['chekboxId'])->withsum('soldproduct','insurance_payment')->first();

                        InsuranceSumaryInvoices::create([
                            'company_id'        =>  userInfo()->company_id,
                            'invoice_id'        =>  $invoice['chekboxId'],
                            'invoice_sumary_id' =>  $summary->id,
                            'insurance_amount'  =>  $invoiceInsAmount->soldproduct_sum_insurance_payment,
                            'total_amount'      =>  $invoiceAmount->soldproduct_sum_total_amount,
                        ]);
                    }
                };
            }
        }else{
            // dd('not-found');
            $invoiceSumary  =   InsuranceInvoiceSumary::whereMonth('created_at',date('m'))->whereYear('created_at',date('Y'))->first();

            foreach ($this->invoicesIds as $key => $invoice) {
                if($invoice['chekboxId']!=false){
                    if (!InsuranceSumaryInvoices::where('invoice_sumary_id',$invoiceSumary->id)->where('invoice_id',$invoice['chekboxId'])->exists()) {

                        $invoiceAmount = Invoice::where('id',$invoice['chekboxId'])->withsum('soldproduct','total_amount')->first();
                        $invoiceInsAmount = Invoice::where('id',$invoice['chekboxId'])->withsum('soldproduct','insurance_payment')->first();

                        InsuranceSumaryInvoices::create([
                            'company_id'        =>  userInfo()->company_id,
                            'invoice_id'        =>  $invoice['chekboxId'],
                            'invoice_sumary_id' =>  $invoiceSumary->id,
                            'insurance_amount'  =>  $invoiceInsAmount->soldproduct_sum_insurance_payment,
                            'total_amount'      =>  $invoiceAmount->soldproduct_sum_total_amount,
                        ]);
                    }
                };
            }
        }

        $this->searchInformation();
    }

    // function mount()
    // {
    //     $this->invoicesIds  =   [];
    //     $this->insurances   =   Insurance::where('company_id', userInfo()->company_id)->select('id', 'insurance_name')->get();
    // }

    public function render()
    {
        $this->insurances   =   Insurance::where('company_id', userInfo()->company_id)->select('id', 'insurance_name')->get();
        return view('livewire.manager.invoice.insurance-invoice');
    }
}
