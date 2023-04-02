<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DB;
use Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductSettingsController extends Controller
{
    public function index()
    {
        $indexes    =   \App\Models\PhotoIndex::all();
        $chromatics    =   \App\Models\PhotoChromatics::all();
        $coatings    =   \App\Models\PhotoCoating::all();
        $types    =   \App\Models\LensType::all();

        return view('admin.productSettings.index',compact('indexes','chromatics','coatings','types'));
    }

    public function saveLensType(Request $request)
    {
        $this->validate($request,[
            'lens_type'=>'required',
        ]);

        $lensType   =   new \App\Models\LensType();

        $lensType->name   =  $request->lens_type;
        // $lensType->company_id   =   Auth::user()->company_id;

        try {
            $lensType->save();
            return redirect()->back()->with('successMsg','Lens Type Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! '.$th);
        }
    }

    public function saveLensCoating(Request $request)
    {
        $this->validate($request,[
            'coating'=>'required',
        ]);

        $coating   =   new \App\Models\PhotoCoating();
        $coating->name =   $request->coating;
        // $coating->company_id   =   Auth::user()->company_id;
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

        $index   =   new \App\Models\PhotoIndex();
        $index->name =   $request->index;
        // $index->company_id   =   Auth::user()->company_id;
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
        // $chromatics->company_id   =   Auth::user()->company_id;
        try {
            $chromatics->save();
            return redirect()->back()->with('successMsg','chromatics Asspect Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong! ');
        }
    }

    

    public function deleteType($id)
    {
        $detail =   \App\Models\LensType::find(Crypt::decrypt($id));
        try {
            $detail->delete();
            return redirect()->back()->with('successMsg','Lens Type Successfully Removed');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!'.$th);
        }
    }

    public function deletechromatics($id)
    {
        $detail =   \App\Models\PhotoChromatics::find(Crypt::decrypt($id));
        try {
            $detail->delete();
            return redirect()->back()->with('successMsg','Lens Chromatics Successfully Removed');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!'.$th);
        }
    }

    public function deleteCoating($id)
    {
        $detail =   \App\Models\PhotoCoating::find(Crypt::decrypt($id));
        try {
            $detail->delete();
            return redirect()->back()->with('successMsg','Lens coaring Successfully Removed');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!'.$th);
        }
    }

    public function deleteindex($id)
    {
        $detail =   \App\Models\PhotoIndex::find(Crypt::decrypt($id));
        try {
            $detail->delete();
            return redirect()->back()->with('successMsg','Lens Index Successfully Removed');
        } 
        catch (\Throwable $th) 
        {
            return redirect()->back()->withInput()->with('errorMsg','Sorry Something Went Wrong!'.$th);
        }
    }
}
