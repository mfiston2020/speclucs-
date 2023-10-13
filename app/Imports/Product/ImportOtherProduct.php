<?php

namespace App\Imports\Product;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\StockTrackRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportOtherProduct implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param Collection $collection
     */


    private $stocktrackRepo;

    function __construct()
    {
        $this->stocktrackRepo = new StockTrackRepo();
    }


    public function collection(Collection $collection)
    {
        $count  =   0;
        try {

            foreach ($collection as $un_filtered_data) {

                if ($un_filtered_data->filter()->isNotEmpty()) {

                    $data   =   $un_filtered_data->filter();

                    $cost   = $data['cost'];
                    $on_hand_quantity   = $data['on_hand_quantity'];
                    $price   = $data['price'];
                    $location   = $data['location'];
                    $description   = $data['description'];
                    $product_name   = $data['product_name'];

                    if (!Product::where('product_name', $product_name)->exists()) {
                        $product    =   Product::create([
                            'cost'              =>  $cost,
                            'stock'             =>  $on_hand_quantity,
                            'price'             =>  $price,
                            'location'          =>  $location,
                            'company_id'        =>  Auth::user()->company_id,
                            'fitting_cost'      =>  '0',
                            'category_id'       =>  Category::where('name', $data['category'])->pluck('id')->first(),
                            'description'       =>  $description,
                            'product_name'      =>  $product_name,
                            'deffective_stock'  =>  '0',
                        ]);
                    }

                    $this->stocktrackRepo->saveTrackRecord($product->id, 0, $product->stock, $product->stock, 'initial', 'rm', 'in');
                }
            }
        } catch (\Throwable $th) {
            dd($th);
        }
        session()->flash('countSkippedImport', $count);
    }
}
