<?php

namespace App\Imports\Manager;

use App\Models\LensType;
use App\Models\PhotoChromatics;
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
    private $count;
    private $category;

    function __construct()
    {
        $this->count        =   0;

        $this->stocktrackRepo = new StockTrackRepo();
        $this->lens_type    =   LensType::select(['id', 'name'])->get();
        $this->index        =   PhotoIndex::select(['id', 'name'])->get();
        $this->coating      =   PhotoCoating::select(['id', 'name'])->get();
        $this->chromatics   =   PhotoChromatics::select(['id', 'name'])->get();
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


                if ($un_filtered_data->filter()->isNotEmpty()) {

                    $data   =   $un_filtered_data->filter();

                    $lensInformation    =   $this->findType($data);

                    if ($lensInformation['type'] && $lensInformation['index'] && $lensInformation['chromatic_aspect'] && $lensInformation['coating']) {
                        //     // checkin the availability of the product
                        $right_product_exists =   $this->checkLensExistance($lensInformation, $data);
                    } else {
                        dd('something is missing');
                    }
                }
            } catch (\Throwable $th) {
                dd($th);
            }
        }
        session()->flash('countSkippedImport', $count);
    }

    function findType($product)
    {
        $productType        =   null;
        $productIndex       =   null;
        $productChromatics  =   null;
        $productCoating     =   null;

        // single visions
        if ($product['lens'] == 'SV_PREM' || $product['lens'] == 'SV_BASIC') {
            $this->category =   '1';

            // type
            $productType    =   'SINGLE VISION';
            // ==============================

            // chromatics
            if ($product['lens'] == 'SV_PREM') {
                $productChromatics  =   'Photo';
            } else {
                $productChromatics  =   'Clear';
            }
            // ==============================

            // Coating
            if ($product['lens'] == 'SV_PREM') {
                $productIndex  =   '1.5';
            } else {
                $productIndex  =   '1.5';
            }
            // ==============================


            // Coating
            if ($product['lens'] == 'SV_PREM_') {
                $productCoating  =   'HMC';
            } else {
                $productCoating  =   'HC';
            }
            // ==============================
        }

        // bifocals
        if ($product['lens'] == 'BF_PREM' || $product['lens'] == 'BF_BASIC') {
            $this->category =   '1';

            // chromatics
            if ($product['lens'] == 'BF_PREM_CIRCLE') {
                $productType    =   'BIFOCAL ROUND TOP';
            } else {
                $productType    =   'BIFOCAL';
            }
            // ==============================

            // chromatics
            if ($product['lens'] == 'BF_PREM') {
                $productChromatics  =   'Photo';
            } else {
                $productChromatics  =   'Clear';
            }
            // ==============================

            // Coating
            if ($product['lens'] == 'BF_PREM_ARC') {
                $productIndex  =   '1.5';
            } else {
                $productIndex  =   '1.5';
            }
            // ==============================


            // Coating
            if ($product['lens'] == 'BF_PREM_ARC') {
                $productCoating  =   'HMC';
            } else {
                $productCoating  =   'HC';
            }
            // ==============================
        }

        // progressive
        if ($product['rx_type'] == 'Progressive') {
            $this->category =   '1';
            $productType    =   'PROGRESSIVE';
        }

        $indx   =   $this->index->where('name', strtoupper($productIndex))->first();
        $ctng   =   $this->coating->where('name', strtoupper($productCoating))->first();
        $type   =   $this->lens_type->where('name', strtoupper($productType))->first();
        $chr    =   $this->chromatics->where('name', ucfirst($productChromatics))->first();

        $productInfo['type']                =   $type;
        $productInfo['index']               =   $indx;
        $productInfo['coating']             =   $ctng;
        $productInfo['chromatic_aspect']    =   $chr;

        return $productInfo;
    }

    function checkLensExistance($info, $data)
    {

        $product_right =   Power::where('type_id', $info['type']->id)
            ->where('index_id', $info['index']->id)
            ->where('chromatics_id', $info['chromatic_aspect']->id)
            ->where('coating_id', $info['coating']->id)
            ->where('sphere', format_values($data['od_sphere'] ?? 0))
            ->where('cylinder', format_values($data['od_cylinder'] ?? 0))
            ->where('axis', initials($info['type']->name) != 'SV' ? format_values($data['od_axis']) : null)
            ->where('add', initials($info['type']->name) != 'SV' ? format_values($data['od_add']) : null)
            ->where('eye', initials($info['type']->name) == 'SV' ? 'any' : 'right')
            ->where('company_id', auth()->user()->company_id)
            ->first();

        $product_left =   Power::where('type_id', $info['type']->id)
            ->where('index_id', $info['index']->id)
            ->where('chromatics_id', $info['chromatic_aspect']->id)
            ->where('coating_id', $info['coating']->id)
            ->where('sphere', format_values($data['os_sphere'] ?? 0))
            ->where('cylinder', format_values($data['os_cylinder'] ?? 0))
            ->where('axis', initials($info['type']->name) != 'SV' ? format_values($data['os_axis']) : null)
            ->where('add', initials($info['type']->name) != 'SV' ? format_values($data['os_add']) : null)
            ->where('eye', initials($info['type']->name) == 'SV' ? 'any' : 'left')
            ->where('company_id', auth()->user()->company_id)
            ->first();

        // =====================================================================

        if (is_null($product_right)) {
            try {
                $product    =   Product::create([
                    'category_id'   =>  $this->category,
                    'location'      =>  $data['location'] ?? null,
                    'product_name'  =>  $info['type']->name,
                    'description'   =>  initials(strtoupper($info['type']->name)) . " "
                        . strtoupper($info['index']->name) . " "
                        . ucfirst($info['chromatic_aspect']->name) . " "
                        . strtoupper($info['coating']->name),

                    'stock'         =>  1,
                    'price'         =>  '0',
                    'cost'          =>  '1',
                    'fitting_cost'  =>  '0',
                    'company_id'    =>  auth()->user()->company_id,
                    'deffective_stock' =>  '0',
                ]);

                Power::create([
                    'product_id'    =>  $product->id,
                    'type_id'       =>  $info['type']->id,
                    'index_id'      =>  $info['index']->id,
                    'chromatics_id' =>  $info['chromatic_aspect']->id,
                    'coating_id'    =>  $info['coating']->id,
                    'sphere'        =>  format_values($data['od_sphere'] ?? 0),
                    'cylinder'      =>  format_values($data['od_cylinder'] ?? 0),
                    'axis'          =>  initials($info['type']->name) != 'SV' ? format_values($data['od_axis']) : null,
                    'add'           =>  initials($info['type']->name) != 'SV' ? format_values($data['od_add']) : null,
                    'eye'           =>  initials($info['type']->name) == 'SV' ? 'any' : 'right',
                    'company_id'    =>  auth()->user()->company_id,
                ]);

                $this->stocktrackRepo->saveTrackRecord($product->id, 0, 1, 1, 'initial', 'rm', 'in');
                $this->stocktrackRepo->saveTrackRecord($product->id, 0, 1, 1, 'initial', 'rm', 'out');
            } catch (\Throwable $th) {
                dd($th);
            }
        } else {
            $this->count += 1;
            $this->stocktrackRepo->saveTrackRecord($product_right->id, 0, 1, 1, 'initial', 'rm', 'in');
            $this->stocktrackRepo->saveTrackRecord($product_right->id, 0, 1, 1, 'initial', 'rm', 'out');
        }

        if (is_null($product_left)) {
            // dd('here');
            try {
                $product    =   Product::create([
                    'category_id'   =>  $this->category,
                    'location'      =>  $data['location'] ?? null,
                    'product_name'  =>  $info['type']->name,
                    'description'   =>  initials(strtoupper($info['type']->name)) . " "
                        . strtoupper($info['index']->name) . " "
                        . ucfirst($info['chromatic_aspect']->name) . " "
                        . strtoupper($info['coating']->name),

                    'stock'         =>  1,
                    'price'         =>  '0',
                    'cost'          =>  '1',
                    'fitting_cost'  =>  '0',
                    'company_id'    =>  auth()->user()->company_id,
                    'deffective_stock' =>  '0',
                ]);

                Power::create([
                    'product_id'    =>  $product->id,
                    'type_id'       =>  $info['type']->id,
                    'index_id'      =>  $info['index']->id,
                    'chromatics_id' =>  $info['chromatic_aspect']->id,
                    'coating_id'    =>  $info['coating']->id,
                    'sphere'        =>  format_values($data['os_sphere'] ?? 0),
                    'cylinder'      =>  format_values($data['os_cylinder'] ?? 0),
                    'axis'          =>  initials($info['type']->name) != 'SV' ? format_values($data['os_axis']) : null,
                    'add'           =>  initials($info['type']->name) != 'SV' ? format_values($data['os_add']) : null,
                    'eye'           =>  initials($info['type']->name) == 'SV' ? 'any' : 'left',
                    'company_id'    =>  auth()->user()->company_id,
                ]);

                $this->stocktrackRepo->saveTrackRecord($product->id, 0, 1, 1, 'initial', 'rm', 'in');
                $this->stocktrackRepo->saveTrackRecord($product->id, 0, 1, 1, 'initial', 'rm', 'out');
            } catch (\Throwable $th) {
                dd($th);
            }
        }
        else {
            $this->count += 1;
            $this->stocktrackRepo->saveTrackRecord($product->id, 0, 1, 1, 'initial', 'rm', 'in');
            $this->stocktrackRepo->saveTrackRecord($product->id, 0, 1, 1, 'initial', 'rm', 'out');
        }

        // dd('pruduct added');
    }
}
