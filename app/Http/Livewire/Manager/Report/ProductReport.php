<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Product;
use App\Models\TrackStockRecord;
use Carbon\Carbon;
use Livewire\Component;

class ProductReport extends Component
{
    public $start_date = "2023-07-31", $end_date = "2023-08-01";
    public $productListing = [];
    public $dateList = [];
    public $products, $stockRecords;

    public $rawMaterialReport, $lensrawMaterialReport;

    public $result = false;

    protected $rules = [
        'end_date' => 'required',
        'start_date' => 'required',
    ];

    function searchInformation()
    {
        $this->resetData();
        $this->validate();

        if ($this->start_date > $this->end_date) {
            dd('operation not allowed!');
        } else {
            $datecount  =   0;
            $carbonDate =   Carbon::create($this->start_date);
            $dateDiff   =   $carbonDate->diffInDays($this->end_date) + 1;
            // dd($dateDiff);
            $dates = [];
            for ($sDate = 1; $sDate <= $dateDiff; $sDate++) {
                $carbonDate =   Carbon::create($this->start_date);
                array_push($this->dateList, $carbonDate->addDay($sDate)->format('Y-m-d'));
            }


            $this->products =   Product::where('company_id', userInfo()->company_id)->with('power')->get();

            foreach ($this->products as $key => $product) {
                foreach ($this->dateList as $key => $date) {
                    $this->productListing[$date . '-' . $product->id] = [
                        'product' => $product,
                        'stockChange' => TrackStockRecord::where('product_id', $product->id)->whereDate('created_at', $date)->where('type', 'rm')->sum('incoming'),
                        'current_stock' => TrackStockRecord::where('product_id', $product->id)->whereDate('created_at', $date)->where('type', 'rm')->first()
                    ];
                }
            }
        }
        // dd($this->productListing['2023-08-01-1']['current_stock']);
        $this->result   =   true;
    }

    function resetData()
    {
        $this->productListing = [];
        $this->dateList = [];
    }

    public function render()
    {
        return view('livewire.manager.report.product-report')->layout('livewire.livewire-slot');
    }
}
