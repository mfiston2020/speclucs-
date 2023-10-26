<?php

namespace App\Http\Livewire\Manager\Sales;

use App\Models\Insurance;
use App\Models\Invoice;
use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Product;
use App\Models\SoldProduct;
use App\Repositories\ProductRepo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductRetail extends Component
{
    // repository
    public $informationMessage;

    // variables for cloud
    public $cloud_id, $hospital_name;

    // client information variables
    public $firstname, $lastname, $tin_number, $phone;
    public $date_of_birth, $gender, $insurance_number, $affiliate;

    // control variables
    public $lensType, $lensIndex, $lensCoating, $lensChromaticAspect, $frameList, $accessoriesList, $insuranceList;

    public $rightEye = false, $leftEye = false, $showPaymentSection = false, $leftLen = false, $rightLen = false, $leftLenFound = true, $rightLenFound = false, $searchProduct = false, $showsubmit = false, $isCloudOrder = 'no';

    // ============== lens selection variables ========
    public $lens_type, $lens_index, $lens_coating, $lens_chromatic;

    // ============== lens variable managemnt =========
    public $r_sphere, $r_cylinder, $r_axis, $r_addition, $r_segment_height, $r_mono_pd;

    public $l_sphere, $l_cylinder, $l_axis, $l_addition, $l_segment_height, $l_mono_pd;

    public $leftLenInfo, $leftLenQty, $leftLenID, $rightLenInfo, $rightLenQty, $rightLenID;

    // =========== frame variable management==========
    public $frame, $frame_stock, $frame_unit_price, $frame_quantity, $frame_price_adjust = 0, $frame_total_amount, $frame_location, $frameInfo;

    // =========== accessories variables =============
    public $accessory, $accessory_stock, $accessory_unit_price, $accessory_quantity, $accessory_price_adjust = 0, $accessory_total_amount, $accessory_location, $accessoryInfo;

    //  lens calculation variables ==============
    public $total_lens_amount, $total_lens_stock;


    // lens variables for insurance calculations ======
    public $insurance_percentage_lens, $insurance_payment_lens, $insurance_approved_lens, $patient_payment_lens, $insurance_type;

    // frame variables for insurance calculations ======
    public $insurance_percentage_frame, $insurance_payment_frame, $insurance_approved_frame, $patient_payment_frame;

    public $invoiceStatus   =   'requested';

    protected $rules = [
        'cloud_id' => 'required_if:isCloudOrder,==,yes'
    ];

    // showing cloud form and hidding it
    function hideCloud($value)
    {
        $this->isCloudOrder =   $value;
    }

    // searching for frame
    function updatedframe($val)
    {
        $data   =   [
            'productType' => 'frame',
            'product_id' => $val
        ];

        $repo   =   new ProductRepo();

        $frameResult = $repo->searchProduct($data);
        $this->frameInfo    =   $frameResult;

        if ($frameResult) {
            $this->frame_quantity   =   1;
            $this->frame_stock      =   $frameResult->stock;
            $this->frame_unit_price =   $frameResult->price;
            $this->frame_location   =   $frameResult->location == null ? '-' : $frameResult->location;
        } else {
            $this->frame_quantity   =   null;
            $this->frame_stock      =   null;
            $this->frame_unit_price =   null;
            $this->frame_location   =   null;
            $this->frame_total_amount   =   0;
        }
    }

    // searching accessories
    function updatedaccessory($value)
    {
        $data   =   [
            'productType' => 'frame',
            'product_id' => $value
        ];

        $repo   =   new ProductRepo();

        $accResult = $repo->searchProduct($data);
        $this->accessoryInfo    =   $accResult;

        if ($accResult) {
            $this->accessory_quantity =  1;
            $this->accessory_stock      =   $accResult->stock;
            $this->accessory_unit_price =   $accResult->price;
            $this->accessory_location   =   $accResult->location == null ? '-' : $accResult->location;
        } else {

            $this->accessory_quantity =  null;
            $this->accessory_stock      =   null;
            $this->accessory_unit_price =   null;
            $this->accessory_location   =   null;
            $this->accessory_total_amount = 0;
        }
    }

    function showModal($msg)
    {
        $this->informationMessage   =   $msg;
        $this->dispatchBrowserEvent('showwarningModal');
    }

    // checking product availability
    function checkAvailability()
    {
        if ($this->lens_type && $this->lens_coating && $this->lens_chromatic && $this->lens_index) {
            $this->searchProduct    =   true;
        } elseif ($this->lens_type || $this->lens_coating || $this->lens_chromatic || $this->lens_index) {
            $this->searchProduct    =   false;
            $this->showModal('Please fill out all the required lens information');
            return;
        }
        if ($this->frame == null && $this->searchProduct == false && $this->accessory == null) {
            $this->showModal('select at least one product');
            return;
        }


        if ($this->searchProduct == true) {
            $right_data  =   [
                'productType'   => 'lens',
                // lens description
                'type'      =>  $this->lens_type,
                'index'     =>  $this->lens_index,
                'coating'   =>  $this->lens_coating,
                'chromatic' =>  $this->lens_chromatic,

                // right side
                'sphere'      =>  $this->r_sphere,
                'cylinder'    =>  $this->r_cylinder,
                'axis'        =>  $this->r_axis,
                'addition'    =>  $this->r_addition,
                'eye'    =>  'right',
            ];

            $left_data  =   [
                'productType'   => 'lens',
                // lens description
                'type'      =>  $this->lens_type,
                'index'     =>  $this->lens_index,
                'coating'   =>  $this->lens_coating,
                'chromatic' =>  $this->lens_chromatic,

                // right side
                'sphere'      =>  $this->l_sphere,
                'cylinder'    =>  $this->l_cylinder,
                'axis'        =>  $this->l_axis,
                'addition'    =>  $this->l_addition,
                'eye'    =>  'left',
            ];

            $repo   =   new ProductRepo();

            $right_len_Results = $repo->searchProduct($right_data);
            $left_len_Results = $repo->searchProduct($left_data);

            $this->rightLenInfo =   $right_len_Results;
            $this->leftLenInfo =   $left_len_Results;

            // dd($left_len_Results[0]->id);

            $this->rightLenFound    =   $right_len_Results == 'product-not-found' ? false : true;
            $this->rightLen         =   $right_len_Results == 'product-not-found' ? false : true;
            $this->rightLenQty      =   $right_len_Results == 'product-not-found' ? '-' : $right_len_Results[0]->stock;
            $this->rightLenID       =   $right_len_Results == 'product-not-found' ? 'r-' : $right_len_Results[0]->id;

            $this->leftLenFound     =   $left_len_Results == 'product-not-found' ? false : true;
            $this->leftLen          =   $left_len_Results == 'product-not-found' ? false : true;
            $this->leftLenQty       =   $left_len_Results == 'product-not-found' ? '-' : $left_len_Results[0]->stock;
            $this->leftLenID        =   $left_len_Results == 'product-not-found' ? 'l-' : $left_len_Results[0]->id;



            // if both products are found
            if ($left_len_Results != 'product-not-found' && $right_len_Results != 'product-not-found') {
                // if ($left_len_Results[0]->id == $right_len_Results[0]->id) {

                //     $this->total_lens_amount =    $right_len_Results[0]->price;
                // } else {

                $leftPrice      =   $left_len_Results   == 'product-not-found' ? 0 : $left_len_Results[0]->price;
                $rightPrice     =   $right_len_Results  == 'product-not-found' ? 0 : $right_len_Results[0]->price;

                $this->total_lens_amount =    $leftPrice + $rightPrice;
                // }
            }
            // if left len is the only product
            else if ($left_len_Results != 'product-not-found' && $right_len_Results == 'product-not-found') {
                $this->total_lens_amount =    $left_len_Results[0]->price;
            }
            // if left len is the only product
            else if ($left_len_Results == 'product-not-found' && $right_len_Results != 'product-not-found') {
                $this->total_lens_amount =    $right_len_Results[0]->price;
            }
            // if right len is the only product
            else {
                $this->total_lens_amount =    0;
            }

            // $this->accessory_total_amount   =   $this->accessory_quantity * ($this->accessory_unit_price - ($this->accessory_price_adjust == null ? 0 : $this->accessory_price_adjust));
        }
        if ($this->frame) {
            $this->frame_total_amount   =   ($this->frame_unit_price + $this->frame_price_adjust) * $this->frame_quantity;
        }
        if ($this->accessory) {
            $this->accessory_total_amount   =   ($this->accessory_unit_price + $this->accessory_price_adjust) * $this->accessory_quantity;
        }

        $this->showPaymentSection   =   true;
    }

    // insurance percentages calculations
    function calculateInsurance()
    {
        $this->checkAvailability();
        if ($this->insurance_type == 'private') {
            $this->insurance_payment_lens    =   0;
            $this->insurance_payment_frame   =   0;
            $this->patient_payment_frame     =   $this->frame_total_amount;
            $this->patient_payment_lens      =   $this->total_lens_amount;
        } elseif ($this->insurance_type == null) {
            $this->showModal('Select Insurance type first');
            return;
        } else {

            // if ($this->insurance_approved_frame >= $this->frame_total_amount) {
            //     $this->insurance_payment_frame    =   $this->frame_total_amount;
            // }

            // if ($this->insurance_approved_frame < $this->frame_total_amount) {
            $this->insurance_payment_frame    =   ($this->insurance_approved_frame  *   $this->insurance_percentage_frame) / 100;
            // }

            // if ($this->insurance_approved_lens >= $this->total_lens_amount) {
            //     $this->insurance_payment_lens    =   $this->total_lens_amount;
            // }

            // if ($this->in/surance_approved_lens < $this->total_lens_amount) {
            $this->insurance_payment_lens    =   ($this->insurance_approved_lens  *   $this->insurance_percentage_lens) / 100;
            // }

            if ($this->insurance_payment_lens > $this->total_lens_amount) {
                $this->insurance_payment_lens    =   $this->total_lens_amount;
                $this->patient_payment_frame = 0;
            } else {

                $this->patient_payment_lens      =  round((($this->total_lens_amount -   $this->insurance_payment_lens) < 0) ? 0 : $this->total_lens_amount -   $this->insurance_payment_lens, 2);
            }

            if ($this->insurance_payment_frame >= $this->frame_total_amount) {
                $this->insurance_payment_frame    =   $this->frame_total_amount;
            } else {

                $this->patient_payment_frame      =   round((($this->frame_total_amount -   $this->insurance_payment_frame) < 0) ? 0 : $this->frame_total_amount -   $this->insurance_payment_frame, 2);
            }
        }
        $this->showsubmit = true;
    }

    // function to handle form submit
    function saveOrder()
    {
        $this->validate();


        $this->checkAvailability();


        if ($this->lens_type && $this->lens_coating && $this->lens_chromatic && $this->lens_index) {
            $this->searchProduct    =   true;
        } elseif ($this->lens_type || $this->lens_coating || $this->lens_chromatic || $this->lens_index) {

            $this->searchProduct    =   false;
        }


        if ($this->frame == null && $this->searchProduct == false && $this->accessory == null) {
            $this->showModal('select at least one product');
            return;
        } else {
            $reference  =   count(DB::table('invoices')->select('reference_number')->where('company_id', userInfo()->company_id)->get());



            if ($this->leftLenFound || $this->rightLenFound) {
                if ($this->rightLenQty < 1 || $this->leftLenQty < 1) {
                    $this->invoiceStatus  =   'Confirmed';
                } else {
                    $this->invoiceStatus  =   'requested';
                }
            } else {
                    $this->invoiceStatus  =   'requested';
                }

            $invoice    =   new Invoice();

            $invoice->reference_number  =   $reference + 1;
            $invoice->status            =   'pending';
            $invoice->user_id           =   userInfo()->id;
            $invoice->total_amount      =   '0';
            $invoice->cloud_id          =   $this->cloud_id;
            $invoice->hospital_name     =   $this->hospital_name;
            $invoice->company_id        =   userInfo()->company_id;
            $invoice->client_id         =   null;
            $invoice->client_name       =   $this->firstname . ' ' . $this->lastname;
            $invoice->affiliate_names   =   $this->affiliate;
            $invoice->phone             =   $this->phone;
            $invoice->tin_number        =   $this->tin_number;
            $invoice->gender            =   $this->gender;
            $invoice->dateOfBirth       =   $this->date_of_birth;
            $invoice->insurance_id      =   $this->insurance_type == 'private' ? null : $this->insurance_type;
            $invoice->status            =   $this->invoiceStatus;
            $invoice->total_amount      =   0;
            $invoice->insurance_card_number =   $this->insurance_number;

            $invoice->save();

            if ($this->lens_type || $this->lens_coating || $this->lens_chromatic) {
                if ($this->leftLenFound && $this->rightLenFound) {
                    $this->save('lens', 'available', $invoice->id, 'left');
                    $this->save('lens', 'available', $invoice->id, 'right');
                }

                if (!$this->leftLenFound && $this->rightLenFound) {
                    $this->save('lens', 'not-available', $invoice->id, 'left');
                    $this->save('lens', 'available', $invoice->id, 'right');
                }

                if ($this->leftLenFound && !$this->rightLenFound) {
                    $this->save('lens', 'available', $invoice->id, 'left');
                    $this->save('lens', 'not-available', $invoice->id, 'right');
                }

                if (!$this->leftLenFound && !$this->rightLenFound) {
                    $this->save('lens', 'not-available', $invoice->id, 'left');
                    $this->save('lens', 'not-available', $invoice->id, 'right');
                }
            }

            if ($this->frame) {
                $this->save('frame', 'available', $invoice->id);
            }

            if ($this->accessory) {
                $this->save('accessory', 'available', $invoice->id);
            }
        }
    }

    // function to save product in the database
    function save($type, $availability, $invoiceId, $eye = '')
    {
        $total = 0;

        if ($availability == 'available' && $type == 'lens') {

            $sold   =   new SoldProduct();

            $sold->company_id   =   userInfo()->company_id;
            $sold->product_id   =   $eye == 'right' ? $this->rightLenInfo[0]->id : $this->leftLenInfo[0]->id;
            $sold->invoice_id   =   $invoiceId;
            $sold->quantity     =   '1';
            $sold->discount     =   '0';
            $sold->eye          =   $eye;
            $sold->unit_price   =   $eye == 'right' ? $this->rightLenInfo[0]->price : $this->leftLenInfo[0]->price;
            $sold->total_amount =   $eye == 'right' ? $this->rightLenInfo[0]->price : $this->leftLenInfo[0]->price;
            $sold->segment_h    =   $eye == 'right' ? $this->r_segment_height : $this->l_segment_height;
            $sold->mono_pd      =   $eye == 'right' ? $this->r_mono_pd : $this->l_mono_pd;
            $sold->is_private   =   $this->insurance_type == 'private' ? 'yes' : null;
            $sold->insurance_id =   $this->insurance_type == 'private' ? null : $this->insurance_type;
            $sold->percentage   =   $this->insurance_type == 'private' ? 0 : $this->insurance_percentage_lens;

            $sold->patient_payment   =   $this->patient_payment_lens;
            $sold->approved_amount   =   $this->insurance_approved_lens;
            $sold->insurance_payment =   $this->insurance_payment_lens;
            $sold->save();

            $total  += $sold->total_amount;
        }

        if ($availability == 'not-available' && $type == 'lens') {
            $data = [
                'invoice_id' => $invoiceId,

                'type'      => $this->lens_type,
                'coating'   => $this->lens_coating,
                'index'     => $this->lens_index,
                'chromatic' => $this->lens_chromatic,

                'eye'       => $eye,
                'sphere'    => $eye == 'right' ? $this->r_sphere : $this->l_sphere,
                'cylinder'  => $eye == 'right' ? $this->r_cylinder : $this->l_cylinder,
                'axis'      => $eye == 'right' ? $this->r_axis : $this->l_axis,
                'addition'  => $eye == 'right' ? $this->r_addition : $this->l_addition,

                'mono_pd'   => $this->r_mono_pd,
                'segment_h' => $this->r_segment_height
            ];

            $repo   =   new ProductRepo();

            $repo->addUnavailableProduct($data);
        } else {
            $sellOther   =   new SoldProduct();
            if ($type == 'frame') {

                $sellOther->company_id   =   userInfo()->company_id;
                $sellOther->product_id   =   $this->frameInfo->id;
                $sellOther->invoice_id   =   $invoiceId;
                $sellOther->quantity     =   '1';
                $sellOther->discount     =   $this->frame_price_adjust;
                $sellOther->unit_price   =   $this->frame_unit_price;
                $sellOther->total_amount =   $this->frame_total_amount;
                $sellOther->is_private   =   $this->insurance_type == 'private' ? 'yes' : null;
                $sellOther->insurance_id =   $this->insurance_type == 'private' ? null : $this->insurance_type;
                $sellOther->percentage   =   $this->insurance_type == 'private' ? 0 : $this->insurance_percentage_frame;

                $sellOther->patient_payment   =   $this->patient_payment_frame;
                $sellOther->approved_amount   =   $this->insurance_approved_frame;
                $sellOther->insurance_payment =   $this->insurance_payment_frame;
                $sellOther->save();

                $total  += $sellOther->total_amount;
            }

            if ($type == 'accessory') {
                $sellOther   =   new SoldProduct();
                $sellOther->company_id   =   userInfo()->company_id;
                $sellOther->product_id   =   $this->accessoryInfo->id;
                $sellOther->invoice_id   =   $invoiceId;
                $sellOther->quantity     =   $this->accessory_quantity;
                $sellOther->discount     =   $this->accessory_price_adjust;
                $sellOther->unit_price   =   $this->accessory_unit_price;
                $sellOther->total_amount =   $this->accessory_total_amount;
                $sellOther->is_private   =   $this->insurance_type == 'private' ? 'yes' : null;
                $sellOther->insurance_id =   $this->insurance_type == 'private' ? null : $this->insurance_type;
                $sellOther->percentage   =   $this->insurance_type == 'private' ? 0 : $this->insurance_percentage_frame;

                $sellOther->patient_payment   =   null;
                $sellOther->approved_amount   =   null;
                $sellOther->insurance_payment =   null;
                $sellOther->save();

                $total  += $sellOther->total_amount;
            }
        }

        Invoice::where('id', $invoiceId)->update([
            'total_amount' => $total
        ]);

        redirect('/manager/editSales/' . Crypt::encrypt($invoiceId))->with('successMsg', 'Invoice ');
    }

    // mount
    function mount()
    {
        $this->lensType             =   LensType::all();
        $this->lensIndex            =   PhotoIndex::all();
        $this->lensCoating          =   PhotoCoating::all();
        $this->lensChromaticAspect  =   PhotoChromatics::all();
        $this->isCloudOrder         =   userInfo()->permissions == 'lab' ? 'yes' : 'no';
        $this->insuranceList        =   Insurance::where('company_id', userInfo()->company_id)->get();

        // non lens products
        $this->frameList  =   Product::where('company_id', userInfo()->company_id)->where('category_id', '2')->orderBy('product_name', 'ASC')->get();

        $this->accessoriesList  =   Product::where('company_id', userInfo()->company_id)->whereNotIn('category_id', ['1', '2'])->get();
    }

    public function render()
    {
        return view('livewire.manager.sales.product-retail')->layout('livewire.livewire-slot');
    }
}
