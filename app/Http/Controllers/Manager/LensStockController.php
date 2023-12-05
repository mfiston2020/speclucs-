<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
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
        $lens_type  =   \App\Models\LensType::all();
        $index  =   \App\Models\PhotoIndex::all();
        $chromatics  =   \App\Models\PhotoChromatics::all();
        $coatings  =   \App\Models\PhotoCoating::all();

        // ==========================================
        $sphere     =   array();
        $cylinder   =   array();
        $add        =   array();
        $my_array   =   array();



        $this->validate($request,[
            'lens_type'=>'required',
            'index'=>'required',
            'chromatics'=>'required',
            'coating'=>'required',
        ]);

        $results    =   DB::table('powers')->select('*')->where('type_id',$request->lens_type)
                                            ->where('index_id',$request->index)
                                            ->where('chromatics_id',$request->chromatics)
                                            ->where('company_id',Auth::user()->company_id)
                                            ->where('coating_id',$request->coating)
                                            ->get();
            // return $results;


        if ($results->isEmpty())
        {
            return redirect()->route('manager.lens.stock',0)->with('warningMsg','No Result Found This search')->withInput();
        }
        else
        {
            foreach($results as $result){
                $sphere[] =   $result->sphere;
                $cylinder[] =   $result->cylinder;
                $add[] =   $result->add;
            }

            $sphere_min =   min(array_unique($sphere));
            $sphere_max =   max(array_unique($sphere));

            $cylinder_min =   min(array_unique($cylinder));
            $cylinder_max =   max(array_unique($cylinder));

            $add_min =   min(array_unique($add));
            $add_max =   max(array_unique($add));

            // ================ getting the value of seleected items ========
            $lt  =   $request->lens_type;
            $ix  =   $request->index;
            $chrm  =   $request->chromatics;
            $ct  =   $request->coating;

            $products_id_array_   =   array();
            $products_id_array   =   array();
            $type=\App\Models\LensType::where(['id'=>$lt])->pluck('name')->first();

            if (initials($type)!='SV')
            {
                // dd('not sv');
                    for ($j = $add_min; $j <= $add_max; $j=$j+0.25)
                {
                    for($i = $sphere_min; $i <= $sphere_max; $i=$i+0.25)
                    // for ($j = $add_min; $j <= $add_max; $j=$j+0.25)
                    {
                        $product_id=\App\Models\Power::where('type_id',$lt)
                                                        ->where('index_id',$ix)
                                                        ->where('chromatics_id',$chrm)
                                                        ->where('coating_id',$ct)
                                                        ->where('company_id',Auth::user()->company_id)
                                                        ->where(['add'=>format_values($j)])
                                                        ->where(['sphere'=>format_values($i)])
                                                        ->select('product_id','sphere','add','eye','cylinder')
                                                        ->get();



                        foreach($product_id as $p_id)
                        {
                            array_push($products_id_array,$p_id);

                            $stock    =   0;
                            $p1 =    Product::where('id',$p_id->product_id)->select('*')->sum('stock');

                            $stock+=$p1;
                            array_push($products_id_array_,[
                                'sphere'=>$p_id->sphere,
                                'add'=>$p_id->add,
                                'add'=>$p_id->add,
                                'cylinder'=>$p_id->cylinder,
                                'eye'=>$p_id->eye,
                                'stock'=>$stock
                            ]);
                        }
                    }
                }
            }

            // dd($products_id_array_[10]);

            return view('manager.stockLens.final-result',
            compact('lens_type','index','chromatics','coatings','results','sphere','sphere_max','sphere_min',
            'cylinder','cylinder_min','cylinder_max','lt','ix','chrm','ct','add','add_max','add_min','products_id_array','products_id_array_'));
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
    //                                         ->where('company_id',Auth::user()->company_id)
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
