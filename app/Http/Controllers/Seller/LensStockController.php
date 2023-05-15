<?php

namespace App\Http\Controllers\Seller;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LensStockController extends Controller
{
    public function index($data)
    {
        $lens_type  =   \App\Models\LensType::all();
        $index  =   \App\Models\PhotoIndex::all();
        $chromatics  =   \App\Models\PhotoChromatics::all();
        $coatings  =   \App\Models\PhotoCoating::all();
        $results    =   $data;

        return view('seller.stockLens.index',compact('lens_type','index','chromatics','coatings','results'));
    }

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
            return redirect()->route('seller.lens.stock',0)->with('warningMsg','No Result Found This search')->withInput();
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

            $products_id_array   =   array();
            $type=\App\Models\LensType::where(['id'=>$lt])->pluck('name')->first();

            if (initials($type)!='SV') 
            {
                for($i = $sphere_min; $i <= $sphere_max; $i=$i+0.25)
                {
                    for ($j = $add_min; $j <= $add_max; $j=$j+0.25)
                    {
                        $product_id=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                      ->where('type_id',$lt)
                                                                      ->where('index_id',$ix)
                                                                      ->where('chromatics_id',$chrm)
                                                                      ->where('coating_id',$ct)
                                                                      ->where('company_id',Auth::user()->company_id)
                                                                      ->where(['add'=>format_values($j)])
                                                                      ->select('product_id','sphere','add')->get();
                        foreach($product_id as $p_id)
                        {
                            array_push($products_id_array,$p_id);
                        }
                    }
                }
            }

            // return $products_id_array;

            return view('seller.stockLens.results',
            compact('lens_type','index','chromatics','coatings','results','sphere','sphere_max','sphere_min',
            'cylinder','cylinder_min','cylinder_max','lt','ix','chrm','ct','add','add_max','add_min','products_id_array'));
        }
    }
}
