<?php

namespace App\Http\Livewire\Website;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;


#[Layout('website.layouts.app')]

class Homepage extends Component
{
    use WithPagination;

    public $categories,$products,$wishlist=[],$cart=[];

    function wishlistUpdate($value){
        array_push($this->wishlist,$value);
        // dd($this->wishlist);
    }
    
    function updateValue(){
        $this->wishlist;
    }

    function mount(){
        $this->categories =   Category::withCount('products')->get();
        $this->products   =   Product::whereNotNull('picture')->orderby('created_at','desc')->get();
    }

    public function render(){
        return view('livewire.website.homepage')->layoutData([
            'cart' => $this->cart,
            'wishlist' => $this->wishlist,
            'categories' => $this->categories,
        ]);
    }
}
