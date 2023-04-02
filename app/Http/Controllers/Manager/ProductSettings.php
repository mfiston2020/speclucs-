<?php

namespace App\Http\Controllers\Manager;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductSettings extends Controller
{
    public function index()
    {
        $indexes    =   \App\Models\PhotoIndex::where('company_id',Auth::user()->company_id)->get();
        $chromatics    =   \App\Models\PhotoChromatics::where('company_id',Auth::user()->company_id)->get();
        $coatings    =   \App\Models\PhotoCoating::where('company_id',Auth::user()->company_id)->get();
        $types    =   \App\Models\LensType::where('company_id',Auth::user()->company_id)->get();

        return view('manager.productSettings.index',compact('indexes','chromatics','coatings','types'));
    }

    public function saveLensType(Request $request)
    {
        $this->validate($request,[
            'lens_type'=>'required',
        ]);

        $lensType   =   new \App\Models\LensType();
        $lensType->company_id   =   Auth::user()->company_id;
        try {
            $lensType->save();
            return redirect()->back()->with('successMsg','Lens Type Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function saveLensCoating(Request $request)
    {
        $this->validate($request,[
            'coating'=>'required',
        ]);

        $coating   =   new \App\Models\PhotoCoating();
        $coating->name =   $request->coating;
        $coating->company_id   =   Auth::user()->company_id;
        try {
            $coating->save();
            return redirect()->back()->with('successMsg','Coating Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function saveLensIndexes(Request $request)
    {
        $this->validate($request,[
            'index'=>'required',
        ]);

        $index   =   new \App\Models\Photoindex();
        $index->name =   $request->index;
        $index->company_id   =   Auth::user()->company_id;
        try {
            $index->save();
            return redirect()->back()->with('successMsg','index Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    public function saveLensPhotoChromatics(Request $request)
    {
        $this->validate($request,[
            'chromatics'=>'required',
        ]);

        $chromatics   =   new \App\Models\PhotoChromatics();
        $chromatics->name =   $request->chromatics;
        $chromatics->company_id   =   Auth::user()->company_id;
        try {
            $chromatics->save();
            return redirect()->back()->with('successMsg','chromatics Asspect Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }
}
