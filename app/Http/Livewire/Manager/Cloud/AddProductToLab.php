<?php

namespace App\Http\Livewire\Manager\Cloud;

use App\Models\CloudProductTransaction;
use App\Repositories\SalesRepo;
use Livewire\Component;

class AddProductToLab extends Component
{
    public $orders,$countOrders=[],$processed=0;

    function processOrders(){
        foreach ($this->orders as $key => $value) {
            $product['product']    =   $value->product->toArray();
            $product['transaction']=   $value->toArray();

            $productRepo  =   new SalesRepo();
            $productRepo->createInvoice($product);
            $this->processed    +=  1;

            $value->status='done';
            $value->save();
        }
    }

    function mount(){
        $this->orders   =   CloudProductTransaction::with('product')->where('company_id',auth()->user()->company_id)->where('status','pending')->get();
    }
    public function render()
    {
        $this->orders   =   CloudProductTransaction::with('product')->where('company_id',auth()->user()->company_id)->where('status','pending')->get();
        return view('livewire.manager.cloud.add-product-to-lab');
    }
}
