<?php

namespace App\Http\Livewire\Manager;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepo;
use Carbon\Carbon;
use Livewire\Component;

class PurchaseOrder extends Component
{
    public $categories,$category,$order_date,$delivery_date,$period_days=0,$period_months=3;

    public $showTable=false,$products=[],$totalDays,$productRepo,$leadTime,$totalCost=0,$omitZero=false;

    protected $rules=[
        'category'      =>  'required',
        'order_date'    =>  'required',
        'delivery_date' =>  'required',
        'period_days'   =>  'required',
        'period_months' =>  'required',
    ];

    function searchInformation(){
        $this->totalCost    =   0;
        $this->leadTime     =   0;
        $validated  =   $this->validate();

        $today  =   now();

        $date1      = strtotime($today);
        $your_date  = strtotime($this->delivery_date);
        $datediff   = $your_date-$date1;
        $this->leadTime =   floor($datediff / (60 * 60 * 24));


        // $date = Carbon::parse($this->delivery_date);
        // $this->leadTime =   $date->diffInDays($today);
        // dd(floor($datediff / (60 * 60 * 24)));
        // dd($this->leadTime);

        $this->totalDays    =   $validated['period_days']+($validated['period_months'] * 30);

        $this->products     =   Product::where('company_id',auth()->user()->company_id)
                                        ->with(['soldproducts'=>function ($query) use ($today){
                                            $query->whereBetween('created_at',[date('Y-m-d',strtotime($today . '-'.$this->totalDays.'day')),date('Y-m-d',strtotime($today))]);
                                        },'category:id,name','power:id,product_id,sphere,cylinder,axis,add,eye'])
                                        ->select('id','product_name','description','stock','cost','category_id')
                                        ->where('category_id',$this->category)
                                        ->get();

        // $this->dispatch('total-cost');

        // dd($this->products[0]);
        $this->showTable=true;
        $this->dispatch('total-cost');
    }

    function changeOmition(){
        $this->omitZero =   !$this->omitZero;
    }

    function goBack(){
        $this->showTable    =   false;
    }

    function mount(){
        $this->categories   =   Category::get();
    }
    public function render()
    {
        return view('livewire.manager.purchase-order')->layout('livewire.livewire-slot');
    }
}
