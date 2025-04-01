<?php

namespace App\Imports\Manager;

use App\Models\Category;
use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Power;
use App\Models\Product;
use App\Repositories\StockTrackRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if (count($collection->filter()->toArray()) <= 500) {
            $data   =   $collection->filter()->toArray();

            foreach (array_chunk($data, 100) as $un_filtered_data) {
                try {

                    // BF_PREM [Bifocal Photo]

                    // BF_BASIC [Bifocal Clear]

                    // anything without Coating Is HC

                    // ANYTHING WITHOUT INDEX THE INDEX WILL BE 1.56

                    // BF SH CIRCLE this means its rounded 

                    // BF SH LINE is just BIFOCAL in the system

                    // ARC means this is HMC


                    // if ($un_filtered_data->filter()->isNotEmpty()) {

                    foreach ($un_filtered_data as $key => $dataChunk) {
                        $data   =   $dataChunk;

                        // dd($dataChunk['frame_description']);

                        $lensInformation    =   $this->findType($data);

                        if ($lensInformation['type'] && $lensInformation['index'] && $lensInformation['chromatic_aspect'] && $lensInformation['coating']) {

                            // checkin the availability of the product
                            $this->checkLensExistance($lensInformation, 'right', $data);
                            $this->checkLensExistance($lensInformation, 'left', $data);
                        }

                        if ($dataChunk['frame_description']) {
                            $this->checkFrameExistance($data);
                        }
                    }
                    // }
                } catch (\Throwable $th) {
                    dd($th);
                }
            }
            session()->flash('countSkippedImport', $this->count);
        }else{
            session()->flash('errorMsg','The excel sheet contains more than 500 records please reduce them to less or equal to 500 records!');
        }
    }

    function findType($product)
    {
        $productType        =   null;
        $productIndex       =   null;
        $productChromatics  =   null;
        $productCoating     =   null;
        $typ    =   null;

        // single visions
        if ($product['lens'] == 'SV_PREM' || $product['lens'] == 'SV_BASIC' || $product['lens'] == 'SV ,1.67, Clear, HC') {
            $this->category =   '1';

            // type
            $productType    =   'SINGLE VISION';
            // ==============================

            if ($product['lens'] == 'SV ,1.67, Clear, HC') {
                $productChromatics  =   'Clear';
                $productIndex  =   '1.67';
                $productCoating  =   'HC';
            } else {

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
        }

        // bifocals
        if ($product['lens'] == 'BF_PREM' || $product['lens'] == 'BF_BASIC') {
            $this->category =   '1';

            // Type
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

            // Index
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

            // extracting information to get the values of the lens
            $extracted  =   explode(' ', str_replace(',', '', $product['lens']));

            if ($extracted[1] != 'Muchos' && $extracted[1] != 'Justus' && $extracted[1] != 'Clarus') {
                $inserted = array(' ');
                array_splice($extracted, 1, 0, $inserted);
                $productType   =   'PROGRESSIVE';
            } else {
                $productType   =   'PROGRESSIVE ' . strtoupper($extracted[1]);
            }

            $this->category =   '1';
            // ==============================

            // chromatics
            $productChromatics  =   $extracted[4];
            // ==============================

            // Index
            $productIndex  = $extracted[2];
            // ==============================


            // Coating
            $productCoating  = $extracted[3];
            // ==============================


            $type   =   $this->lens_type->where('name', strtoupper($productType))->first();

            if (is_null($type)) {
                try {
                    $typ =   new LensType();

                    $typ->name   =   strtoupper($productType);
                    $typ->save();

                    DB::commit();
                } catch (\Throwable $th) {
                    dd($th);
                }
            }
        }

        $indx   =   $this->index->where('name', strtoupper($productIndex))->first();
        $ctng   =   $this->coating->where('name', strtoupper($productCoating))->first();
        $type   =   $this->lens_type->where('name', strtoupper($productType))->first() ?? $typ;
        $chr    =   $this->chromatics->where('name', ucfirst($productChromatics))->first();

        $productInfo['type']                =   $type;
        $productInfo['index']               =   $indx;
        $productInfo['coating']             =   $ctng;
        $productInfo['chromatic_aspect']    =   $chr;

        // dd($productInfo['type']->name);

        return $productInfo;
    }

    function checkLensExistance($info, $eye, $data)
    {
        $product =   Power::where('type_id', $info['type']->id)
            ->where('index_id', $info['index']->id)
            ->where('chromatics_id', $info['chromatic_aspect']->id)
            ->where('coating_id', $info['coating']->id)
            ->where('sphere', $eye == 'right' ? format_values($data['od_sphere'] ?? 0) : format_values($data['os_sphere'] ?? 0))
            ->where('cylinder', $eye == 'right' ? format_values($data['od_cylinder'] ?? 0) : format_values($data['os_cylinder'] ?? 0))
            ->where('axis', initials($info['type']->name) != 'SV' ? ($eye == 'right' ? format_values($data['od_axis'] ?? 0) : format_values($data['os_axis'] ?? 0)) : 0)
            ->where('add', initials($info['type']->name) != 'SV' ? ($eye == 'right' ? format_values($data['od_add'] ?? null) : format_values($data['os_add'] ?? null)) : null)
            ->where('eye', initials($info['type']->name) == 'SV' ? 'any' : $eye)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        // =====================================================================

        if (is_null($product)) {
            try {
                $product    =   Product::insertGetId([
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
                    'created_at'    =>  now()->toDateTimeString(),
                    'updated_at'    =>  now()->toDateTimeString(),
                ]);

                // dd($product);

                Power::insert([
                    'product_id'    =>  $product,
                    'type_id'       =>  $info['type']->id,
                    'index_id'      =>  $info['index']->id,
                    'chromatics_id' =>  $info['chromatic_aspect']->id,
                    'coating_id'    =>  $info['coating']->id,
                    'sphere'        =>  $eye == 'right' ? format_values($data['od_sphere'] ?? 0) : format_values($data['os_sphere'] ?? 0),
                    'cylinder'      =>  $eye == 'right' ? format_values($data['od_cylinder'] ?? 0) : format_values($data['os_cylinder'] ?? 0),
                    'axis'          =>  initials($info['type']->name) != 'SV' ? ($eye == 'right' ? format_values($data['od_axis'] ?? 0) : format_values($data['os_axis'] ?? 0)) : 0,
                    'add'           =>  initials($info['type']->name) != 'SV' ? ($eye == 'right' ? format_values($data['od_add']) : format_values($data['os_add'])) : null,
                    'eye'           =>  initials($info['type']->name) == 'SV' ? 'any' : $eye,
                    'company_id'    =>  auth()->user()->company_id,
                    // 'created_at'    =>  now()->toDateTimeString(),
                    // 'updated_at'    =>  now()->toDateTimeString(),
                ]);
                $this->count += 1;
                $this->stocktrackRepo->saveTrackRecord($product, 0, 1, 1, 'initial', 'rm', 'in');
                $this->stocktrackRepo->saveTrackRecord($product, 0, 1, 1, 'initial', 'rm', 'out');
            } catch (\Throwable $th) {
                dd($th);
            }
        } else {
            $product_ =   Power::where('type_id', $info['type']->id)
                ->where('index_id', $info['index']->id)
                ->where('chromatics_id', $info['chromatic_aspect']->id)
                ->where('coating_id', $info['coating']->id)
                ->where('sphere', $eye == 'right' ? format_values($data['od_sphere'] ?? 0) : format_values($data['os_sphere'] ?? 0))
                ->where('cylinder', $eye == 'right' ? format_values($data['od_cylinder'] ?? 0) : format_values($data['os_cylinder'] ?? 0))
                ->where('axis', initials($info['type']->name) != 'SV' ? ($eye == 'right' ? format_values($data['od_axis'] ?? 0) : format_values($data['os_axis'] ?? 0)) : 0)
                ->where('add', initials($info['type']->name) != 'SV' ? ($eye == 'right' ? format_values($data['od_add'] ?? null) : format_values($data['os_add'] ?? null)) : null)
                ->where('eye', initials($info['type']->name) == 'SV' ? 'any' : $eye)
                ->where('company_id', auth()->user()->company_id)
                ->first();

            if (!is_null($product_)) {
                $this->stocktrackRepo->saveTrackRecord($product_->product_id, 0, 1, 1, 'initial', 'rm', 'in');
                $this->stocktrackRepo->saveTrackRecord($product_->product_id, 0, 1, 1, 'initial', 'rm', 'out');
            }
        }
    }

    function checkFrameExistance($name)
    {
        $cleanName  =   cleanString($name['frame_description']);
        $frame      =   Product::where('slug_name', $cleanName)->where('company_id', Auth::user()->company_id)->first();

        if (is_null($frame)) {
            $product    =   Product::insertGetId([
                'cost'              =>  '0',
                'stock'             =>  '1',
                'price'             =>  '0',
                'location'          =>  '-',
                'company_id'        =>  Auth::user()->company_id,
                'fitting_cost'      =>  '0',
                'category_id'       =>  2,
                'description'       =>  $name['frame_description'],
                'product_name'      =>  $name['frame_sku'],
                'slug_name'         =>  $cleanName,
                'deffective_stock'  =>  '0',
                // 'created_at'    =>  now()->toDateTimeString(),
                // 'updated_at'    =>  now()->toDateTimeString(),
            ]);
            $this->count += 1;
            $this->stocktrackRepo->saveTrackRecord($product, 0, 1, 1, 'initial', 'rm', 'in');
            $this->stocktrackRepo->saveTrackRecord($product, 0, 1, 1, 'initial', 'rm', 'out');

            // dd($product);
        } else {
            if (!is_null($frame  =   Product::where('slug_name', $cleanName)->where('company_id', Auth::user()->company_id)->first())) {
                # code...
                $this->stocktrackRepo->saveTrackRecord($frame->id, 0, 1, 1, 'initial', 'rm', 'in');
                $this->stocktrackRepo->saveTrackRecord($frame->id, 0, 1, 1, 'initial', 'rm', 'out');
            }
        }
    }
}
