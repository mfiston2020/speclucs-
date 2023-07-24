<?php

namespace App\Http\Livewire\Manager\Sales;

use App\Models\Insurance;
use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Product;
use Livewire\Component;

class ProductRetail extends Component
{
    // client information variables
    public $firstname;
    public $lastname;
    public $tin_number;
    public $phone;
    public $date_of_birth;
    public $gender, $insurance_number;

    // control variables
    public $eye;
    public $invoice_id;
    public $productID;
    public $myCustomers;
    public $productType;
    public $customerType;
    public $rightEye =   false;
    public $leftEye =   false;

    // variable to hold values
    public $nonlensProducts;
    // public $nonlensProductDetail;
    // public $existingCustomer    =   array();
    // public $singleExistingCustomer;

    // =================================
    public $r_sphere;
    public $r_cylinder;
    public $r_axis;
    public $r_addition;

    public $l_sphere;
    public $l_cylinder;
    public $l_axis;
    public $l_addition;

    public $sphere;
    public $cylinder;
    public $axis;
    public $addition;
    // ==================================

    public $lensProduct;
    public $productSelected;
    public $nonlensProductSelected;

    // lens characteristics
    public $type;
    public $index;
    public $coating;
    public $chromatic;

    public $lensType;
    public $lensIndex;
    public $lensCoating;
    public $lensChromaticAspect;

    // variables to handle calculations
    public $product_stock;
    public $product_price;
    public $proquantity;
    public $prodiscount =   0;
    public $product_unit_price;
    public $product_total_amount;

    // get all prducts related to this invoice variable
    public $invoiceProduct;
    public $notAvailableStockProducts;

    // insurance
    public $allInsurances;
    public $insurance_type;
    public $insurance_percentage    =   0;

    public $patient_payment =   0;
    public $approved_amount =   0;
    public $insurance_payment   =   0;

    // control variables
    public $showProductDetails = false;

    // final payment variable
    public $hide_insurance_details;


    // mount
    function mount()
    {
        $this->lensType             =   LensType::all();
        $this->lensIndex            =   PhotoIndex::all();
        $this->lensCoating          =   PhotoCoating::all();
        $this->lensChromaticAspect  =   PhotoChromatics::all();
        $this->allInsurances        =   Insurance::where('company_id', userInfo()->company_id)->get();

        // non lens products
        $this->nonlensProducts  =   Product::where('company_id', userInfo()->company_id)->whereNotIn('category_id', ['1'])->get();
        // $this->getAllInvoiceProduct();
    }

    public function render()
    {
        return view('livewire.manager.sales.product-retail')->layout('livewire.livewire-slot');
    }
}
