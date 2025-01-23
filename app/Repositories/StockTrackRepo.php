<?php

namespace App\Repositories;

use App\Interface\StockTrackInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\SoldProduct;
use App\Models\TrackPricingChange;
use App\Models\TrackStockRecord;
use Illuminate\Support\Facades\Auth;

class StockTrackRepo implements StockTrackInterface
{

    public String $message  =   '';
    public bool $showProductDetails  =   false;
    public $products;

    function saveTrackRecord(string $productId, string $currentStock, string $incoming, string $change, string $reason, string $type, string $operation)
    {
        TrackStockRecord::create([
            'product_id'    => $productId,
            'user_id'       => userInfo()->id,
            'company_id'    => userInfo()->company_id,
            'current_stock' => $currentStock,
            'incoming'      => $incoming,
            'change'        => $operation == 'in' ? (int)$currentStock + (int)$incoming : (int)$currentStock - (int)$incoming,
            'operation'     => $operation,
            'reason'        => $reason,
            'type'          => $type,
            'status'        => $operation,
        ]);
    }

    function saveTrackPricing(string $productId, string $currentPrice, string $incoming, string $status, string $side)
    {
        TrackPricingChange::create([
            'company_id' =>  userInfo()->company_id,
            'user_id'   =>  userInfo()->id,
            'product_id' =>  $productId,
            'new'       =>  $incoming,
            'old'       =>  $currentPrice,
            'status'    =>  $status,
        ]);
    }

    function stockLens(string $type, string $indx, string $chromatic, string $coating)
    {
        $companyId  =   auth()->user()->company_id;
        $lens_type  =   \App\Models\LensType::select('id', 'name')->get();
        $index      =   \App\Models\PhotoIndex::select('id', 'name')->get();
        $chromatics =   \App\Models\PhotoChromatics::select('id', 'name')->get();
        $coatings   =   \App\Models\PhotoCoating::select('id', 'name')->get();

        // ==========================================
        $sphere     =   array();
        $cylinder   =   array();
        $add        =   array();
        $results    =   array();


        $sphere_max =   [];
        $sphere_min =   [];

        $cylinder_min   =   [];
        $cylinder_max   =   [];

        $add_max    =   [];
        $add_min    =   [];

        $productStock   =   array();

        $message    =   null;

        // ================ getting the value of seleected items ========
        $lt     =   $type;
        $ix     =   $indx;
        $chrm   =   $chromatic;
        $ct     =   $coating;

        $productListing   =   Product::where('company_id', $companyId)
                                        ->where('category_id', Category::where('name', 'Lens')->pluck('id')->first())
                                        ->whereHas('power', function ($query) use ($lt, $ix, $chrm, $ct) {
                                            $query->where('index_id', $ix)
                                                ->where('type_id', $lt)
                                                ->where('chromatics_id', $chrm)
                                                ->where('coating_id', $ct);
                                        })->select('id', 'stock')
                                        ->get();

        if ($productListing->isEmpty()) {
            return $results;
        } else {
            $productStock   =   [];
            foreach ($productListing as $result) {

                if (is_null($result->power)) {
                    continue;
                }

                if (
                    initials($lens_type->where('id', $type)->pluck('name')->first()) == 'SV'
                    && $result->power->index_id == $ix
                    && $result->power->type_id == $lt
                    && $result->power->chromatics_id == $chrm
                    && $result->power->coating_id == $ct
                ) {

                    // array to store all values of power separately
                    $sphere[] =   $result->power->sphere;
                    $cylinder[] =   $result->power->cylinder;

                    $productStock[format_values($result->power->sphere)][format_values($result->power->cylinder)]    = $result->stock;
                } elseif (
                    initials($lens_type->where('id', $type)->pluck('name')->first()) == 'BT'
                    && $result->power->index_id == $ix
                    && $result->power->type_id == $lt
                    && $result->power->chromatics_id == $chrm
                    && $result->power->coating_id == $ct
                ) {

                    // array to store all values of power separately
                    $add[] =   $result->power->add;
                    $sphere[] =   $result->power->sphere;

                    $productStock[format_values($result->power->sphere)][format_values($result->power->add)]    = $result->stock;
                }

                if (
                    initials($lens_type->where('id', $type)->pluck('name')->first()) != 'BT'
                    && $result->power->index_id     ==  $ix
                    && $result->power->type_id      ==  $lt
                    && $result->power->coating_id   ==  $ct
                    && $result->power->chromatics_id ==  $chrm
                ) {
                    // array to store all values of power separately
                    $add[]      =   $result->power->add;
                    $eye[]      =   $result->power->eye;
                    $sphere[]   =   $result->power->sphere;

                    if ($result->power->eye == 'Right' || $result->power->eye == 'R') {
                        $productStock[format_values($result->power->sphere)][format_values($result->power->add)]['Right']    = $result->stock;
                    }

                    if ($result->power->eye == 'Right' || $result->power->eye == 'L') {
                        $productStock[format_values($result->power->sphere)][format_values($result->power->add)]['Left']    = $result->stock;
                    }
                }
            }

            if (count($productStock) <= 0) {
                return $results;
            }

            if (initials($lens_type->where('id', $type)->pluck('name')->first()) == 'SV') {
                $sphere_min =   min(array_unique($sphere));
                $sphere_max =   max(array_unique($sphere));

                $cylinder_min =   min(array_unique($cylinder));
                $cylinder_max =   max(array_unique($cylinder));
            } else {
                $sphere_min =   min(array_unique($sphere));
                $sphere_max =   max(array_unique($sphere));

                $add_min =   min(array_unique($add));
                $add_max =   max(array_unique($add));
            }

            $products_id_array   =   array();

            if (initials($lens_type->where('id', $type)->pluck('name')->first()) != 'SV') {
                for ($i = $sphere_min; $i <= $sphere_max; $i = $i + 0.25) {
                    for ($j = $add_min; $j <= $add_max; $j = $j + 0.25) {
                        $product_id = \App\Models\Power::where(['sphere' => format_values($i)])
                            ->where('type_id', $lt)
                            ->where('index_id', $ix)
                            ->where('chromatics_id', $chrm)
                            ->where('coating_id', $ct)
                            ->where('company_id', Auth::user()->company_id)
                            ->where(['add' => format_values($j)])
                            ->select('product_id', 'sphere', 'add')->get();

                        foreach ($product_id as $p_id) {
                            // array_push($products_id_array, $p_id);
                            $products_id_array = [
                                'sphere'  =>  $i,
                                'cylinder'  =>  $i,
                                'product_id' =>  $p_id,
                                'soldProducts' =>    SoldProduct::where('product_id', $p_id)->sum('quantity')
                            ];
                        }
                    }
                }
            }

            $lt     =   $lens_type->where('id', $type)->pluck('name')->first();
            $ix     =   $index->where('id', $indx)->pluck('name')->first();
            $chrm   =   $chromatics->where('id', $chromatic)->pluck('name')->first();
            $ct     =   $coatings->where('id', $coating)->pluck('name')->first();


            $results    =   [
                'products_id_array'    =>  $products_id_array,
                'sphere_max'    =>  $sphere_max,
                'sphere_min'    =>  $sphere_min,
                'cylinder_min'  =>  $cylinder_min,
                'cylinder_max'  =>  $cylinder_max,
                'lt'            =>  $lt,
                'ix'            =>  $ix,
                'chrm'          =>  $chrm,
                'ct'            =>  $ct,
                'add_max'       =>  $add_max,
                'add_min'       =>  $add_min
            ];

            return $results;
        }
    }
}
