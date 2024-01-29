<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\LensType;
use App\Models\Product;
use App\Models\TrackStockRecord;
use Carbon\Carbon;
use Livewire\Component;

class StockHistory extends Component
{

    public $start_date, $end_date, $searchFoundSomething = 'yes';
    public $productListing = [];
    public $productListingVariation = [];
    public $dateList = [];
    public $productArrayList = array();
    public $products, $stockRecords, $soldProducts, $receivedProducts;
    public $daysCount   =   0;

    public $rawMaterialReport, $lensrawMaterialReport;

    public $result = false;

    public $types,$lens_type,$showType=false,$categories,$category;

    protected $rules = [
        'end_date' => 'required',
        'start_date' => 'required',

        'category' => 'required',
        'lens_type'=>'required_if:category,1'
    ];

    // function loadMore(){
    //     $this->searchInformation();
    // }

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
        $this->resetData();
        $this->validate();

        if ($this->start_date > $this->end_date) {
            dd('operation not allowed!');
        } else {
            $datecount  =   0;
            $carbonDate =   Carbon::create($this->start_date);
            $dateDiff   =   $carbonDate->diffInDays($this->end_date);
            $this->daysCount    =   $dateDiff;
            // dd($dateDiff);
            $dates = [];
            for ($sDate = 1; $sDate <= $dateDiff; $sDate++) {
                $carbonDate =   Carbon::create($this->start_date);
                array_push($this->dateList, $carbonDate->addDay($sDate)->format('Y-m-d'));
            }

            if ($this->category=='1') {
                $this->products =   Product::where('company_id', userInfo()->company_id)->orderBy('created_at','asc')->where('category_id',$this->category)->where('product_name',$this->lens_type)->get();
            } else {
                $this->products =   Product::where('company_id', userInfo()->company_id)->orderBy('created_at','asc')->where('category_id',$this->category)->get();
            }

            // dd($this->products);

            $this->soldProducts =   TrackStockRecord::where('company_id', userInfo()->company_id)->with('product')->whereHas('product',function($query){
                $query->where('category_id',$this->category);
            })->whereDate('created_at', '>=', date('Y-m-d', strtotime($this->start_date)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($this->end_date . '+1day')))->get();

            // dd($this->soldProducts);

            if (count($this->products) <= 0) {
                $this->searchFoundSomething = 'no';
            } else {
                $this->searchFoundSomething = 'yes';
            }
        }

        $this->result   =   true;
    }

    function resetData()
    {
        $this->productListing = [];
        $this->dateList = [];
    }

    public function render()
    {
        // $this->searchInformation();
        $this->categories   =   Category::get();
        return view('livewire.manager.report.stock-history')->layout('livewire.livewire-slot');
    }
}
