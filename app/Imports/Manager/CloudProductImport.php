<?php

namespace App\Imports\Manager;

use App\Models\LensType;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Power;
use App\Models\Product;
use App\Repositories\StockTrackRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CloudProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param Collection $collection
     */

    private $index;
    private $coating;
    private $lens_type;
    private $chromatics;
    private $stocktrackRepo;

    function __construct()
    {
        $this->stocktrackRepo = new StockTrackRepo();
        $this->lens_type    =   LensType::select(['id', 'name'])->get();
        $this->index        =   PhotoIndex::select(['id', 'name'])->get();
        $this->coating      =   PhotoCoating::select(['id', 'name'])->get();
    }


    public function collection(Collection $collection)
    {
        $count  =   0;

        foreach ($collection as $un_filtered_data) {
            try {

                // BF_PREM [Bifocal Photo]

                // BF_BASIC [Bifocal Clear]

                // anything without Coating Is HC

                // ANYTHING WITHOUT INDEX THE INDEX WILL BE 1.56

                // BF SH CIRCLE this means its rounded 

                // BF SH LINE is just BIFOCAL in the system

                // ARC means this is HMC



                // $frameType  =   $un_filtered_data['lens']== 'BF_PREM'? 'BIFOCAL': ($un_filtered_data['lens']== 'BF_BASIC'?'BIFOCAL':'');

                // dd($un_filtered_data);

                if ($un_filtered_data->filter()->isNotEmpty()) {
                    $data   =   $un_filtered_data->filter();



                    dd($this->findType($data['lens']));

                    // if ($type && $indx && $chr && $ctng) {
                    //     // checkin the availability of the product
                    //     $product_exists =   Power::where('type_id', $type->id)
                    //         ->where('index_id', $indx->id)
                    //         ->where('chromatics_id', $chr->id)
                    //         ->where('coating_id', $ctng->id)
                    //         ->where('sphere', format_values($data['sphere'] ?? 0))
                    //         ->where('cylinder', format_values($data['cylinder'] ?? 0))
                    //         ->where('axis', format_values($data['axis'] ?? 0))
                    //         ->where('add', format_values($data['add'] ?? 0))
                    //         ->where('eye', initials($data['lens_type']) == 'SV' ? 'any' : $data['eye'])
                    //         ->where('company_id', Auth::user()->company_id)
                    //         ->exists();

                    //         dd($product_exists);

                    //     // =====================================================================

                    //     // if (!$product_exists) {
                    //     //     $product    =   Product::create([
                    //     //         'category_id'   =>  '1',
                    //     //         'location'   =>  $data['location'] ?? null,
                    //     //         'product_name'  =>  $data['lens_type'],
                    //     //         'description'   =>  initials(strtoupper($data['lens_type'])) . " "
                    //     //             . strtoupper($un_filtered_data['index']) . " "
                    //     //             . ucfirst($data['chromatics_aspects']) . " "
                    //     //             . strtoupper($data['coating']),

                    //     //         'stock'         =>  $un_filtered_data['quantity_on_hand'],
                    //     //         'price'         =>  $data['retail_price'],
                    //     //         'cost'          =>  $data['supplier_cost'],
                    //     //         'fitting_cost'  =>  '0',
                    //     //         'company_id'    =>  Auth::user()->company_id,
                    //     //         'deffective_stock' =>  '0',
                    //     //     ]);

                    //     //     Power::create([
                    //     //         'product_id'    =>  $product->id,
                    //     //         'type_id'       =>  $type->id,
                    //     //         'index_id'      =>  $indx->id,
                    //     //         'chromatics_id' =>  $chr->id,
                    //     //         'coating_id'    =>  $ctng->id,
                    //     //         'sphere'        =>  format_values($data['sphere'] ?? 0),
                    //     //         'cylinder'      =>  format_values($data['cylinder'] ?? 0),
                    //     //         'axis'          =>  format_values($data['axis'] ?? 0),
                    //     //         'add'           =>  format_values($data['add'] ?? 0),
                    //     //         'eye'           =>  strtoupper($data['lens_type']) == 'SINGLE VISION' ?   'any'   :   $data['eye'],
                    //     //         'company_id'    =>  Auth::user()->company_id,
                    //     //     ]);
                    //     //     $this->stocktrackRepo->saveTrackRecord($product->id, 0, $product->stock, $product->stock, 'initial', 'rm', 'in');
                    //     // } else {
                    //     //     $count++;
                    //     // }
                    // }
                }
            } catch (\Throwable $th) {
                dd($th);
            }
        }
        session()->flash('countSkippedImport', $count);
    }

    function findType($product)
    {
        $productType    =   null;
        $productIndex   =   null;

        // looking for the product type
        if ($product == 'BF_PREM' || $product == 'BF_BASIC') {
            $productType    =   'BIFOCAL';
        }

        if ($product == 'SV_PREM' || $product == 'SV_BASIC') {
            $productType    =   'SINGLE VISION';
        }

        if (str_starts_with('prog',$product)) {
            $productType    =   'PROGRESSIVE';
        }

        // looking for the product index
        if ($productType == 'BIFOCAL' || $productType == 'SINGLE VISION') {
            $productIndex    =   '1.56';
        }


        // looking for the product coating
        // looking for the product chromatic aspect

        // $indx   =   $this->index->where('name', strtoupper($productIndex))->first();
        // $ctng   =   $this->coating->where('name', strtoupper($productType))->first();
        // $type   =   $this->lens_type->where('name', strtoupper($productType))->first();
        // $chr    =   $this->chromatics->where('name', ucfirst($productType))->first();

        $productInfo['type']                =   $productIndex;
        $productInfo['index']               =   $productIndex;
        // $productInfo['coating']             =   $ctng;
        // $productInfo['chromatic_aspect']    =   $type;

        return $productType;
    }
}
