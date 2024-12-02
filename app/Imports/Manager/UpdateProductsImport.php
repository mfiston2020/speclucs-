<?php

namespace App\Imports\Manager;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UpdateProductsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    public function collection(Collection $collection)
    {
        try {
            foreach ($collection as $un_filtered_data) {
                if ($un_filtered_data->filter()->isNotEmpty()) {
                    $data   =   $un_filtered_data->filter();

                    if (!is_null($un_filtered_data['stock'])) {
                        Product::where('id', $data['id'])->update([
                            'description'       =>  $un_filtered_data['description'],
                            'product_name'      =>  $un_filtered_data['name'],
                            'cost'              =>  $un_filtered_data['cost'],
                            'price'             =>  $un_filtered_data['price'],
                            'stock'             =>  $un_filtered_data['stock'],
                            'wholesale_price'   =>  $un_filtered_data['wholesale_price'],
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            session()->flash('missingData', 'Check for missing values in the excel sheet');
        }
    }
}
