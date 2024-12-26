<?php

namespace App\Http\Livewire\Manager\Sales;

use App\Models\Hospital;
use App\Models\Insurance;
use App\Models\Invoice;
use App\Models\LensPricing;
use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Product;
use App\Models\SoldProduct;
use App\Models\SupplyRequest;
use App\Models\TrackOrderRecord;
use App\Repositories\ProductRepo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BulkOrder extends Component
{

    // repository
    public $informationMessage, $hide_r_axis = false, $hide_l_axis = false;

    // variables for cloud
    public $cloud_id, $hospital_name, $visionCenters, $visionCenter;

    public $name;

    // variables for visionCenter
    public $suppliers, $supplier;
    public $totalLens   = 0, $totalFrames = 0, $totalAccessories = 0;

    // client information variables
    public $firstname, $lastname, $tin_number, $phone;
    public $date_of_birth, $gender, $insurance_number, $affiliate;

    // control variables
    public $lensType, $lensIndex, $lensCoating, $lensChromaticAspect, $frameList, $accessoriesList, $insuranceList;

    public $rightEye = false, $leftEye = false, $showPaymentSection = false, $leftLen = false, $rightLen = false, $leftLenFound = true, $rightLenFound = false, $searchProduct = false, $showsubmit = false, $isCloudOrder = 'no', $isBulkOrder = false, $leftPriceRange = null, $rightPriceRange = null;

    // =-=============== variables for the bulk orders ==================
    public $addedLensList = [], $addFrameList = [], $addedAccessoriesList = [];
    public $showLens    =   true, $showFrames    =   true, $showAccessories    =   true, $right_len_quantity, $left_lent_quantity, $left_len_quantity;

    // ============== lens selection variables ========
    public $lens_type, $lens_index, $lens_coating, $lens_chromatic;

    // ============== lens variable managemnt =========
    public $r_sphere, $r_cylinder, $r_axis, $r_addition, $r_segment_height, $r_mono_pd, $r_sign, $l_sign;

    public $l_sphere, $l_cylinder, $l_axis, $l_addition, $l_segment_height, $l_mono_pd;

    public $leftLenInfo, $leftLenQty, $leftLenID, $rightLenInfo, $rightLenQty, $rightLenID, $rightBooked, $leftBookedCount, $rightBookedCount, $leftBooked;

    // =========== frame variable management==========
    public $frame, $frame_stock, $frame_unit_price, $frame_quantity, $frame_price_adjust = 0, $frame_total_amount, $frame_location, $frameInfo;

    // =========== accessories variables =============
    public $accessory, $accessory_stock, $accessory_unit_price, $accessory_quantity, $accessory_price_adjust = 0, $accessory_total_amount, $accessory_location, $accessoryInfo;

    //  lens calculation variables ==============
    public $total_lens_amount, $total_lens_stock;


    // lens variables for insurance calculations ======
    public $insurance_percentage_lens, $insurance_payment_lens, $insurance_approved_lens, $patient_payment_lens, $insurance_type;

    // frame variables for insurance calculations ======
    public $insurance_percentage_frame, $insurance_payment_frame, $insurance_approved_frame, $patient_payment_frame, $ordered_frames;

    public $invoiceStatus   =   'requested';

    function updatedhospital_name()
    {
        if (Cache::has('visionCenters' . auth()->user()->company_id)) {
            dd(Cache::get('visionCenters' . auth()->user()->company_id));
        } else {
            dd('nothing');
        }
    }

    function updatedSupplier()
    {
        $companyId  =   !is_null($this->supplier) ? $this->supplier : getuserCompanyInfo()->id;
        // non lens products
        $this->frameList  =   Product::where('company_id', $companyId)->where('category_id', '2')->orderBy('product_name', 'ASC')->get();

        $this->accessoriesList  =   Product::where('company_id', $companyId)->whereNotIn('category_id', ['1', '2'])->get();

        if (!is_null($this->supplier)) {

            $this->frame_quantity       =   null;
            $this->frame_stock          =   null;
            $this->frame_unit_price     =   null;
            $this->frame_location       =   null;
            $this->frame_total_amount   =   0;

            // ==============================

            $this->accessory_quantity       =  null;
            $this->accessory_stock          =   null;
            $this->accessory_unit_price     =   null;
            $this->accessory_location       =   null;
            $this->accessory_total_amount   = 0;
        }
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

        $invoiceStock   =   Invoice::where('status', 'requested')->where('company_id', userInfo()->company_id)->whereRelation('soldproduct', 'product_id', $frameResult->id)->withSum('soldproduct', 'quantity')->get();


        $this->ordered_frames   =   $invoiceStock->sum('soldproduct_sum_quantity');
        // dd($invoiceStock->sum('soldproduct_sum_quantity'));

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
        $this->dispatch('showwarningModal');
    }

    function showTotal()
    {
        $this->showPaymentSection   =   true;
    }

    function checkTotal($type)
    {
        if ($type == 'lens') {
            $this->totalLens    =   0;
            for ($i = 0; $i < count($this->addedLensList); $i++) {
                $this->totalLens  =  $this->totalLens + ($this->addedLensList[$i]['product']->price * $this->addedLensList[$i]['quantity']);
            }
        }
        if ($type == 'frame') {
            $this->totalFrames  =   0;
            for ($i = 0; $i < count($this->addFrameList); $i++) {
                $this->totalFrames  =  $this->totalFrames + ($this->addFrameList[$i]['product']->price * $this->addFrameList[$i]['quantity']);
            }
        }
        if ($type == 'accessory') {
            $this->totalAccessories =   0;
            for ($i = 0; $i < count($this->addedAccessoriesList); $i++) {
                $this->totalAccessories  =  $this->totalAccessories + ($this->addedAccessoriesList[$i]['product']->price * $this->addedAccessoriesList[$i]['quantity']);
            }
        }
    }

    function showProducts($typeOfProduct)
    {
        if ($typeOfProduct == 'lens') {
            $this->showLens =   !$this->showLens;
        }
    }

    function checkProduct($type)
    {
        if ($type == 'lens') {
            $this->checkAvailability();
        }
    }

    function addProductToList($type, $eyeSide)
    {
        $totalOfAllList =   0;

        if ($type == 'lens') {

            if (count($this->rightLenInfo) > 0 && $eyeSide == 'right') {

                $rightLimit =   $this->rightLen ? ($this->rightLenQty - $this->rightBooked < 0 ? 0 : $this->rightLenQty - $this->rightBooked) : 0;

                $this->validate([
                    'right_len_quantity' => 'required|numeric|max:' . $rightLimit
                ]);

                if ($this->rightLenInfo && !is_null($this->right_len_quantity)) {
                    $listCount  =   count($this->addedLensList);
                    $this->addedLensList[$listCount]['product']     =   $this->rightLenInfo[0];
                    $this->addedLensList[$listCount]['quantity']    =   $this->right_len_quantity;
                    $this->addedLensList[$listCount]['eye']         =   'Right';

                    $this->r_sphere     =   null;
                    $this->r_cylinder   =   null;
                    $this->r_axis       =   null;
                    $this->r_addition   =   null;
                    $this->rightLenInfo =   [];
                    $this->rightLen  =   null;
                    $this->right_len_quantity   =   null;

                    $this->checkTotal('lens');
                }
            }

            if (count($this->leftLenInfo) > 0 && $eyeSide == 'left') {

                $leftLimit =   $this->leftLen ? ($this->leftLenQty - $this->leftBooked < 0 ? 0 : $this->leftLenQty - $this->leftBooked) : 0;

                $this->validate([
                    'left_len_quantity' => 'required|numeric|max:' . $leftLimit
                ]);

                if ($this->leftLenInfo && !is_null($this->left_len_quantity)) {
                    $listCount  =   count($this->addedLensList);
                    $this->addedLensList[$listCount]['product']     =   $this->leftLenInfo[0];
                    $this->addedLensList[$listCount]['quantity']    =   $this->left_len_quantity;
                    $this->addedLensList[$listCount]['eye']         =   'Left';

                    $this->l_sphere     =   null;
                    $this->l_cylinder   =   null;
                    $this->l_axis       =   null;
                    $this->l_addition   =   null;
                    $this->leftLen  =   null;
                    $this->left_len_quantity    =   null;
                    $this->leftLenInfo  =   [];

                    $this->checkTotal('lens');
                }
            }
        }

        if ($type == 'frame') {

            $this->validate([
                'frame_quantity' => 'required|numeric|max:' . $this->frame_stock
            ]);

            $this->checkAvailability();

            $count  =   count($this->addFrameList);
            $this->addFrameList[$count]['product'] =   $this->frameInfo;
            $this->addFrameList[$count]['quantity'] =   $this->frame_quantity;

            $this->frame    =   null;
            $this->frame_quantity    =   null;
            $this->frame_location    =   null;
            $this->frame_unit_price    =   null;
            $this->frame_stock    =   null;

            $this->checkTotal('frame');
        }

        if ($type == 'accessory') {

            $this->validate([
                'accessory_quantity' => 'required|numeric|max:' . $this->accessory_stock
            ]);

            $this->checkAvailability();

            $count  =   count($this->addedAccessoriesList);
            $this->addedAccessoriesList[$count]['product'] =   $this->accessoryInfo;
            $this->addedAccessoriesList[$count]['quantity'] =   $this->accessory_quantity;

            $this->accessory    =   null;
            $this->accessory_quantity    =   null;
            $this->accessory_location    =   null;
            $this->accessory_unit_price    =   null;
            $this->accessory_stock    =   null;

            $this->checkTotal('accessory');
        }
    }

    function removeProductFromList($key, $type)
    {

        if ($type == 'lens') {
            unset($this->addedLensList[$key]['product']);
            unset($this->addedLensList[$key]['quantity']);
            unset($this->addedLensList[$key]['eye']);
            unset($this->addedLensList[$key]);

            $this->addedLensList    =   array_values($this->addedLensList);

            $this->checkTotal('lens');
        }

        if ($type == 'frame') {
            unset($this->addFrameList[$key]['product']);
            unset($this->addFrameList[$key]['quantity']);
            unset($this->addFrameList[$key]);

            $this->addFrameList    =   array_values($this->addFrameList);

            $this->checkTotal('frame');
        }

        if ($type == 'accessory') {
            unset($this->addedAccessoriesList[$key]['product']);
            unset($this->addedAccessoriesList[$key]['quantity']);
            unset($this->addedAccessoriesList[$key]);

            $this->addedAccessoriesList    =   array_values($this->addedAccessoriesList);

            $this->checkTotal('accessory');
        }
    }

    // checking product availability
    function checkAvailability()
    {
        $this->informationMessage   =   null;
        $left_sphere = $this->l_sphere == 0 ? $this->l_sphere : ($this->l_sign == 'minus' ? -1 * abs($this->l_sphere) : abs($this->l_sphere));
        $right_sphere = $this->r_sphere == 0 ? $this->r_sphere : ($this->r_sign == 'minus' ? -1 * abs($this->r_sphere) : abs($this->r_sphere));

        if ($this->lens_type && $this->lens_coating && $this->lens_chromatic && $this->lens_index) {
            $this->searchProduct    =   true;
        } elseif ($this->lens_type || $this->lens_coating || $this->lens_chromatic || $this->lens_index) {
            $this->searchProduct    =   false;
            $this->showModal('Please fill out all the required lens information');
            return;
        }
        if (count($this->addedLensList) <= 0 && count($this->addFrameList) <= 0 && count($this->addedAccessoriesList) <= 0) {
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
                'sphere'      =>  format_values($right_sphere),
                'cylinder'    =>  format_values($this->r_cylinder),
                'axis'        =>  format_values($this->r_axis),
                'addition'    =>  format_values($this->r_addition),
                'supplier'    =>  !is_null($this->supplier) ? $this->supplier : getuserCompanyInfo()->id,
                'eye'         =>  'right',
            ];

            $left_data  =   [
                'productType'   => 'lens',
                // lens description
                'type'      =>  $this->lens_type,
                'index'     =>  $this->lens_index,
                'coating'   =>  $this->lens_coating,
                'chromatic' =>  $this->lens_chromatic,

                // right side
                'sphere'      =>  format_values($left_sphere),
                'cylinder'    =>  format_values($this->l_cylinder),
                'axis'        =>  format_values($this->l_axis),
                'addition'    =>  format_values($this->l_addition),
                'supplier'    =>  !is_null($this->supplier) ? $this->supplier : getuserCompanyInfo()->id,
                'eye'         =>  'left',
            ];
            // dd($left_data);

            $repo   =   new ProductRepo();

            $right_len_Results  = $repo->searchProduct($right_data);
            $left_len_Results   = $repo->searchProduct($left_data);
            // dd($left_len_Results);

            $this->rightLenInfo =   $right_len_Results;
            $this->leftLenInfo =   $left_len_Results;


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

                $leftPrice      =   $left_len_Results   == 'product-not-found' ? 0 : $left_len_Results[0]->price;
                $rightPrice     =   $right_len_Results  == 'product-not-found' ? 0 : $right_len_Results[0]->price;

                $this->total_lens_amount =    $leftPrice + $rightPrice;
                // }

                // checking for booked stock on lens
                $invoiceStock =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $right_len_Results[0]->id)->withSum('soldproduct', 'quantity')->get();
                $this->rightBooked  =   $invoiceStock->sum('soldproduct_sum_quantity');

                $invoiceStock =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $left_len_Results[0]->id)->withSum('soldproduct', 'quantity')->get();

                $this->leftBooked  =   $invoiceStock->sum('soldproduct_sum_quantity');
            }
            // if left len is the only product
            else if ($left_len_Results != 'product-not-found' && $right_len_Results == 'product-not-found') {
                $this->total_lens_amount =    $left_len_Results[0]->price;

                $invoiceStock =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $left_len_Results[0]->id)->withSum('soldproduct', 'quantity')->get();

                $this->leftBooked  =   $invoiceStock->sum('soldproduct_sum_quantity');
            }
            // if left len is the only product
            else if ($left_len_Results == 'product-not-found' && $right_len_Results != 'product-not-found') {
                $this->total_lens_amount =    $right_len_Results[0]->price;

                // checking for booked stock on lens
                $invoiceStock =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $right_len_Results[0]->id)->withSum('soldproduct', 'quantity')->get();
                $this->rightBooked  =   $invoiceStock->sum('soldproduct_sum_quantity');
            }
        }
        if ($this->frame) {
            $this->frame_total_amount   =   ($this->frame_unit_price + $this->frame_price_adjust) * $this->frame_quantity;
        }
        if ($this->accessory) {
            $this->accessory_total_amount   =   ($this->accessory_unit_price + $this->accessory_price_adjust) * $this->accessory_quantity;
        }
        // $this->showPaymentSection   =   true;
    }

    // function to handle form submit
    function saveOrder()
    {
        if (count($this->addedLensList)<=0 && count($this->addFrameList)<=0 && count($this->addedAccessoriesList)<=0) {

            dd(count($this->addFrameList));
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
            if ($this->frame == null && $this->searchProduct == false && $this->accessory != null) {
                $this->invoiceStatus  =   'requested';
            }

            if ($this->rightLenFound != null && $this->leftLenFound != null) {

                $stock_balancingR    =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $this->rightLenInfo[0]->id)->where('company_id', userInfo()->company_id)->withsum('soldproduct', 'quantity')->get();

                $stock_balancingL    =   Invoice::where('status', 'requested')->whereRelation('soldproduct', 'product_id', $this->leftLenInfo[0]->id)->where('company_id', userInfo()->company_id)->withsum('soldproduct', 'quantity')->get();

                if ($stock_balancingR->sum('soldproduct_sum_quantity') >= ((int)$this->rightLenInfo[0]->stock - 1) || $stock_balancingL->sum('soldproduct_sum_quantity') >= ((int)$this->leftLenInfo[0]->stock - 1)) {
                    $this->invoiceStatus    =   'booked';
                }

                if ($this->rightLenQty < 1 || $this->leftLenQty < 1) {
                    $this->invoiceStatus  =   'Confirmed';
                }
            }

            if (!$this->rightLen || !$this->leftLen) {
                $this->invoiceStatus  =   'requested';
            }

            $invoice    =   new Invoice();

            $invoice->reference_number  =   $reference + 1;
            $invoice->status            =   'pending';
            $invoice->user_id           =   userInfo()->id;
            $invoice->total_amount      =   '0';
            $invoice->cloud_id          =   $this->name;
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
            $invoice->supplier_id       =   $this->supplier;
            $invoice->insurance_card_number =   $this->insurance_number;

            $invoice->save();

            TrackOrderRecord::create([
                'status'        =>  $this->invoiceStatus,
                'user_id'       =>  auth()->user()->id,
                'invoice_id'    =>  $invoice->id,
            ]);

            if (count($this->addedLensList) > 0) {

                if ($this->lens_type || $this->lens_coating || $this->lens_chromatic) {
                    for ($i = 0; $i < count($this->addedLensList); $i++) {
                        $this->save('lens', 'available', $invoice->id, $this->addedLensList[$i]['eye'], $this->addedLensList[$i]['product'], $this->addedLensList[$i]['quantity']);
                    }
                }
            }

            if (count($this->addFrameList) > 0) {
                for ($i = 0; $i < count($this->addFrameList); $i++) {
                    $this->save('lens', 'available', $invoice->id, '', $this->addFrameList[$i]['product'], $this->addFrameList[$i]['quantity']);
                }
            }

            if (count($this->addedAccessoriesList) > 0) {
                for ($i = 0; $i < count($this->addedAccessoriesList); $i++) {
                    $this->save('lens', 'available', $invoice->id, '', $this->addedAccessoriesList[$i]['product'], $this->addedAccessoriesList[$i]['quantity']);
                }
            }
        }
    }

    // function to save product in the database
    function save($type, $availability, $invoiceId, $eye = '', $productInfo, $quantity)
    {
        $total = 0;

        if ($availability == 'available' && $type == 'lens') {

            $sold   =   new SoldProduct();

            $sold->company_id   =   userInfo()->company_id;
            $sold->product_id   =   $productInfo->id;
            $sold->invoice_id   =   $invoiceId;
            $sold->quantity     =   $quantity;
            $sold->discount     =   '0';
            $sold->eye          =   $eye;
            $sold->unit_price   =   $productInfo->price;
            $sold->total_amount =   $productInfo->price * $quantity;
            $sold->axis         =   $eye == 'right' ? $this->r_axis : $this->l_axis;
            $sold->save();

            $total  = $total + $sold->total_amount;
        } else {
            $sellOther   =   new SoldProduct();
            if ($type == 'frame') {

                $sellOther->company_id   =   userInfo()->company_id;
                $sellOther->product_id   =   $productInfo->id;
                $sellOther->invoice_id   =   $invoiceId;
                $sellOther->quantity     =   $quantity;
                $sellOther->unit_price   =   $productInfo->price;
                $sellOther->total_amount =   $productInfo->price * $quantity;
                $sellOther->save();

                $total  += $sellOther->total_amount;
            }

            if ($type == 'accessory') {
                $sellOther   =   new SoldProduct();
                $sellOther->company_id   =   userInfo()->company_id;
                $sellOther->product_id   =   $productInfo->id;
                $sellOther->invoice_id   =   $invoiceId;
                $sellOther->quantity     =   $quantity;
                $sellOther->unit_price   =   $productInfo->price;
                $sellOther->total_amount =   $productInfo->price * $quantity;
                $sellOther->save();

                $total  += $sellOther->total_amount;
            }
        }

        Invoice::where('id', $invoiceId)->update([
            'total_amount' => $total
        ]);

        redirect('/manager/editSales/' . Crypt::encrypt($invoiceId))->with('successMsg', 'Invoice ');
    }

    function updated($type)
    {
        // right side
        if ($type == 'r_cylinder') {
            $this->rightLen =   false;
            if ($this->r_cylinder == 0) {
                $this->hide_r_axis = true;
                $this->r_axis = 0;
            } else {
                $this->r_axis = null;
                $this->hide_r_axis = false;
            }
        }
        if ($type == 'r_sphere') {
            $this->rightLen =   false;
        }
        if ($type == 'r_sphere') {
            $this->rightLen =   false;
        }
        if ($type == 'r_addition') {
            $this->rightLen =   false;
        }

        // left side 
        if ($type == 'l_cylinder') {
            $this->leftLen =   false;
            if ($this->l_cylinder == 0) {
                $this->hide_l_axis = true;
                $this->l_axis = 0;
            } else {
                $this->l_axis = null;
                $this->hide_l_axis = false;
            }
        }
        if (
            $type == 'l_sphere'
        ) {
            $this->leftLen =   false;
        }
        if (
            $type == 'l_sphere'
        ) {
            $this->leftLen =   false;
        }
        if (
            $type == 'l_addition'
        ) {
            $this->leftLen =   false;
        }
    }

    // mount
    function mount()
    {
        $this->lensType             =   LensType::all();
        $this->lensIndex            =   PhotoIndex::all();
        $this->lensCoating          =   PhotoCoating::all();
        $this->lensChromaticAspect  =   PhotoChromatics::all();
        $this->suppliers            =   SupplyRequest::where('request_from', getuserCompanyInfo()->id)->where('status', 'approved')->get();

        $this->isCloudOrder         =   userInfo()->permissions == 'lab' ? 'yes' : 'no';
        $this->insuranceList        =   Insurance::where('company_id', userInfo()->company_id)->get();

        $this->visionCenters        =   Hospital::where('company_id', userInfo()->company_id)->get();
        $this->frameList  =   Product::where('company_id', userInfo()->company_id)->where('category_id', '2')->orderBy('product_name', 'ASC')->get();

        $this->accessoriesList  =   Product::where('company_id', userInfo()->company_id)->whereNotIn('category_id', ['1', '2'])->get();

        // non lens products

    }

    public function render()
    {
        return view('livewire.manager.sales.bulk-order')->layout('livewire.livewire-slot');
    }
}
