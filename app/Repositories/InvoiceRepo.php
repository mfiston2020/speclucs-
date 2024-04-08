<?php

namespace App\Repositories;

use App\Interface\InvoiceInterface;
use App\Models\Invoice;

class InvoiceRepo implements InvoiceInterface
{
    function internalOrder(string $status,string $available='yes')
    {
        if ($available=='na') {

            if (getuserCompanyInfo()->is_vision_center=='1') {
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->where('status',$status)
                                            ->whereNull('supplier_id')
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })
                                            ->orderBy('id','desc')
                                            ->paginate(50);
            }else{
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->where('status',$status)
                                            ->whereNull('supplier_id')
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })
                                            ->orderBy('id','desc')
                                            ->paginate(50);
            }
        }else{
            if (getuserCompanyInfo()->is_vision_center=='1') {
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->where('status',$status)
                                            ->whereNull('supplier_id')
                                            ->with('soldproduct',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })
                                            ->orderBy('id','desc')
                                            ->whereDoesntHave('unavailableProducts')->get();
            }else{
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->where('status',$status)
                                            ->whereNull('supplier_id')
                                            ->with('soldproduct',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })
                                            ->orderBy('id','desc')
                                            ->whereDoesntHave('unavailableProducts')->get();
            }
        }
    }

    function externalOrder(string $status,string $available='yes'){
        if ($available=='na') {
            if (getuserCompanyInfo()->is_vision_center=='1') {
                return Invoice::whereNotNull('supplier_id')
                                            ->where('status',$status)
                                            ->where('company_id',userInfo()->company_id)
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })
                                            ->orderBy('id','desc')
                                            ->paginate(50);
            } else {
                return Invoice::where('supplier_id', userInfo()->company_id)
                                            ->where('status',$status)
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })
                                            ->orderBy('id','desc')
                                            ->paginate(50);
            }
        }else{
            if (getuserCompanyInfo()->is_vision_center=='1') {
                    return Invoice::whereNotNull('supplier_id')
                                                ->where('status',$status)
                                                ->where('company_id',userInfo()->company_id)
                                                ->with('soldproduct',function($query){
                                                    $query->with('product',function($q){
                                                        $q->with(['power','category']);
                                                    });
                                                })
                                                ->orderBy('id','desc')
                                                ->whereDoesntHave('unavailableProducts')->get();
                } else {
                    return Invoice::where('supplier_id', userInfo()->company_id)
                                                ->where('status',$status)
                                                ->with('soldproduct',function($query){
                                                    $query->with('product',function($q){
                                                        $q->with(['power','category']);
                                                    });
                                                })
                                                ->orderBy('id','desc')
                                                ->whereDoesntHave('unavailableProducts')->get();
                }
            }
        }
}
