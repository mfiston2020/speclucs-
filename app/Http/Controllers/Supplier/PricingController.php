<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class PricingController extends Controller
{
    public function index()
    {
        $pricings   =   \App\Models\Pricing::where('company_id',Auth::user()->company_id)->get();
        return view('supplier.pricing.index',compact('pricings'));
    }

    public function add()
    {
        $categories =   \App\Models\Category::all();
        $lens_types =   \App\Models\LensType::all();
        $chromatics =   \App\Models\PhotoChromatics::all();
        $coatings   =   \App\Models\PhotoCoating::all();
        $index      =   \App\Models\PhotoIndex::all();

        return view('supplier.pricing.create',compact('categories','lens_types','chromatics','coatings','index'));
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'lens_type'=>'required',
            'index'=>'required',
            'chromatics'=>'required',
            'coating'=>'required',
            'sphere_min'=>'required',
            'sphere_max'=>'required',
            'cylinder_min'=>'required',
            'cylinder_max'=>'required',
            'axis_min'=>'required',
            'axis_max'=>'required',
            'add_min'=>'required',
            'add_max'=>'required',
            'lens_price'=>'required',
            'eye'=>'required',
        ]);

        $pricing    =   new \App\Models\Pricing();

        $pricing->company_id    =   Auth::user()->company_id;
        $pricing->category_id   =   $request->category;
        $pricing->user_id       =   Auth::user()->id;
        $pricing->type_id       =   $request->lens_type;
        $pricing->index_id      =   $request->index;
        $pricing->chromatics_id =   $request->chromatics;
        $pricing->coating_id    =   $request->coating;
        $pricing->sphere_min    =   $request->sphere_min;
        $pricing->sphere_max    =   $request->sphere_max;
        $pricing->cylinder_min  =   $request->cylinder_min;
        $pricing->cylinder_max  =   $request->cylinder_max;
        $pricing->axis_min      =   $request->axis_min;
        $pricing->axis_max      =   $request->axis_max;
        $pricing->add_min       =   $request->add_min;
        $pricing->add_max       =   $request->add_max;
        $pricing->eye           =   $request->eye;
        $pricing->price         =   $request->lens_price;

        try {
            $pricing->save();
            return redirect()->route('supplier.pricing.index')->with('successMsg','Product Tarrif Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
