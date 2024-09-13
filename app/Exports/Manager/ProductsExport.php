<?php

namespace App\Exports\Manager;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    public function collection()
    {
        return Product::with('power', 'category')->select('id', 'category_id', 'product_name', 'description', 'stock', 'cost', 'price', 'wholesale_price')->where('company_id', Auth::user()->company_id)->get();
    }

    function map($products): array
    {
        // dd($products);
        return [
            $products->id,
            $products->category->name,
            $products->product_name,
            $products->description,
            $products->power?->sphere,
            $products->power?->cylinder,
            $products->power?->axis,
            $products->power?->add,
            $products->power?->eye,
            $products->stock,
            $products->cost,
            $products->price,
            $products->wholesale_price,
        ];
    }

    public function headings(): array
    {
        return ["id", "Category", "Name", "Description", "sphere", "cylinder", "axis", "add", "eye", "stock", "cost", "price", "wholesale price"];
    }
}
