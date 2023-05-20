<?php

namespace App\Repositories;

use App\Interface\ProductInterface;
use App\Models\LensType;
use App\Models\Power;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductRepo implements ProductInterface{

    public String $message  =   '';
    public bool $showProductDetails  =   false;
    public $product =   [];

    function searchProduct(string $lensType, string $index, string $chromatic, string $coating, string $sphere, string $cylinder, string $eye,string $axis,string $add)
    {}

    function saveProduct(array $request)
    {
        dd($request['patient_number']);

        if ($request['category']=='1' && $request['category']!=null)
        {
            // ============================================
            $product    =   new \App\Models\Product();

            $lens_type  =   \App\Models\LensType::find($request['lens_type']);
            $indx       =   \App\Models\PhotoIndex::find($request['index']);
            $chro       =   \App\Models\PhotoChromatics::find($request['chromatics']);
            $coat       =   \App\Models\PhotoCoating::find($request['coating']);

            $description    =   initials($lens_type['name'])." ".$indx['name']." ".$chro['name']." ".$coat['name'];

            // checking the existance of the product
            $product_exists =   Power::where('type_id',$request['lens_type'])
                                        ->where('index_id',$request['index'])
                                        ->where('chromatics_id',$request['chromatics'])
                                        ->where('coating_id',$request['coating'])
                                        ->where('sphere',format_values($request['sphere']))
                                        ->where('cylinder',format_values($request['cylinder']))
                                        ->where('axis',format_values($request['axis']))
                                        ->where('add',format_values($request['add']))
                                        ->where('eye',initials($lens_type->name)=='SV'?'any':$request['eye'])->first();


            $prdt   =   $product_exists==null?null:Product::where('id',$product_exists->id)->first();

            if ($product_exists && $prdt->cost==$request['lens_cost'])
            {
                return redirect()->route('manager.product')->with('warningMsg','Product already exists');
            }
            else{
                if (initials($lens_type->name)=='SV')
                {
                    $product->category_id       =   $request['category'];
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description;
                    $product->stock             =   $request['lens_stock'];
                    $product->deffective_stock  =   '0';
                    $product->price             =   $request['lens_price'];
                    $product->cost              =   $request['lens_cost'];
                    $product->fitting_cost      =   $request['fitting_cost'];
                    $product->company_id        =   Auth::user()->company_id;
                    try {
                        $product->save();

                        $power                    =   new \App\Models\Power();
                        $power->product_id        =   $product->id;
                        $power->type_id           =   $request['lens_type'];
                        $power->index_id          =   $request['index'];
                        $power->chromatics_id     =   $request['chromatics'];
                        $power->coating_id        =   $request['coating'];
                        $power->sphere            =   format_values($request['sphere']);
                        $power->cylinder          =   format_values($request['cylinder']);
                        $power->axis              =   format_values($request['axis']);
                        $power->add               =   format_values($request['add']);
                        $power->eye               =   'any';
                        $power->company_id        =   Auth::user()->company_id;
                        $power->save();
                    } catch (\Throwable $th) {
                        return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
                    }
                }
                else
                {

                    $product->category_id       =   $request['category'];
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description.' - '.$request['eye'];;
                    $product->stock             =   $request['lens_stock'];
                    $product->deffective_stock  =   '0';
                    $product->price             =   $request['lens_price'];
                    $product->cost              =   $request['lens_cost'];
                    $product->fitting_cost      =   $request['fitting_cost'];
                    $product->company_id        =   Auth::user()->company_id;

                    $product->save();

                    $power  =   new \App\Models\Power();
                    $power->product_id        =   $product->id;
                    $power->type_id           =   $request['lens_type'];
                    $power->index_id          =   $request['index'];
                    $power->chromatics_id     =   $request['chromatics'];
                    $power->coating_id        =   $request['coating'];
                    $power->sphere            =   format_values($request['sphere']);
                    $power->cylinder          =   format_values($request['cylinder']);
                    $power->axis              =   format_values($request['axis']);
                    $power->add               =   format_values($request['add']);
                    $power->eye               =   $request['eye'];
                    $power->company_id        =   Auth::user()->company_id;
                    $power->save();
                }
            }
            return redirect()->route('manager.product')->with('successMsg','Product Created Successfully');
        }
    }
}
