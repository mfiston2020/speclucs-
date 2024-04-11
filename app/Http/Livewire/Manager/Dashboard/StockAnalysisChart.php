<?php

namespace App\Http\Livewire\Manager\Dashboard;

use App\Models\Category;
use App\Models\LensType;
use App\Models\Product;
use App\Repositories\ProductRepo;
use Livewire\Component;

class StockAnalysisChart extends Component
{
    public $timeframe,$supply,$order_date,$delivery_date,$products=[];
    public $lensData=[],$frameData=[],$accessoryData=[];

    public $lensC=0,$lensH=0,$lensM=0,$lensL=0,$lensO=0,$lensD;
    public $frameC=0,$frameH=0,$frameM=0,$frameL=0,$frameO=0,$frameD=0;
    public $accessoriesC=0,$accessoriesH=0,$accessoriesM=0,$accessoriesL=0,$accessoriesO=0,$accessoriesD=0;

    public $productRepo;

    function updatedtimeframe(){
        $this->lensC=0;
        $this->lensH=0;
        $this->lensM=0;
        $this->lensL=0;
        $this->lensO=0;
        $this->lensD=0;
        $this->frameC=0;
        $this->frameH=0;
        $this->frameM=0;
        $this->frameL=0;
        $this->frameO=0;
        $this->frameD=0;
        $this->accessoriesC=0;
        $this->accessoriesH=0;
        $this->accessoriesM=0;
        $this->accessoriesL=0;
        $this->accessoriesO=0;
        $this->accessoriesD=0;
        $this->getProductData();
    }

    function getProductData(){
        $productRepo  =   new ProductRepo();
        foreach ($this->products as $key => $product) {

            $fromPo =   $productRepo->productStockEfficiency($product->id);

            // lens
            if ($fromPo['category']=='1') {
                $this->lensData=[
                    'lens_critical' =>  $fromPo['status']=='critical'?$this->lensC++:$this->lensC,
                    'lens_high'     =>  $fromPo['status']=='high'?$this->lensH++:$this->lensH,
                    'lens_medium'   =>  $fromPo['status']=='medium'?$this->lensM++:$this->lensM,
                    'lens_low'      =>  $fromPo['status']=='low'?$this->lensL++:$this->lensL,
                    'lens_over'     =>  $fromPo['status']=='over'?$this->lensO++:$this->lensO,
                    'lens_discontinued'=>  $fromPo['status']=='Discontinued'?$this->lensD++:$this->lensD,
                ];

                if ($fromPo['status']=='critical') {
                   dd($fromPo);
                }
            }

            // frame
            if ($fromPo['category']=='2') {
                $this->frameData=[
                    'frame_critical' =>  $fromPo['status']=='critical'?$this->frameC++:$this->frameC,
                    'frame_high'     =>  $fromPo['status']=='high'?$this->frameH++:$this->frameH,
                    'frame_medium'   =>  $fromPo['status']=='medium'?$this->frameM++:$this->frameM,
                    'frame_low'      =>  $fromPo['status']=='low'?$this->frameL++:$this->frameL,
                    'frame_over'     =>  $fromPo['status']=='over'?$this->frameO++:$this->frameO,
                    'frame_discontinued'     =>  $fromPo['status']=='Discontinued'?$this->frameD++:$this->frameD,
                ];
            }else{
                $this->accessoryData=[
                    'accessories_critical' =>  $fromPo['status']=='critical'?$this->accessoriesC++:$this->accessoriesC,
                    'accessories_high'     =>  $fromPo['status']=='high'?$this->accessoriesH++:$this->accessoriesH,
                    'accessories_medium'   =>  $fromPo['status']=='medium'?$this->accessoriesM++:$this->accessoriesM,
                    'accessories_low'      =>  $fromPo['status']=='low'?$this->accessoriesL++:$this->accessoriesL,
                    'accessories_over'     =>  $fromPo['status']=='over'?$this->accessoriesO++:$this->accessoriesO,
                    'accessories_discontinued'     =>  $fromPo['status']=='Discontinued'?$this->accessoriesD++:$this->accessoriesD,
                ];
            }
        }

        // dd($this->lensData);
        $this->dispatchBrowserEvent('refreshChart');
    }

    function mount(){
        $productRepo  =   new ProductRepo();
        $this->products =   Product::where('company_id',auth()->user()->company_id)->with('soldProducts')->get();

        $this->lensData=[
            'lens_critical' =>  0,
            'lens_high'     =>  0,
            'lens_medium'   =>  0,
            'lens_low'      =>  0,
            'lens_over'     =>  0,
            'lens_discontinued'     =>  0,
        ];

        $this->frameData=[
            'frame_critical' =>  0,
            'frame_high'     =>  0,
            'frame_medium'   =>  0,
            'frame_low'      =>  0,
            'frame_over'     =>  0,
            'frame_discontinued'     =>  0,
        ];
        $this->accessoryData=[
            'accessories_critical' =>  0,
            'accessories_high'     =>  0,
            'accessories_medium'   =>  0,
            'accessories_low'      =>  0,
            'accessories_over'     =>  0,
            'accessories_discontinued'     =>  0,
        ];
    }

    public function render()
    {
        return view('livewire.manager.dashboard.stock-analysis-chart');
    }
}
