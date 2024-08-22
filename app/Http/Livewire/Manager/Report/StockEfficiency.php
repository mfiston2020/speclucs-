<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\Product;
use App\Models\TrackStockRecord;
use Livewire\Component;

class StockEfficiency extends Component
{
    public $result = false, $productEfficiency;
    public $start_date, $end_date, $searchFoundSomething = 'yes';
    public $types, $lens_type, $showType = false, $categories, $category;

    function updatedCategory()
    {
        $this->resetData();
        $this->result   =   false;
    }

    function searchInformation()
    {
        if ($this->start_date > $this->end_date) {
            dd('operation not allowed!');
        } else {
            $products   = [];
            $this->resetData();

            $this->validate([
                'category' => 'required',
                'end_date' => 'required',
                'start_date' => 'required',
            ]);

            $products   =   Product::where('category_id', $this->category)->where('company_id', userInfo()->company_id)->select('id','category_id', 'product_name', 'stock', 'description')
                ->withOnly('soldProducts', function ($q) {
                    $q->wherebetween('created_at', [$this->start_date, $this->end_date]);
                })->with('power:id,product_id,sphere,cylinder,axis,add')->withsum('soldproducts', 'quantity')
                ->get();

            // checking the stock that was available by the date
            $stockThen  =   TrackStockRecord::where('company_id', userInfo()->company_id)
                ->wherebetween('created_at', [$this->start_date, $this->end_date])
                ->select('id', 'product_id', 'company_id', 'current_stock')
                ->get();

            foreach ($products as $key => $product) {

                // if(count($stockThen->where('product_id',$product->id)->all())>0){
                $stock  =   $stockThen->where('product_id', $product->id)->pluck('current_stock')->last();

                $usage          =   ($product->soldproducts_sum_quantity == 0 || $product->soldproducts_sum_quantity == null) ? 0 : $product->soldproducts_sum_quantity;
                $keeping        =   $usage * 3 == 0 ? 1 : $usage * 3;
                $currentStock   =   $stock == 0 || is_null($stock) ? 1 : $stock;

                $efficienctyLevel   =   $keeping > $currentStock ? ($currentStock / $keeping) * 100 : ($keeping / $currentStock) * 100;

                $this->productEfficiency[$key] = [
                    'product'       =>  $product,
                    'usage'         =>  $usage,
                    'Qty_to_keep'   =>  $keeping,
                    'efficiency'    =>  $currentStock == $keeping ? 100 : $efficienctyLevel,
                    'greater'       =>  $currentStock < $keeping ? true : false,
                    'less'          =>  $currentStock == $keeping ? true : ($currentStock > $keeping ? true : false),
                ];
            }
            if (count($this->productEfficiency) <= 0) {
                $this->searchFoundSomething = 'no';
                $this->result   =   false;
            } else {
                $this->result   =   true;
                $this->searchFoundSomething = 'yes';
            }
            // }

            // dd($this->productEfficiency[89]);
        }
    }

    function resetData()
    {
        $this->productEfficiency    =   [];
    }

    public function render()
    {
        $this->categories   =   Category::get();
        return view('livewire.manager.report.stock-efficiency')->layout('livewire.livewire-slot');
    }
}
