<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Product;
use Livewire\Component;

class ClosingReport extends Component
{
    public $closing_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;

    public $current_stock   =   0;

    protected $rules = [
        'closing_date' => 'required',
    ];

    function searchInformation()
    {
        $this->validate();
        $this->products =   Product::where('company_id', userInfo()->company_id)->paginate(100);

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
