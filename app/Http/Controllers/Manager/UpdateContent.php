<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Repositories\StockTrackRepo;
use Illuminate\Http\Request;

class UpdateContent extends Controller
{
    private $stocktrackRepo;

    public function __construct()
    {
        $this->stocktrackRepo = new StockTrackRepo();
    }

    public function updateStock(Request $request)
    {
        $id         =   $request->get('pk');
        $name       =   $request->input('name');
        $value      =   $request->input('value');

        $product     =   \App\Models\Product::find($id);

        if ($name == 'stock') {
            if ($product->stock > $value) {
                $variation = $product->stock - $value;

                $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, $variation, $value, 'adjust', 'rm', 'out');
                $product->stock     =   $value;
                $product->save();


                return 'success out';
            }
            if ($product->stock < $value) {
                $variation =  $value - $product->stock;
                $this->stocktrackRepo->saveTrackRecord($product->id, $product->stock, $variation, $value, 'adjust', 'rm', 'in');

                $product->stock     =   $value;
                $product->save();


                return 'success in';
            }
        }

        if ($name == 'price') {
            if ($product->price > $value) {
                $variation = $product->price - $value;

                $this->stocktrackRepo->saveTrackPricing($product->id,$product->price,$value,'decrease','price');
                $product->price     =   $value;
                $product->save();


                return 'success decrease';
            }
            if ($product->price < $value) {
                $variation = $product->price - $value;

                $this->stocktrackRepo->saveTrackPricing($product->id,$product->price,$value,'increase','price');
                $product->price     =   $value;
                $product->save();


                return 'success increase';
            }
        }

        if ($name == 'cost') {
            if ($product->cost > $value) {
                $variation = $product->cost - $value;

                $this->stocktrackRepo->saveTrackPricing($product->id,$product->cost,$value,'decrease','cost');
                $product->cost     =   $value;
                $product->save();


                return 'success decrease';
            }
            if ($product->cost < $value) {
                $variation = $product->cost - $value;

                $this->stocktrackRepo->saveTrackPricing($product->id,$product->cost,$value,'increase','cost');
                $product->cost     =   $value;
                $product->save();


                return 'success increase';
            }
        }
    }
}
