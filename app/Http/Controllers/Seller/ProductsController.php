<?php

namespace App\Http\Controllers\Seller;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products =   \App\Models\Product::where('company_id',Auth::user()->company_id)->get();
        return view('seller.product.index',compact('products'));
    }

    public function create()
    {
        $categories =   \App\Models\Category::all();
        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index      =   \App\Models\PhotoIndex::all();

        return view('seller.product.create',compact('categories','lens_types','chromatics','coatings','index'));
    }

    public function save(Request $request)
    {
        // return $request->all();
        
        $this->validate($request,[
            'category'=>'required',
        ]);

        if ($request->category=='1' && $request->category!=null) 
        {
            // ============================================
            for ($i=0; $i < count($request->sphere); $i++) 
            { 
                $product    =   new \App\Models\Product();
                $lens_type  =   \App\Models\LensType::find($request->lens_type);
                $indx       =   \App\Models\PhotoIndex::find($request->index);
                $chro       =   \App\Models\PhotoChromatics::find($request->chromatics);
                $coat       =   \App\Models\PhotoCoating::find($request->coating);

                if (initials($lens_type->name)=='SV') 
                {
                    // $this->validate($request,[
                    //     'lens_type'=>'required',
                    //     'index'=>'required',
                    //     'chromatics'=>'required',
                    //     'coating'=>'required',
                    //     // ======================
                    //     'sphere'=>'required',                
                    //     'cylinder'=>'required',                   
                    //     // ======================
                    //     'lens_stock'=>'required | integer',
                    //     'lens_price'=>'required | integer',
                    //     'lens_cost'=>'required | integer',
                    // ]);
                    $description    =   initials($lens_type->name)." ".$indx->name." ".$chro->name." ".$coat->name;
        
                    $product->category_id       =   $request->category;
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description;
                    $product->stock             =   $request->lens_stock[$i];
                    $product->deffective_stock  =   '0';
                    $product->price             =   $request->lens_price[$i];
                    $product->cost              =   $request->lens_cost[$i];
                    $product->company_id        =   Auth::user()->company_id;
                    try {
                        $product->save();

                        $power                    =   new \App\Models\Power();
                        $power->product_id        =   $product->id;
                        $power->type_id           =   $request->lens_type;
                        $power->index_id          =   $request->index;
                        $power->chromatics_id     =   $request->chromatics;
                        $power->coating_id        =   $request->coating;
                        $power->sphere            =   format_values($request->sphere[$i]);
                        $power->cylinder          =   format_values($request->cylinder[$i]);
                        $power->axis              =   format_values($request->axis[$i]);
                        $power->add               =   format_values($request->add[$i]);
                        $power->eye               =   'any';
                        $power->company_id        =   Auth::user()->company_id;
                        $power->save();
                    } catch (\Throwable $th) {
                        return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
                    }
                }
                else
                {
                    // $this->validate($request,[
                    //     'lens_type'=>'required',
                    //     'index'=>'required',
                    //     'chromatics'=>'required',
                    //     'coating'=>'required',
                    //     // ======================
                    //     'sphere'=>'required',                
                    //     'cylinder'=>'required',                
                    //     'axis'=>'required',                
                    //     'add'=>'required',                
                    //     'eye'=>'required',                
                    //     // ======================
                    //     'lens_stock'=>'required | integer',
                    //     'lens_price'=>'required | integer',
                    //     'lens_cost'=>'required | integer',
                    // ]);
                    $description    =   initials($lens_type->name)." ".$indx->name." ".$chro->name." ".$coat->name;
        
                    $product->category_id       =   $request->category;
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description.' - '.$request->eye[$i];;
                    $product->stock             =   $request->lens_stock[$i];
                    $product->deffective_stock  =   '0';
                    $product->price             =   $request->lens_price[$i];
                    $product->cost              =   $request->lens_cost[$i];
                    $product->company_id        =   Auth::user()->company_id;
                    // try {
                        $product->save();

                        $power  =   new \App\Models\Power();
                        $power->product_id        =   $product->id;
                        $power->type_id           =   $request->lens_type;
                        $power->index_id          =   $request->index;
                        $power->chromatics_id     =   $request->chromatics;
                        $power->coating_id        =   $request->coating;
                        $power->sphere            =   format_values($request->sphere[$i]);
                        $power->cylinder          =   format_values($request->cylinder[$i]);
                        $power->axis              =   format_values($request->axis[$i]);
                        $power->add               =   format_values($request->add[$i]);
                        $power->eye               =   $request->eye[$i];
                        $power->company_id        =   Auth::user()->company_id;
                        $power->save();
                        // return redirect()->route('seller.product')->with('successMsg','Product Created Successfully');
                    // } catch (\Throwable $th) {
                    //     return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
                    // }
                }
                
            }
            // return $hello;
            return redirect()->route('seller.product')->with('successMsg','Product Created Successfully');
        } 
        else 
        {
            // return 'hello';
            $this->validate($request,[
                'product_name'=>'required | min:3',
                'product_description'=>'required | min:3',
                'stock'=>'required | integer',
                'defective_stock'=>'required | integer',
                'price'=>'required | integer',
                'cost'=>'required | integer',
            ]);
    
            $product    =   new \App\Models\Product();
    
            $product->category_id       =   $request->category;
            $product->product_name      =   $request->product_name;
            $product->description       =   $request->product_description;
            $product->stock             =   $request->stock;
            $product->deffective_stock  =   $request->defective_stock;
            $product->price             =   $request->price;
            $product->cost              =   $request->cost;
            $product->company_id        =   Auth::user()->company_id;
    
            try {
                $product->save();
                return redirect()->route('seller.product')->with('successMsg','Product Created Successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
            }
        }
    }

    public function export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }
}
