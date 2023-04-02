<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateContent extends Controller
{
    public function updateStock(Request $request)
    {
        $id         =   $request->get('pk');
        $name       =   $request->input('name');
        $value      =   $request->input('value');

        $product     =   \App\Models\Product::find($id);

        if($name=='stock')
        {
            $product->stock     =   $value;
            $product->save();
            return 'successfully saved';   
        }
    }
}
