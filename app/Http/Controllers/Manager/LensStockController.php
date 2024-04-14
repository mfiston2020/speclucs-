<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;


class LensStockController extends Controller
{
    private $productRepo;

    public function __construct() {
        $this->productRepo = new ProductRepo();
    }


    public function index($data)
    {
        $lens_type  =   \App\Models\LensType::all();
        $index  =   \App\Models\PhotoIndex::all();
        $chromatics  =   \App\Models\PhotoChromatics::all();
        $coatings  =   \App\Models\PhotoCoating::all();
        $results    =   $data;

        return view('manager.stockLens.index',compact('lens_type','index','chromatics','coatings','results'));
    }

    public function lensStockSearch(Request $request)
    {
        $companyId  =   auth()->user()->company_id;
        $lens_type  =   \App\Models\LensType::select('id','name')->get();
        $index      =   \App\Models\PhotoIndex::select('id','name')->get();
        $chromatics =   \App\Models\PhotoChromatics::select('id','name')->get();
        $coatings   =   \App\Models\PhotoCoating::select('id','name')->get();

        // ==========================================
        $sphere     =   array();
        $cylinder   =   array();
        $add        =   array();
        $my_array   =   array();


        $sphere_max =   [];
        $sphere_min =   [];

        $cylinder_min   =   [];
        $cylinder_max   =   [];

        $add_max    =   [];
        $add_min    =   [];

        $productStock   =   array();

        // ================ getting the value of seleected items ========
        $lt     =   $request->lens_type;
        $ix     =   $request->index;
        $chrm   =   $request->chromatics;
        $ct     =   $request->coating;

        $productListing   =   Product::where('company_id',$companyId)
                                        ->where('category_id','1')
                                        ->with('power')->select('id','stock')
                                        ->get();

        $this->validate($request,[
            'lens_type'=>'required',
            'index'=>'required',
            'chromatics'=>'required',
            'coating'=>'required',
        ]);

        if ($productListing->isEmpty())
        {
            return redirect()->route('manager.lens.stock',0)->with('warningMsg','No Result Found This search')->withInput();
        }
        else{
            foreach($productListing as $result){

                if (initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())=='SV' && $result->power->index_id==$ix && $result->power->type_id==$lt && $result->power->chromatics_id==$chrm && $result->power->coating_id==$ct) {

                    // array to store all values of power separately
                    $sphere[] =   $result->power->sphere;
                    $cylinder[] =   $result->power->cylinder;

                    $productStock[format_values($result->power->sphere)][format_values($result->power->cylinder)]    = $result->stock;
                }

                elseif (initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())=='BT' && $result->power->index_id==$ix && $result->power->type_id==$lt && $result->power->chromatics_id==$chrm && $result->power->coating_id==$ct) {

                    // array to store all values of power separately
                    $add[] =   $result->power->add;
                    $sphere[] =   $result->power->sphere;

                    $productStock[format_values($result->power->sphere)][format_values($result->power->add)]    = $result->stock;
                }

                if(initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())!='SV' && initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())=='BT'){
                    // array to store all values of power separately
                    $add[]      =   $result->power->add;
                    $eye[]      =   $result->power->eye;
                    $sphere[]   =   $result->power->sphere;

                    if ($result->power->eye=='Right' || $result->power->eye=='R') {
                        $productStock[format_values($result->power->sphere)][format_values($result->power->add)]['Right']    = $result->stock;
                    }

                    if ($result->power->eye=='Right' || $result->power->eye=='L') {
                        $productStock[format_values($result->power->sphere)][format_values($result->power->add)]['Left']    = $result->stock;
                    }
                }
            }

            if (count($productStock)<=0)
            {
                return redirect()->route('manager.lens.stock',0)->with('warningMsg','No Result Found This search')->withInput();
            }

            if (initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())=='SV'){
                $sphere_min =   min( array_unique($sphere) );
                $sphere_max =   max( array_unique($sphere) );

                $cylinder_min =   min( array_unique($cylinder) );
                $cylinder_max =   max( array_unique($cylinder) );
            }
            else{
                $sphere_min =   min( array_unique($sphere) );
                $sphere_max =   max( array_unique($sphere) );

                $add_min =   min( array_unique($add) );
                $add_max =   max( array_unique($add) );
            }

            $lt     =   $lens_type->where('id',$request->lens_type)->pluck('name')->first();
            $ix     =   $index->where('id',$request->index)->pluck('name')->first();
            $chrm   =   $chromatics->where('id',$request->chromatics)->pluck('name')->first();
            $ct     =   $coatings->where('id',$request->coatings)->pluck('name')->first();


            if (initials($lt)=='SV') {
                for ($i = $sphere_max; $i >= $sphere_min; $i=$i-0.25) {
                    for ($j = $cylinder_max; $j >= $cylinder_min; $j=$j-0.25) {
                        try {
                            $productStock[format_values($i)][format_values($j)];
                        } catch (\Throwable $th) {
                            $productStock[format_values($i)][format_values($j)]=null;
                        }
                    }
                }
            }elseif (initials($lt)=='BT') {
                for ($i = $sphere_max; $i >= $sphere_min; $i=$i-0.25) {
                    for ($j = $add_max; $j >= $add_min; $j=$j-0.25) {
                        try {
                            $productStock[format_values($i)][format_values($j)];
                        } catch (\Throwable $th) {
                            $productStock[format_values($i)][format_values($j)]=null;
                        }
                    }
                }
            }else{
                for ($i = $sphere_max; $i >= $sphere_min; $i=$i-0.25) {
                    for ($j = $add_max; $j >= $add_min; $j=$j-0.25) {
                        try {
                            $productStock[format_values($i)][format_values($j)]['Right'];
                            $productStock[format_values($i)][format_values($j)]['Left'];
                        } catch (\Throwable $th) {
                            $productStock[format_values($i)][format_values($j)]['Right']=null;
                            $productStock[format_values($i)][format_values($j)]['Left']=null;
                        }
                    }
                }
            }

            // dd($sphere_max);

            return view('manager.stockLens.final-result',
            compact('productStock','lens_type','index','chromatics','coatings','sphere','sphere_max','sphere_min',
            'cylinder','cylinder_min','cylinder_max','lt','ix','chrm','ct','add','add_max','add_min'));
        }
    }
}
