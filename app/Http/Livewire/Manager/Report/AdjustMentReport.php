<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\LensType;
use App\Models\Product;
use Livewire\Component;

class AdjustMentReport extends Component
{
    public $start_date, $end_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;

    public $current_stock   =   0,$paginat=500;

    public $types,$lens_type,$showType=false,$categories,$category;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date',

        'category' => 'required',
        'lens_type'=>'required_if:category,1'
    ];

    function loadMore(){
        $this->paginat  +=  500;
    }

    function updatedCategory(){
        if ($this->category=='1') {
            $this->types    =   LensType::get();
            $this->showType =   true;
        }
        else{
            $this->showType=false;
        }
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
        $this->categories   =   Category::get();
        // $this->types        =   LensType::get();
        if ($this->category=='1') {
            $this->products =   Product::where('company_id', userInfo()->company_id)->where('category_id',$this->category)->where('product_name',$this->lens_type)->get();}
        else{
        $this->products =   Product::where('company_id', userInfo()->company_id)->where('category_id',$this->category)->get();
        }
        return view('livewire.manager.report.adjust-ment-report')->layout('livewire.livewire-slot');
    }
}
