<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\LensType;
use App\Models\Product;
use Livewire\Component;

class ClosingReport extends Component
{


    public $closing_date, $searchFoundSomething = 'yes', $result = false, $products = [], $product_sold = 0;
    public $types,$lens_type,$showType=false,$categories,$category,$dateNow;

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

    function searchInformation()
    {
        $this->validate();
        $this->dateNow = now();

        // $stockTracker   =   \App\Models\TrackStockRecord::whereDate('created_at','>=',date('Y-m-d',strtotime($closing_date)))->whereDate('created_at','<=',date('Y-m-d',strtotime($dateNow.'-1day')))->where('company_id',userInfo()->company_id)->where('type','rm')->get();

        // $stockTracker2   =   \App\Models\TrackStockRecord::whereDate('created_at','>=',date('Y-m-d',strtotime($closing_date)))->whereDate('created_at','<=',date('Y-m-d',strtotime($dateNow)))->where('company_id',userInfo()->company_id)->where('type','rm')->get();


        if ($this->category=='1') {

            $this->products =   Product::where('company_id', userInfo()->company_id)
                                        ->with(['power','category'])
                                        ->with('productTrack',function($query){
                                            $query->where('operation','in')->sum('incoming');
                                        })
                                        ->where('product_name',$this->lens_type)
                                        ->where('category_id',$this->category)
                                        ->orderBy('created_at','asc')
                                        ->select('id','category_id','product_name','description','cost','price')
                                        ->get();
        }else{
            $this->products =   Product::where('company_id', userInfo()->company_id)
                                        ->with(['category','productTrack'=>function($query){
                                            $query->whereDate('created_at','>=',date('Y-m-d',strtotime($this->closing_date)))
                                                    ->whereDate('created_at','<=',date('Y-m-d',strtotime($this->dateNow.'-1day')))
                                                    ->where('operation','in')->sum('incoming');
                                        }])
                                        ->orderBy('created_at','asc')
                                        ->where('category_id',$this->category)
                                        ->select('id','category_id','product_name','description','cost','price')
                                        ->get();
        }

        if (count($this->products) <= 0) {
            $this->searchFoundSomething = 'no';
        } else {
            $this->searchFoundSomething = 'yes';
        }

        $this->result   =   true;

        // foreach ($this->products as $key => $prod) {
        //     if (!$prod->productTrack->isEmpty()) {
        //         dd(;
        //     }
        // }
    }

    public function render()
    {
        $this->categories   =   Category::get();

        return view('livewire.manager.report.closing-report')->layout('livewire.livewire-slot');
    }
}
