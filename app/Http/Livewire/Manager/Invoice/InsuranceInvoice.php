<?php

namespace App\Http\Livewire\Manager\Invoice;

use App\Models\Insurance;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InsuranceInvoice extends Component
{
    public $insurances, $insurance, $invoices, $allData, $showData = false, $start_date, $end_date;


    protected $rules    =   [
        'start_date' => 'required',
        'end_date' => 'required',
        'insurance' => 'required',
    ];

    function searchInformation()
    {
        $validated  =   $this->validate();
        $fromDate   =   date('Y-m-d', strtotime($validated['start_date']));
        $toDate     =   date('Y-m-d', strtotime($validated['end_date']));

        $this->invoices =   Invoice::where('company_id', Auth::user()->company_id)
            ->where('insurance_id', $validated['insurance'])
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->withsum('soldproduct', 'total_amount')
            ->withsum('soldproduct', 'insurance_payment')
            ->orderBy('created_at', 'DESC')->get();

        $this->showData =   true;
    }

    function mount()
    {
        // $this->invoices     =   Invoice::where('company_id', Auth::user()->company_id)->orderBy('created_at', 'DESC')->get();
        $this->insurances   =   Insurance::where('company_id', userInfo()->company_id)->select('id', 'insurance_name')->get();
    }

    public function render()
    {
        return view('livewire.manager.invoice.insurance-invoice');
    }
}
