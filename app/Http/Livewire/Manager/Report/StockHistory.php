<?php

namespace App\Http\Livewire\Manager\Report;

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

    public $rawMaterialReport, $lensrawMaterialReport,$paginat  =   500;

    public $result = false;

    protected $rules = [
        'end_date' => 'required',
        'start_date' => 'required',
    ];

    function loadMore(){
        $this->paginat  +=  500;
        $this->searchInformation();
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

            $this->products =   Product::where('company_id', userInfo()->company_id)->orderBy('created_at','desc')->take($this->paginat)->get();

            $this->soldProducts =   TrackStockRecord::where('company_id', userInfo()->company_id)->whereDate('created_at', '>=', date('Y-m-d', strtotime($this->start_date)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($this->end_date . '+1day')))->get();

            // $this->receivedProducts =   ReceivedProduct::where('company_id', userInfo()->company_id)->whereDate('created_at', '>=', date('Y-m-d', strtotime($this->start_date)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($this->end_date)))->get();


            if (count($this->products) <= 0) {
                $this->searchFoundSomething = 'no';
            } else {
                $this->searchFoundSomething = 'yes';
            }
        }
        // dd($this->productListingVariation);

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
        return view('livewire.manager.report.stock-history')->layout('livewire.livewire-slot');
    }
}
