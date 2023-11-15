<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ClosingReport extends Component
{
    // use WithPagination;

    public $closing_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;

    public $current_stock   =   0;

    protected $rules = [
        'closing_date' => 'required',
    ];

    function searchInformation()
    {
        $this->validate();
        $this->products =   Product::where('company_id', userInfo()->company_id)->take(100);

        if (count($this->products) <= 0) {
            $this->searchFoundSomething = 'no';
        } else {
            $this->searchFoundSomething = 'yes';
        }

        $this->result   =   true;
    }

    public function render()
    {
        return view('livewire.manager.report.closing-report')->layout('livewire.livewire-slot');
    }
}
