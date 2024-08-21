<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class StockEfficiency extends Component
{
    public $result = false, $productEfficiency;
    public $start_date, $end_date, $searchFoundSomething = 'yes';
    public $types, $lens_type, $showType = false, $categories, $category;


    function searchInformation()
    {
        $this->resetData();
        $this->validate([
            'category' => 'required',
            'end_date' => 'required',
            'start_date' => 'required',
        ]);

        $products   =   Product::where('category_id', $this->category)->select('id', 'product_name', 'stock', 'description')
            ->withOnly('soldProducts', function ($q) {
                $q->wherebetween('created_at', [$this->start_date, $this->end_date]);
            })->withsum('soldproducts', 'quantity')
            ->get();

        foreach ($products as $key => $product) {

            $usage          =   $product->soldproducts_sum_quantity;
            $keeping        =   $usage * 3;
            $currentStock   =   $product->stock;

            $efficienctyLevel   =   $keeping != 0 && $currentStock != 0 ? ($keeping > $currentStock ? ($currentStock / $keeping) * 100 : ($keeping / $currentStock) * 100) : 'N/A';

            $this->productEfficiency[$key] = [
                'product'       => $product,
                'usage'         => $usage,
                'Qty_to_keep'   => $keeping,
                'efficiency'    => $efficienctyLevel,
                'greater'       =>  $currentStock < $keeping ? true : false,
                'less'          =>  $currentStock > $keeping ? true : false,
            ];
        }
        if (count($this->productEfficiency) <= 0) {
            $this->searchFoundSomething = 'no';
            $this->result   =   false;
        } else {
            $this->result   =   true;
            $this->searchFoundSomething = 'yes';
        }
    }

    function resetData(){
        $this->productEfficiency    =   [];
    }

    public function render()
    {
        $this->categories   =   Category::get();
        return view('livewire.manager.report.stock-efficiency')->layout('livewire.livewire-slot');
    }
}
