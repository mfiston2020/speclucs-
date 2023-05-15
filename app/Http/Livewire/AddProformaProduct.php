<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddProformaProduct extends Component
{
    public $category;

    public $categories;
    public $lens_types;
    public $chromatics;
    public $coatings;
    public $index;
    public $lensTpype;
    public $tpype;
    public $proforma;

    public $lensindex;
    public $lenschromatics;
    public $lenscoating;
    public $lensphere;
    public $lencylinder;
    public $lensaxis;
    public $lensadd;
    public $lenseye;

    public $product;
    public $non_lens_product;
    public $product_found;
    public $insurance;
    public $nonLensProduct;
    public $productNonLens;

    public function updatedlensTpype($type)
    {
        $this->tpype    =   \App\Models\LensType::where('id',$type)->pluck('name')->first();
    }

    public function searchLens()
    {
        if (initials($this->tpype=='SINGLE VISION'))
        {
            $ltype  =   \App\Models\LensType::where('name',$this->tpype)->pluck('id')->first();

            $product_id     =   \App\Models\Power::where('type_id',$ltype)
                                                    ->where('index_id',$this->lensindex)
                                                    ->where('chromatics_id',$this->lenschromatics)
                                                    ->where('coating_id',$this->lenscoating)
                                                    ->where('sphere',format_values($this->lensphere))
                                                    ->where('cylinder',format_values($this->lencylinder))
                                                    ->where('eye','any')
                                                    ->where('company_id',Auth::user()->company_id)
                                                    ->select('product_id')->first();

            $this->product    =   \App\Models\Product::find($product_id);
        }
        else
        {
            $product_id     =   \App\Models\Power::where('type_id',$this->tpype)
                                                    ->where('index_id',$this->lensindex)
                                                    ->where('chromatics_id',$this->lenschromatics)
                                                    ->where('coating_id',$this->lenscoating)
                                                    ->where('sphere',format_values($this->lensphere))
                                                    ->where('cylinder',format_values($this->lencylinder))
                                                    ->where('axis',format_values($this->lensaxis))
                                                    ->where('add',format_values($this->lensadd))
                                                    ->where('eye',$this->lenseye)
                                                    ->where('company_id',Auth::user()->company_id)
                                                    ->select('product_id')->first();

            $this->product    =   \App\Models\Product::find($product_id);
        }

        if ($this->product!=null) {
            $this->product_found    =   1;
        }

        // dd($this->product);
    }

    public function updatednonLensProduct($values)
    {
        $this->productNonLens   =   \App\Models\Product::find($values);
    }

    public function mount($proforma_id)
    {
        $this->proforma =   $proforma_id;

        $insurance_id   =   \App\Models\Proforma::find($proforma_id);
        $this->insurance    =   \App\Models\Insurance::where('id',$insurance_id->insurance_id)->pluck('insurance_name')->first();
        // $this->insurance    =   $insurance_id;
        
        $this->non_lens_product      =   \App\Models\Product::where('company_id',Auth::user()->company_id)->where('category_id','<>','1')->get();

        $this->categories =   \App\Models\Category::all();
        $this->lens_types =   \App\Models\LensType::all();
        $this->chromatics =   \App\Models\PhotoChromatics::all();
        $this->coatings   =   \App\Models\PhotoCoating::all();
        $this->index      =   \App\Models\PhotoIndex::all();
    }

    public function render()
    {
        return view('livewire.add-proforma-product');
    }
}
