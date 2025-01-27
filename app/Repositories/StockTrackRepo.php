<?php

namespace App\Repositories;

use App\Interface\StockTrackInterface;
use App\Models\Category;
use App\Models\Power;
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
        $results    =   array();

        // ================ getting the value of seleected items ========
        $lt     =   $type;
        $ix     =   $indx;
        $chrm   =   $chromatic;
        $ct     =   $coating;


        $powers =   Power::with('product')->where('type_id', $lt)->where('index_id', $ix)
            ->where('chromatics_id', $chrm)
            ->where('coating_id', $ct)
            ->select('product_id', 'sphere', 'cylinder', 'axis', 'add')
            ->get();

        // $productListing   =   Product::with('power')->where('company_id', $companyId)
        //     ->where('category_id', Category::where('name', 'Lens')->pluck('id')->first())
        //     ->whereHas('power', function ($query) use ($lt, $ix, $chrm, $ct) {
        //         $query->where('index_id', $ix)
        //             ->where('type_id', $lt)
        //             ->where('chromatics_id', $chrm)
        //             ->where('coating_id', $ct);
        //     })->select('id')->get();

        $results    =   [
            'powers'        =>  $powers,
            'sphere_max'    =>  $powers->max('sphere'),
            'sphere_min'    =>  $powers->min('sphere'),
            'cylinder_min'  =>  $powers->min('cylinder'),
            'cylinder_max'  =>  $powers->max('cylinder'),
            'lt'            =>  $lens_type->where('id', $lt)->pluck('name')->first(),
            'ix'            =>  $index->where('id', $ix)->pluck('name')->first(),
            'chrm'          =>  $chromatics->where('id', $chrm)->pluck('name')->first(),
            'ct'            =>  $coatings->where('id', $ct)->pluck('name')->first(),


            'ltid'            =>  $lt,
            'ixid'            =>  $ix,
            'chrmid'          =>  $chrm,
            'ctid'            =>  $ct,


            'add_max'       =>  $powers->max('cylinder'),
            'add_min'       =>  $powers->min('cylinder'),

            'lens_type'     =>  $lens_type,
            'index'         =>  $index,
            'chromatics'    =>  $chromatics,
            'coatings'      =>  $coatings,
        ];

        // dd($results['powers'][0]);

        return $results;
    }
}
