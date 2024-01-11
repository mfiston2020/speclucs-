<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\LensType;
use App\Models\Product;
use Livewire\Component;

class ClosingReport extends Component
{


    public $closing_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;
    public $types,$lens_type,$showType=false,$categories,$category;

    public $current_stock   =   0, $paginat =   500;

    protected $rules = [
        'category' => 'required',
        'closing_date' => 'required',
        'lens_type'=>'required_if:category,1'
    ];

    protected $messages=[
        'lens_type.required_id'=>'Field Required if Category is Lens',
    ];


    function updatedCategory(){
        if ($this->category=='1') {
            $this->types    =   LensType::get();
            $this->showType =   true;
        }
        else{
            $this->showType=false;
        }
    }

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
        $this->categories   =   Category::get();
        // $this->types        =   LensType::get();
        if ($this->category=='1') {
            $this->products =   Product::where('company_id', userInfo()->company_id)->where('category_id',$this->category)->where('product_name',$this->lens_type)->orderBy('created_at','desc')->get();
        }else{
            $this->products =   Product::where('company_id', userInfo()->company_id)->where('category_id',$this->category)->take($this->paginat)->orderBy('created_at','desc')->get();
        }
        return view('livewire.manager.report.closing-report')->layout('livewire.livewire-slot');
    }
}
