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

            $value->status='done';
            $value->save();
        }
        session()->flash('successMsg','All orders added to priced!');
        $this->redirect('/manager/request/request/priced');
    }

    function mount(){
        $this->orders   =   CloudProductTransaction::with('product')->where('company_id',auth()->user()->company_id)->where('status','pending')->get();
        foreach ($this->orders as $key => $value) {
            array_push($this->countOrders,$value->transaction_id);
        }
    }
    public function render()
    {
        $this->orders   =   CloudProductTransaction::with('product')->where('company_id',auth()->user()->company_id)->where('status','pending')->get();
        return view('livewire.manager.cloud.add-product-to-lab');
    }
}
