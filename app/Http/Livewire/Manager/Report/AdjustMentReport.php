<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Product;
use Livewire\Component;

class AdjustMentReport extends Component
{
    public $start_date, $end_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;

    public $current_stock   =   0,$paginat=500;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ];

    function loadMore(){
        $this->paginat  +=  500;
    }



    function searchInformation()
    {
        $this->validate();

        if (count($this->products) <= 0) {
            $this->searchFoundSomething = 'no';
        } else {
            $this->searchFoundSomething = 'yes';
        }

        $this->result   =   true;
    }


    public function render()
    {
        $this->products =   Product::where('company_id', userInfo()->company_id)->take($this->paginat)->get();
        return view('livewire.manager.report.adjust-ment-report')->layout('livewire.livewire-slot');
    }
}
