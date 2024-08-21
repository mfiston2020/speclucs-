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
    public $dateList = [], $productCounting = [];
    public $productArrayList = array(), $countProduct   =   1;
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
        $this->result   =   false;
        if ($this->category == '1') {
            $this->types    =   LensType::get();
            $this->showType =   true;
        } else {
            $this->showType = false;
        }
    }

    function updatedlens_type()
    {
        $this->hideResult();
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
            // Create Carbon date instance once
            $carbonDate = Carbon::create($this->start_date);

            // Calculate date difference and populate dates array
            $dateDiff = $carbonDate->diffInDays($this->end_date);
            $this->daysCount = $dateDiff;
            $this->dateList = [];

            for ($sDate = 1; $sDate <= $dateDiff; $sDate++) {
                // Clone the original carbon date to avoid changing the original instance
                $this->dateList[] = $carbonDate->copy()->addDay($sDate)->format('Y-m-d');
            }
            $productTracker =   null;

            if ($this->category == Category::where('name', 'Lens')->pluck('id')->first()) {

                // fetch lens products based on their type
                $productTracker =   TrackStockRecord::where('company_id', userInfo()->company_id)->whereHas('product', function ($query) {
                    $query->whereHas('power', function ($query) {
                        $query->where('type_id', $this->lens_type)
                            ->select('id', 'product_id', 'sphere', 'cylinder', 'axis', 'add', 'eye');
                    })
                        ->where('category_id', $this->category)
                        ->select('id', 'category_id', 'product_name', 'description', 'cost');
                })
                    ->wherebetween('created_at', [$this->start_date, $this->end_date])
                    ->where('type', 'rm')
                    ->orderBy('product_id', 'asc')
                    ->select('id', 'product_id', 'current_stock', 'incoming', 'status', 'type', 'created_at', 'reason', 'change')
                    ->get();

                // dd($productTracker);
            } else {
                $productTracker =   TrackStockRecord::where('company_id', userInfo()->company_id)->whereHas('product', function ($query) {
                    $query->where('category_id', $this->category)->select('id', 'product_name', 'description', 'cost');
                })->wherebetween('created_at', [$this->start_date, $this->end_date])->where('type', 'rm')->orderBy('product_id', 'asc')->select('id', 'product_id', 'current_stock', 'incoming', 'status', 'type', 'created_at', 'reason', 'change')->get();
            }


            foreach ($productTracker as $key => $prodFound) {
                $instock    =   0;
                $outStock   =   0;
                $countProduct   =   1;

                $prodFound->status == 'in' ? $instock     =   $prodFound->incoming : $instock   =   0;
                $prodFound->status == 'out' ? $outStock   =   $prodFound->incoming : $outStock  =   0;

                $this->productListing[$key] = [
                    '_closingStock' => $prodFound->change,
                    'closingStock'  => $prodFound->change,
                    'product'       => $prodFound->product,
                    'product_id'    => $prodFound->product->id,
                    'inStock'       => number_format($instock),
                    'outStock'      => number_format($outStock),
                    'current_stock' => $prodFound->current_stock,
                    'date'          => date('Y-m-d', strtotime($prodFound->created_at)),
                    'reason'        => $prodFound->where('id', $prodFound->id)->pluck('reason')->first(),
                    'count'         => $countProduct,
                ];

                $this->productCounting[$prodFound->product->id] =   1;

                if ($key > 0) {
                    if ($this->productListing[$key]['product_id'] == $this->productListing[$key - 1]['product_id']) {
                        $this->productListing[$key]['count'] = $this->productListing[$key - 1]['count'] + 1;
                        $this->productCounting[$prodFound->product->id] = $this->productListing[$key]['count'];
                    } else {
                        $countProduct   = 1;
                    }
                }
            }

            // dd($this->productCounting);

            if (count($this->productListing) <= 0) {
                $this->searchFoundSomething = 'no';
                $this->result   =   false;
            } else {
                $this->result   =   true;
                $this->searchFoundSomething = 'yes';
            }
        }
    }

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
