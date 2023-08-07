<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\Power;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    public function index()
    {
        $products =   \App\Models\Product::where('company_id', Auth::user()->company_id)->get();
        return view('manager.product.index', compact('products'));
    }

    public function create()
    {
        $categories =   \App\Models\Category::all();
        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index      =   \App\Models\PhotoIndex::all();
        $suppliers  =   Supplier::where('company_id', userInfo()->company_id)->get();

        return view('manager.product.create', compact('categories', 'lens_types', 'chromatics', 'coatings', 'index', 'suppliers'));
    }

    public function save(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'category' => 'required',
        ]);

        if ($request->category == '1' && $request->category != null) {
            // ============================================
            for ($i = 0; $i < count($request->sphere); $i++) {
                $product    =   new \App\Models\Product();
                $lens_type  =   \App\Models\LensType::find($request->lens_type);
                $indx       =   \App\Models\PhotoIndex::find($request->index);
                $chro       =   \App\Models\PhotoChromatics::find($request->chromatics);
                $coat       =   \App\Models\PhotoCoating::find($request->coating);

                $description    =   initials($lens_type->name) . " " . $indx->name . " " . $chro->name . " " . $coat->name;

                // checking the existance of the product
                $product_exists =   Power::where('type_id', $request->lens_type)
                    ->where('index_id', $request->index)
                    ->where('chromatics_id', $request->chromatics)
                    ->where('coating_id', $request->coating)
                    ->where('sphere', format_values($request->sphere[$i]))
                    ->where('cylinder', format_values($request->cylinder[$i]))
                    ->where('axis', format_values($request->axis[$i]))
                    ->where('add', format_values($request->add[$i]))
                    ->where('eye', initials($lens_type->name) == 'SV' ? 'any' : $request->eye[$i])->first();

                $prdt   =   $product_exists == null ? null : Product::where('id', $product_exists->id)->first();

                if ($product_exists && $prdt->cost == $request->lens_cost[$i]) {
                    return redirect()->route('manager.product')->with('warningMsg', 'Product already exists');
                } else {
                    if (initials($lens_type->name) == 'SV') {
                        $product->category_id       =   $request->category;
                        $product->supplier_id       =   $request->supplier;
                        $product->product_name      =   $lens_type->name;
                        $product->description       =   $description;
                        $product->stock             =   $request->lens_stock[$i];
                        $product->deffective_stock  =   '0';
                        $product->price             =   $request->lens_price[$i];
                        $product->cost              =   $request->lens_cost[$i];
                        $product->fitting_cost      =   $request->fitting_cost[$i];
                        $product->location          =   $request->location[$i];
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
                            return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
                        }
                    } else {

                        $product->category_id       =   $request->category;
                        $product->product_name      =   $lens_type->name;
                        $product->description       =   $description . ' - ' . $request->eye[$i];;
                        $product->stock             =   $request->lens_stock[$i];
                        $product->deffective_stock  =   '0';
                        $product->price             =   $request->lens_price[$i];
                        $product->cost              =   $request->lens_cost[$i];
                        $product->fitting_cost      =   $request->fitting_cost[$i];
                        $product->company_id        =   Auth::user()->company_id;

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
                    }
                }
            }
            return redirect()->route('manager.product')->with('successMsg', 'Product Created Successfully');
        } else {
            // return 'hello';
            $this->validate($request, [
                'product_name' => 'required | min:3',
                'product_description' => 'required | min:3',
                'stock' => 'required | integer',
                'defective_stock' => 'required | integer',
                'price' => 'required | integer',
                'cost' => 'required | integer',
            ]);

            $product    =   new \App\Models\Product();

            $product->category_id       =   $request->category;
            $product->product_name      =   $request->product_name;
            $product->description       =   $request->product_description;
            $product->stock             =   $request->stock;
            $product->deffective_stock  =   $request->defective_stock;
            $product->price             =   $request->price;
            $product->cost              =   $request->cost;
            $product->location          =   $request->location;
            $product->supplier_id       =   $request->supplier;
            $product->company_id        =   Auth::user()->company_id;

            try {
                $product->save();
                return redirect()->route('manager.product')->with('successMsg', 'Product Created Successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        }
    }

    function importProduct()
    {
        return view('manager.product.import');
    }

    function saveImport(Request $request)
    {
        $this->validate($request, [
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ProductImport(), $request->excelFile);

            $count  =   session('countSkippedImport');

            if ($count > 0) {
                return redirect()->route('manager.product')->with('successMsg', 'Importing successful skipped ' . $count . ' Duplicates');
            } else {
                return redirect()->route('manager.product')->with('successMsg', 'Importing successful');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Oops! something Went Wrong!');
        }
    }
}
