<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdatingController extends Controller
{
    public function updateProductSettings(Request $request)
    {
        $id         =   $request->get('pk');
        $name       =   $request->input('name');
        $value      =   $request->input('value');

        if($name=='type')
        {
            $product        =   \App\Models\LensType::find($id);
            $product->name  =   $value;
            $product->save();
            return 'successfully saved';   
        }

        if($name=='chromatics')
        {
            $product        =   \App\Models\PhotoChromatics::find($id);
            $product->name  =   $value;
            $product->save();
            return 'successfully saved';   
        }

        if($name=='coating')
        {
            $product        =   \App\Models\PhotoCoating::find($id);
            $product->name  =   $value;
            $product->save();
            return 'successfully saved';   
        }

        if($name=='index')
        {
            $product        =   \App\Models\PhotoIndex::find($id);
            $product->name  =   $value;
            $product->save();
            return 'successfully saved';   
        }
    }
}
