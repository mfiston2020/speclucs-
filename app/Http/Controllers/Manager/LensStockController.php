<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Power;
use App\Models\Product;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

    // public function lensStockSearch(Request $request){
    //     return $this->productRepo->productMatrix($request->all());
    // }

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

        // dd($productListing);

        $this->validate($request,[
            'lens_type'=>'required',
            'index'=>'required',
            'chromatics'=>'required',
            'coating'=>'required',
        ]);

        // $power    =   Power::where('type_id',$request->lens_type)
        //                         ->where('index_id',$request->index)
        //                         ->where('chromatics_id',$request->chromatics)
        //                         ->where('company_id',$companyId)
        //                         ->where('coating_id',$request->coating)
        //                         ->get();
        // return $results;


        if ($productListing->isEmpty())
        {
            return redirect()->route('manager.lens.stock',0)->with('warningMsg','No Result Found This search')->withInput();
        }
        else
        {
            foreach($productListing as $result){

                if (initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())=='SV' && $result->power->index_id==$ix && $result->power->type_id==$lt && $result->power->chromatics_id==$chrm && $result->power->coating_id==$ct) {
                    // array to store all values of power separately
                    $sphere[] =   $result->power->sphere;
                    $cylinder[] =   $result->power->cylinder;

                    $productStock[format_values($result->power->sphere)][format_values($result->power->cylinder)]    = $result->stock;
                }

                elseif (initials($lens_type->where('id',$request->lens_type)->pluck('name')->first())=='BT' && $result->power->index_id==$ix && $result->power->type_id==$lt && $result->power->chromatics_id==$chrm && $result->power->coating_id==$ct) {
                    $add[] =   $result->power->add;
                    $cylinder[] =   $result->power->cylinder;
                    $sphere[] =   $result->power->sphere;

                    $productStock[format_values($result->power->sphere)][format_values($result->power->add)]    = $result->stock;
                }

                else {
                    $add[]      =   $result->power->add;
                    $eye[]      =   $result->power->eye;
                    $sphere[]   =   $result->power->sphere;
                    $cylinder[] =   $result->power->cylinder;

                    if ($result->power->eye=='Right' || $result->power->eye=='R') {
                        $productStock[format_values($result->power->sphere)][format_values($result->power->add)]['Right']    = $result->stock;
                    }

                    if ($result->power->eye=='Right' || $result->power->eye=='L') {
                        $productStock[format_values($result->power->sphere)][format_values($result->power->add)]['Left']    = $result->stock;
                    }

                }
            }

            // dd($productStock);

            $add_min =   min( array_unique($add) );
            $add_max =   max( array_unique($add) );

            $sphere_min =   min( array_unique($sphere) );
            $sphere_max =   max( array_unique($sphere) );

            $cylinder_min =   min( array_unique($cylinder) );
            $cylinder_max =   max( array_unique($cylinder) );

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

            return view('manager.stockLens.final-result',
            compact('productStock','lens_type','index','chromatics','coatings','sphere','sphere_max','sphere_min',
            'cylinder','cylinder_min','cylinder_max','lt','ix','chrm','ct','add','add_max','add_min'));
        }
    }






    // public function lensStockSearch(Request $request)
    // {
    //     $lens_type  =   \App\Models\LensType::all();
    //     $index  =   \App\Models\PhotoIndex::all();
    //     $chromatics  =   \App\Models\PhotoChromatics::all();
    //     $coatings  =   \App\Models\PhotoCoating::all();

    //     // ==========================================
    //     $sphere     =   array();
    //     $cylinder   =   array();
    //     $add        =   array();
    //     $my_array   =   array();



    //     $this->validate($request,[
    //         'lens_type'=>'required',
    //         'index'=>'required',
    //         'chromatics'=>'required',
    //         'coating'=>'required',
    //     ]);

    //     $results    =   DB::table('powers')->select('*')->where('type_id',$request->lens_type)
    //                                         ->where('index_id',$request->index)
    //                                         ->where('chromatics_id',$request->chromatics)
    //                                         ->where('company_id',companyId)
    //                                         ->where('coating_id',$request->coating)
    //                                         ->get();
    //         // return $results;


    //     if ($results->isEmpty())
    //     {
    //         return redirect()->route('manager.lens.stock',0)->with('warningMsg','No Result Found This search')->withInput();
    //     }
    //     else
    //     {
    //         foreach($results as $result){
    //             $sphere[] =   $result->sphere;
    //             $cylinder[] =   $result->cylinder;
    //             $add[] =   $result->add;
    //         }

    //         $sphere_min =   min(array_unique($sphere));
    //         $sphere_max =   max(array_unique($sphere));

    //         $cylinder_min =   min(array_unique($cylinder));
    //         $cylinder_max =   max(array_unique($cylinder));

    //         $add_min =   min(array_unique($add));
    //         $add_max =   max(array_unique($add));

    //         // ================ getting the value of seleected items ========
    //         $lt  =   $request->lens_type;
    //         $ix  =   $request->index;
    //         $chrm  =   $request->chromatics;
    //         $ct  =   $request->coating;

    //         $products_id_array   =   array();
    //         $type=\App\Models\LensType::where(['id'=>$lt])->pluck('name')->first();

    //         if (initials($type)!='SV')
    //         {
    //             for($i = $sphere_min; $i <= $sphere_max; $i=$i+0.25)
    //             {
    //                 for ($j = $add_min; $j <= $add_max; $j=$j+0.25)
    //                 {
    //                     $product_id=\App\Models\Power::where(['sphere'=>format_values($i)])
    //                                                                   ->where('type_id',$lt)
    //                                                                   ->where('index_id',$ix)
    //                                                                   ->where('chromatics_id',$chrm)
    //                                                                   ->where('coating_id',$ct)
    //                                                                   ->where('company_id',Auth::user()->company_id)
    //                                                                   ->where(['add'=>format_values($j)])
    //                                                                   ->select('product_id','sphere','add')->get();
    //                     foreach($product_id as $p_id)
    //                     {
    //                         // dd($p_id);
    //                         array_push($products_id_array,$p_id);
    //                     }
    //                 }
    //             }
    //         }

    //         // return $products_id_array;

    //         return view('manager.stockLens.results',
    //         compact('lens_type','index','chromatics','coatings','results','sphere','sphere_max','sphere_min',
    //         'cylinder','cylinder_min','cylinder_max','lt','ix','chrm','ct','add','add_max','add_min','products_id_array'));
    //     }
    // }
}
