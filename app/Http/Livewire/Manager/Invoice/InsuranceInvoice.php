<?php

namespace App\Http\Livewire\Manager\Invoice;

use App\Models\Insurance;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InsuranceInvoice extends Component
{
    public $insurances,$insurance,$months,$invoices,$allData,$showData=false,$year,$month;


    protected $rules    =   [
        'year'=>'required',
        'month'=>'required',
        'insurance'=>'required',
    ];

    function searchInformation(){
        $validated  =   $this->validate();
        
    }

    function mount(){
        $this->invoices     =   Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')->get();
        $this->insurances    =   Insurance::where('company_id',userInfo()->id)->select('id','insurance_name')->get();
    }

    public function render()
    {
        $this->months   =   makeMonths();
        return view('livewire.manager.invoice.insurance-invoice');
    }
}
