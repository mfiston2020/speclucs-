<?php

namespace App\Http\Livewire\Manager;

use App\Models\Customer;
use App\Models\Insurance;
use App\Models\Invoice;
use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Product;
use App\Models\SoldProduct;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddSaleProduct extends Component
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
    public $nonlensProductDetail;
    public $existingCustomer    =   array();
    public $singleExistingCustomer;

    public $sphere;
    public $cylinder;
    public $axis;
    public $addition;

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

    // updated
    function updatedcustomerType($value)
    {
        $this->existingCustomer =   Customer::where('company_id', userInfo()->company_id)->get();
    }
    function updatedmyCustomers($value)
    {
        $this->singleExistingCustomer   =   Customer::findOrFail($value);
    }
    function updatedproductSelected($value)
    {
        $this->searchPrice($value);
    }

    // =======================================
    function updatedprodiscount()
    {
        $this->calculate_total_amount();
    }
    function updatedproquantity()
    {
        $this->calculate_total_amount();
    } // ======================================

    // calculating the percentage
    function updatedinsurance_type($value)
    {
        if ($this->insurance_type == 'private') {
            $this->insurance_percentage =   100;
            $this->insurance_payment    =   0;
            $this->hide_insurance_details =  'yes';
        }
    }

    // when lens fields are being updated
    function calculate_total_amount()
    {

        $discounted     =   intval($this->product_unit_price) + intval($this->prodiscount);

        $this->product_total_amount =   intval($this->proquantity)   *   $discounted;
    }

    function add_pending_product()
    {

        // $this->validate(
        //     [
        //         'firstname'           => 'required',
        //         'lastname'    => 'required',
        //         'gender'           => 'required',
        //         'date_of_birth'        => 'required',
        //     ]
        // );

        if ($this->rightEye == true) {
            $this->eye  =   'right';
        }
        if ($this->leftEye == true) {
            $this->eye  =   'left';
        }

        $order  =   new \App\Models\PendingOrder();

        $order->company_id      =   Auth::user()->company_id;
        $order->user_id         =   Auth::user()->id;
        $order->type_id         =   $this->type;
        $order->coating_id      =   $this->coating;
        $order->index_id        =   $this->coating;
        $order->chromatic_id    =   $this->chromatic;
        $order->patient_number  =   $this->phone;
        $order->firstname       =   $this->firstname;
        $order->lastname        =   $this->lastname;
        $order->gender          =   $this->gender;
        $order->date_of_birth   =   $this->date_of_birth;
        $order->invoice_date    =   date('Y-m-d');

        $order->sphere_r    =   ($this->eye  ==   'left') ? null : $this->sphere;
        $order->cylinder_r  =   ($this->eye  ==   'left') ? null : $this->cylinder;
        $order->axis_r      =   ($this->eye  ==   'left') ? null : $this->axis;
        $order->addition_r  =   ($this->eye  ==   'left') ? null : $this->addition;

        $order->sphere_l    =   ($this->eye  ==   'right') ? null : $this->sphere;
        $order->cylinder_l  =   ($this->eye  ==   'right') ? null : $this->cylinder;
        $order->axis_l      =   ($this->eye  ==   'right') ? null : $this->axis;
        $order->addition_l  =   ($this->eye  ==   'right') ? null : $this->addition;
        $order->save();

        $product    =   Invoice::where('company_id', userInfo()->company_id)->where('status', 'pending')->orderBy('created_at', 'desc')->first();
        $product->delete();

        $notification   =   new \App\Models\SupplierNotify();
        $notification->company_id   =   Auth::user()->company_id;
        $notification->supplier_id  =   Auth::user()->company_id;
        // $notification->order_id     =   $order->id;
        $notification->notification  =   'New Request from ' . userInfo()->name;
        $notification->save();
        return redirect()->route('manager.sales')->with('successMsg', 'Product requested from stock!');
    }

    function approvedAmount()
    {
        if ($this->insurance_type == 'private') {
            $this->insurance_payment    =   0;
            $this->patient_payment      =   $this->product_total_amount -   $this->insurance_payment;
        } else {
            if ($this->approved_amount > $this->product_total_amount) {
                $this->insurance_payment    =   $this->product_total_amount;
            } else {
                $this->insurance_payment    =   ($this->approved_amount  *   $this->insurance_percentage) / 100;
            }
            $this->patient_payment      =   (($this->product_total_amount -   $this->insurance_payment) < 0) ? 0 : $this->product_total_amount -   $this->insurance_payment;
        }
    }

    // function to search for the product price
    function searchPrice($value)
    {
        if ($this->productType == 'lens') {
            $lensTypeFull   =   LensType::find($this->type);

            if ($lensTypeFull) {
                if (initials($lensTypeFull->name) == 'SV') {
                    if (
                        $this->type != null && $this->index != null
                        && $this->chromatic != null
                        && $this->coating != null
                        && $this->sphere != null
                        && $this->cylinder != null
                    ) {
                        $product_id     =   \App\Models\Power::where('type_id', $this->type)
                            ->where('index_id', $this->index)
                            ->where('chromatics_id', $this->chromatic)
                            ->where('coating_id', $this->coating)
                            ->where('sphere', format_values($this->sphere))
                            ->where('cylinder', format_values($this->cylinder))
                            ->where('eye', 'any')
                            ->where('company_id', userInfo()->company_id)
                            ->select('product_id')->first();

                        $this->lensProduct    =   \App\Models\Product::find($product_id);
                        if (!$this->lensProduct) {
                            $this->emitSelf('product-not-found');
                            $this->showProductDetails   =   false;
                        } else {
                            $this->productID            =   $this->lensProduct[0]->id;
                            $this->product_unit_price   =   $this->lensProduct[0]->price;
                            $this->product_stock        =   $this->lensProduct[0]->stock;
                            $this->showProductDetails   =   true;
                        }
                    } else {
                        $this->emitSelf('product-not-found');
                        $this->showProductDetails   =   false;
                        $this->dispatchBrowserEvent('openProductNotFoundModal');
                    }
                } else {
                    if ($this->rightEye == true) {
                        $this->eye  =   'right';
                    }
                    if ($this->leftEye == true) {
                        $this->eye  =   'left';
                    }

                    if (
                        $this->type && $this->index &&
                        $this->chromatic && $this->coating &&
                        $this->sphere && $this->cylinder &&
                        $this->axis && $this->add && $this->eye
                    ) {
                        $product_id     =   \App\Models\Power::where('type_id', $this->type)
                            ->where('index_id', $this->index)
                            ->where('chromatics_id', $this->chromatics)
                            ->where('coating_id', $this->coating)
                            ->where('sphere', format_values($this->sphere))
                            ->where('cylinder', format_values($this->cylinder))
                            ->where('axis', format_values($this->axis))
                            ->where('add', format_values($this->add))
                            ->where('eye', $this->eye)
                            ->where('company_id', userInfo()->company_id)
                            ->select('product_id')->first();

                        $this->lensProduct    =   \App\Models\Product::find($product_id);
                        if (!$this->lensProduct) {
                            $this->dispatchBrowserEvent('openProductNotFoundModal');
                            $this->emitSelf('product-not-found');
                            $this->showProductDetails   =   false;
                        } else {
                            $this->productID            =   $this->lensProduct[0]->id;
                            $this->product_unit_price   =   $this->lensProduct[0]->price;
                            $this->product_stock        =   $this->lensProduct[0]->stock;
                            $this->showProductDetails   =   true;
                        }
                    } else {
                        $this->dispatchBrowserEvent('openProductNotFoundModal');
                        $this->emitSelf('product-not-found');
                        $this->showProductDetails   =   false;
                    }
                }
            } else {
                $this->dispatchBrowserEvent('openProductNotFoundModal');
                $this->emitSelf('product-not-found');
                $this->showProductDetails   =   false;
            }
        } else {
            $this->nonlensProductDetail      =   \App\Models\Product::find($value);

            if ($this->nonlensProductDetail != null) {
                $this->productID            =   $this->nonlensProductDetail->id;
                $this->product_unit_price   =   $this->nonlensProductDetail->price;
                $this->product_stock        =   $this->nonlensProductDetail->stock;
                $this->showProductDetails   =   true;
            } else {
                $this->productID            =   null;
                $this->product_unit_price   =   null;
                $this->product_stock        =   null;
                $this->showProductDetails   =   false;
            }
        }
    }

    // saving the product
    function saveSoldProduct()
    {

        $validatedData  = $this->validate(
            [
                'invoice_id'    => 'required',
                'productID'     => 'required',
                'proquantity'           => 'required',
                'product_unit_price'    => 'required',
                'prodiscount'           => 'required',
                'insurance_type'        => 'required',
                'product_total_amount'  => 'required',
                'insurance_percentage'  => 'required',
            ]
        );


        if ($this->singleExistingCustomer) {

            Invoice::find($validatedData['invoice_id'])->update([
                'client_id' => null,
                'client_name' => null,
                'phone' => null,
            ]);

            Invoice::find($validatedData['invoice_id'])->update([
                'client_id' => $this->singleExistingCustomer->id
            ]);
        }
        if ($this->customerType == 'new') {
            Invoice::find($validatedData['invoice_id'])->update([
                'client_id' => null,
                'client_name' => $this->firstname . ' ' . $this->lastname,
                'phone' => $this->phone,
                'tin_number' => $this->tin_number,
                'gender' => $this->gender,
                'dateOfBirth' => $this->date_of_birth,
            ]);
        }


        $_product   =   \App\Models\SoldProduct::where('company_id', Auth::user()->company_id)->get();
        $existing_product   =  0;

        foreach ($_product as $key => $item) {
            if ($validatedData['productID'] == $item->product_id && $validatedData['invoice_id'] == $item->invoice_id) {
                $existing_product   =   1;
                $id                 =   $item->id;
            }
        }

        if ($existing_product == 0) {

            $sold   =   new \App\Models\SoldProduct();

            $sold->company_id   =   Auth::user()->company_id;
            $sold->product_id   =   $validatedData['productID'];
            $sold->invoice_id   =   $validatedData['invoice_id'];
            $sold->quantity     =   $validatedData['proquantity'];
            $sold->discount     =   $validatedData['prodiscount'];
            $sold->unit_price   =   $validatedData['product_unit_price'];
            $sold->total_amount =   $validatedData['product_total_amount'];
            $sold->is_private   =   $this->insurance_type == 'private' ? 'yes' : null;
            $sold->insurance_id =   $this->insurance_type == 'private' ? null : $this->insurance_type;
            $sold->percentage   =   $this->insurance_type == 'private' ? 0 : $this->insurance_percentage;

            $sold->patient_payment  =   $this->patient_payment;
            $sold->approved_amount  =   $this->approved_amount;
            $sold->insurance_payment =   $this->insurance_payment;

            try {
                $sold->save();
                $this->resetInput();
                $this->emitSelf('product-added');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        } else {
            $product = \App\Models\SoldProduct::find($id);

            $product->quantity        =   $validatedData['proquantity'] + $product->quantity;

            try {
                $product->save();

                $this->resetInput();
                $this->emitSelf('product-added');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        }
    }


    //function get all prducts related to this invoice
    function getAllInvoiceProduct()
    {
        $this->invoiceProduct   =   SoldProduct::where('invoice_id', $this->invoice_id)->get();
    }

    // removing one of the saved product
    function removeProduct($id)
    {
        SoldProduct::find($id)->delete();
        $this->getAllInvoiceProduct();
    }

    // rest fields
    function resetInput()
    {
        $this->proquantity          =   null;
        $this->product_unit_price   =   null;
        $this->prodiscount          =   null;
        $this->product_total_amount =   null;
        $this->insurance_percentage =   0;
        $this->showProductDetails   =   false;
        $this->insurance_payment    =   0;
        $this->patient_payment      =   0;

        $this->getAllInvoiceProduct();
    }

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
        $this->getAllInvoiceProduct();
    }

    public function render()
    {
        return view('livewire.manager.add-sale-product');
    }
}
