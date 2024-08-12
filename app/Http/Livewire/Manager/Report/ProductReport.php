<?php

namespace App\Http\Livewire\Manager\Report;

use App\Models\Category;
use App\Models\LensType;
use App\Models\Product;
use App\Models\TrackStockRecord;
use Carbon\Carbon;
use Livewire\Component;

class ProductReport extends Component
{
    public $start_date, $end_date, $searchFoundSomething = 'yes';
    public $productListing = [];
    public $productListingVariation = [];
    public $dateList = [];
    public $productArrayList = array();
    public $products, $stockRecords;
    public $daysCount   =   0;

    public $rawMaterialReport, $lensrawMaterialReport;

    public $result = false;
    public $types, $lens_type, $showType = false, $categories, $category;

    protected $rules = [
        'end_date' => 'required',
        'start_date' => 'required',
    ];


    function hideResult()
    {
        $this->result   =   false;
    }

    function updatedCategory()
    {
        if ($this->category == '1') {
            $this->types    =   LensType::get();
            $this->showType =   true;
        } else {
            $this->showType = false;
        }
    }

    function updatedstart_date()
    {
        $this->hideResult();
    }

    function updatedend_date()
    {
        $this->hideResult();
    }

    function searchInformation()
    {
        $this->resetData();
        $this->validate([
            'category' => 'required',
            'end_date' => 'required',
            'start_date' => 'required',
        ]);

        if ($this->start_date > $this->end_date) {
            dd('operation not allowed!');
        } else {
            $datecount  =   0;
            $carbonDate =   Carbon::create($this->start_date);
            $dateDiff   =   $carbonDate->diffInDays($this->end_date);
            $this->daysCount    =   $dateDiff;

            $dates = [];
            for ($sDate = 1; $sDate <= $dateDiff; $sDate++) {
                $carbonDate =   Carbon::create($this->start_date);
                array_push($this->dateList, $carbonDate->addDay($sDate)->format('Y-m-d'));
            }

            $this->products =   Product::where('company_id', userInfo()->company_id)->where('category_id', $this->category)->select('id', 'product_name', 'description', 'cost', 'location')->with('power:id,product_id,sphere,cylinder,axis,add,eye')->get();

            try {
                foreach ($this->products as $key => $product) {
                    foreach ($this->dateList as $key => $date) {
                        $incoming       =   TrackStockRecord::where('product_id', $product->id)->whereDate('created_at', $date)->where('type', 'rm')->select('id', 'incoming', 'current_stock')->first();

                        if (!is_null($incoming)) {
                            $closingStock   =   TrackStockRecord::where('product_id', $product->id)->where('type', 'rm')->pluck('change')->first();

                            $instock    =   $incoming->where('product_id', $product->id)->where('operation', 'in')->sum('incoming');
                            $outstock   =   $incoming->where('product_id', $product->id)->where('operation', 'out')->sum('incoming');

                            $this->productListing[$date] = [
                                'date'          => $date,
                                'product'       => $product,
                                'incoming'      => $incoming,
                                'closingStock'  => $closingStock,
                                'openingStock'  => $incoming->current_stock,

                                'in_stock'      => $instock,
                                'out_stock'     => $outstock,

                                'closing_stock' => $incoming->current_stock + $instock - $outstock,
                            ];
                        }
                    }
                }
            } catch (\Throwable $th) {
                dd($th);
            }

            if (count($this->productListing) <= 0) {
                $this->searchFoundSomething = 'no';
                $this->result   =   false;
            } else {
                $this->searchFoundSomething = 'yes';
                $this->result   =   true;
            }
            // $this->result   =   true;
            // dd($this->productListing);
        }
    }

    // function searchInformation()
    // {
    //     $this->resetData();
    //     $this->validate();

    //     if ($this->start_date > $this->end_date) {
    //         dd('operation not allowed!');
    //     } else {
    //         $datecount  =   0;
    //         $carbonDate =   Carbon::create($this->start_date);
    //         $dateDiff   =   $carbonDate->diffInDays($this->end_date);
    //         $this->daysCount    =   $dateDiff;
    //         // dd($dateDiff);
    //         $dates = [];
    //         for ($sDate = 1; $sDate <= $dateDiff; $sDate++) {
    //             $carbonDate =   Carbon::create($this->start_date);
    //             array_push($this->dateList, $carbonDate->addDay($sDate)->format('Y-m-d'));
    //         }

    //         $this->products =   Product::where('company_id', userInfo()->company_id)->where('category_id', $this->category)->select('id', 'product_name', 'cost', 'location')->with('power:id,product_id,sphere,cylinder,axis,add,eye')->get();

    //         $productTracker =   TrackStockRecord::where('company_id', userInfo()->company_id)->get();
    //         foreach ($productTracker as $key => $id) {
    //             array_push($this->productArrayList, $id->id);
    //         }

    //         (int)$count_p = $dateDiff;
    //         foreach ($this->products as $key => $product) {

    //             $incomingSum = 0;

    //             if ($count_p == 0) {
    //                 $count_p = $dateDiff;
    //             }

    //             foreach ($this->dateList as $count => $date) {

    //                 $incoming       =   TrackStockRecord::where('product_id', $product->id)->whereDate('created_at', $date)->where('type', 'rm')->sum('incoming');
    //                 $closingStock   =   TrackStockRecord::where('product_id', $product->id)->where('type', 'rm')->pluck('change')->first();

    //                 // finding the opening stock
    //                 $incomingSum += (int)$incoming;

    //                 // closing stock calculations
    //                 $closingStock = date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime($this->end_date))
    //                     ? ($closingStock == null
    //                         ? 0 : $closingStock) : 0;

    //                 if (!array_search($product->id, $this->productArrayList, true)) {
    //                     $closingStock = date('Y-m-d', strtotime($date)) == date('Y-m-d', strtotime($this->end_date))
    //                         ? ($product->stock == null
    //                             ? 0 : $product->stock) : 0;
    //                 }

    //                 $this->productListing[$date . '-' . $product->id] = [
    //                     'product' => $product,
    //                     'current_stock' => TrackStockRecord::where('product_id', $product->id)->whereDate('created_at', $date)->where('type', 'rm')->first(),
    //                     'incoming' => number_format($incoming),
    //                     'hide' => $this->daysCount == $count_p ? false : true,
    //                     'closingStock' => $closingStock,
    //                     'openingStock' => $incomingSum,
    //                 ];
    //                 $count_p--;

    //                 dd($this->productListing);
    //             }

    //             $this->productListingVariation[$product->id] = $incomingSum;
    //         }

    //         dd($this->productListing);

    //         if (count($this->productListing) <= 0) {
    //             $this->searchFoundSomething = 'no';
    //         } else {
    //             $this->searchFoundSomething = 'yes';
    //         }
    //     }
    //     // dd($this->productListingVariation);

    //     $this->result   =   true;
    // }

    function resetData()
    {
        $this->productListing = [];
        $this->dateList = [];
    }

    public function render()
    {
        $this->categories   =   Category::get();
        return view('livewire.manager.report.product-report')->layout('livewire.livewire-slot');
    }
}
