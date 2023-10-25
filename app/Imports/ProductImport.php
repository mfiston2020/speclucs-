<?php

namespace App\Imports;

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

class ProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param Collection $collection
     */

    private $index;
    private $coating;
    private $lens_type;
    private $chromatics;
    private $stocktrackRepo;

    function __construct()
    {
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
                $indx   =   $this->index->where('name', strtoupper($un_filtered_data['index']))->first();
                $ctng   =   $this->coating->where('name', strtoupper($un_filtered_data['coating']))->first();
                $type   =   $this->lens_type->where('name', strtoupper($un_filtered_data['lens_type']))->first();
                $chr    =   $this->chromatics->where('name', ucfirst($un_filtered_data['chromatics_aspects']))->first();

                if ($un_filtered_data->filter()->isNotEmpty()) {
                    $data   =   $un_filtered_data->filter();

                    // checkin the availability of the product
                    $product_exists =   Power::where('type_id', $type->id)
                        ->where('index_id', $indx->id)
                        ->where('chromatics_id', $chr->id)
                        ->where('coating_id', $ctng->id)
                        ->where('sphere', format_values($data['sphere'] ?? 0))
                        ->where('cylinder', format_values($data['cylinder'] ?? 0))
                        ->where('axis', format_values($data['axis'] ?? 0))
                        ->where('add', format_values($data['add'] ?? 0))
                        ->where('eye', initials($data['lens_type']) == 'SV' ? 'any' : $data['eye'])
                        ->exists();

                    // =====================================================================

                    if (!$product_exists) {
                        $product    =   Product::create([
                            'category_id'   =>  '1',
                            'location'   =>  $data['location'],
                            'product_name'  =>  $data['lens_type'],
                            'description'   =>  initials(strtoupper($data['lens_type'])) . " "
                                . strtoupper($data['index']) . " "
                                . ucfirst($data['chromatics_aspects']) . " "
                                . strtoupper($data['coating'].'-'.$data['eye']),

                            'stock'         =>  $un_filtered_data['quantity_on_hand'],
                            'price'         =>  $data['retail_price'],
                            'cost'          =>  $data['supplier_cost'],
                            'fitting_cost'  =>  '0',
                            'company_id'    =>  Auth::user()->company_id,
                            'deffective_stock' =>  '0',
                        ]);


                        $this->stocktrackRepo->saveTrackRecord($product->id, 0, $product->stock, $product->stock, 'initial', 'rm', 'in');

                        Power::create([
                            'product_id'    =>  $product->id,
                            'type_id'       =>  $type->id,
                            'index_id'      =>  $indx->id,
                            'chromatics_id' =>  $chr->id,
                            'coating_id'    =>  $ctng->id,
                            'sphere'        =>  format_values($data['sphere'] ?? 0),
                            'cylinder'      =>  format_values($data['cylinder'] ?? 0),
                            'axis'          =>  format_values($data['axis'] ?? 0),
                            'add'           =>  format_values($data['add'] ?? 0),
                            'eye'           =>  strtoupper($data['lens_type']) == 'SINGLE VISION' ?   'any'   :   $data['eye'],
                            'company_id'    =>  Auth::user()->company_id,
                        ]);
                    } else {
                        $count++;
                    }
                }
            } catch (\Throwable $th) {
                dd($th);
            }
        }
        session()->flash('countSkippedImport', $count);
    }
}
