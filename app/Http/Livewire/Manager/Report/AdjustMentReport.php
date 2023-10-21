<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Product;
use Livewire\Component;

class AdjustMentReport extends Component
{
    public $start_date, $end_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;

    public $current_stock   =   0;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ];



    function searchInformation()
    {
        $this->validate();
        $this->products =   Product::where('company_id', userInfo()->company_id)->get();

        if (count($this->products) <= 0) {
            $this->searchFoundSomething = 'no';
        } else {
            $this->searchFoundSomething = 'yes';
        }

        $this->result   =   true;
    }


    public function render()
    {
        return view('livewire.manager.report.adjust-ment-report')->layout('livewire.livewire-slot');
    }
}
