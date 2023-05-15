<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $products   =   DB::table('products')->join('categories','products.category_id','categories.id')
                        ->select('categories.name','products.product_name','products.description','products.stock','products.price','products.created_at')
                        ->get();

        return $products;
    }
}
