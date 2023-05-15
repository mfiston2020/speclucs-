<?php

namespace App\Imports;

use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Power;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param Collection $collection
    */

    private $index;
    private $coating;
    private $lens_type;
    private $chromatics;

    function __construct()
    {
        $this->lens_type    =   LensType::select(['id','name'])->get();
        $this->index        =   PhotoIndex::select(['id','name'])->get();
        $this->coating      =   PhotoCoating::select(['id','name'])->get();
        $this->chromatics   =   PhotoChromatics::select(['id','name'])->get();
    }

    public function collection(Collection $collection)
    {
        foreach($collection as $un_filtered_data)
        {

            $indx   =   $this->index->where('name',strtoupper($un_filtered_data['index']))->first();
            $ctng   =   $this->coating->where('name',strtoupper($un_filtered_data['coating']))->first();
            $type   =   $this->lens_type->where('name',strtoupper($un_filtered_data['lens_type']))->first();
            $chr    =   $this->chromatics->where('name',ucfirst($un_filtered_data['chromatics_aspects']))->first();

            if($un_filtered_data->filter()->isNotEmpty())
            {
                $data   =   $un_filtered_data->filter();

                $product    =   Product::create([
                    'category_id'   =>  '1',
                    'product_name'  =>  $data['lens_type'],
                    'description'   =>  initials(strtoupper($data['lens_type']))." "
                                        .strtoupper($data['index'])." "
                                        .ucfirst($data['chromatics_aspects'])." "
                                        .strtoupper($data['coating']),

                    'stock'         =>  $data['quantity_on_hand'],
                    'price'         =>  $data['retail_price'],
                    'cost'          =>  $data['whole_seller_price'],
                    'fitting_cost'  =>  '0',
                    'company_id'    =>  Auth::user()->company_id,
                    'deffective_stock'=>  '0',
                ]);

                Power::create([
                    'product_id'    =>  $product->id,
                    'type_id'       =>  $type->id,
                    'index_id'      =>  $indx->id,
                    'chromatics_id' =>  $chr->id,
                    'coating_id'    =>  $ctng->id,
                    'sphere'        =>  format_values($data['sphere']),
                    'cylinder'      =>  format_values($data['cylinder']),
                    'axis'          =>  format_values($data['axis']),
                    'add'           =>  format_values($data['add']),
                    'eye'           =>  strtoupper($data['lens_type'])=='SINGLE VISION' ?   'any'   :   $data['eye'],
                    'company_id'    =>  Auth::user()->company_id,
                ]);
            }
        }
    }
}
