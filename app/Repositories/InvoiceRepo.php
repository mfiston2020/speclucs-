<?php

namespace App\Repositories;

use App\Interface\InvoiceInterface;
use App\Models\Invoice;

class InvoiceRepo implements InvoiceInterface
{
    function internalOrder(array $status,string $available='yes')
    {
        if ($available=='na') {

            if (getuserCompanyInfo()->is_vision_center=='1') {
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->whereIn('status',$status)
                                            ->whereNull('supplier_id')
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with(['uindex','coating','uchromatic','type','product'=>function($q){
                                                    $q->with(['power','category']);
                                                }]);
                                            })
                                            ->orderBy('created_at','desc')
                                            ->get();
            }else{
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->whereIn('status',$status)
                                            ->whereNull('supplier_id')
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with(['uindex','coating','uchromatic','type','product'=>function($q){
                                                    $q->with(['power','category']);
                                                }]);
                                            })
                                            ->orderBy('created_at','desc')
                                            ->get();
            }
        }else{
            if (getuserCompanyInfo()->is_vision_center=='1') {
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->whereIn('status',$status)
                                            ->whereNull('supplier_id')
                                            ->with('unavailableProducts',function($query){
                                                $query->with(['uindex','coating','uchromatic','type','product'=>function($q){
                                                    $q->with(['power','category']);
                                                }]);
                                            })
                                            ->with('soldproduct',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })->orderBy('created_at','desc')->get();
            }else{
                return Invoice::where('company_id', userInfo()->company_id)
                                            ->whereIn('status',$status)
                                            ->with('unavailableProducts',function($query){
                                                $query->with(['uindex','coating','uchromatic','type','product'=>function($q){
                                                    $q->with(['power','category']);
                                                }]);
                                            })
                                            ->with('soldproduct',function($query){
                                                $query->with('product',function($q){
                                                    $q->with(['power','category']);
                                                });
                                            })->orderBy('created_at','desc')->get();
            }
        }
    }

    function externalOrder(array $status,string $available='yes'){
        if ($available=='na') {
            if (getuserCompanyInfo()->is_vision_center=='1') {
                return Invoice::whereNotNull('supplier_id')
                                            ->whereIn('status',$status)
                                            ->where('company_id',userInfo()->company_id)
                                            ->without('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with(['uindex','coating','uchromatic','type','product'=>function($q){
                                                    $q->with(['power','category']);
                                                }]);
                                            })
                                            ->orderBy('created_at','desc')
                                            ->get();
            } else {
                return Invoice::where('supplier_id', userInfo()->company_id)
                                            ->whereIn('status',$status)
                                            ->with('soldproduct')
                                            ->with('unavailableProducts',function($query){
                                                $query->with(['uindex','coating','uchromatic','type','product'=>function($q){
                                                    $q->with(['power','category']);
                                                }]);
                                            })
                                            ->orderBy('created_at','desc')
                                            ->get();
            }
        }else{
            if (getuserCompanyInfo()->is_vision_center=='1') {
                    return Invoice::whereNotNull('supplier_id')
                                                ->whereIn('status',$status)
                                                ->where('company_id',userInfo()->company_id)
                                                ->with('soldproduct',function($query){
                                                    $query->with('product',function($q){
                                                        $q->with(['power','category']);
                                                    });
                                                })
                                                ->orderBy('created_at','desc')->get();
                } else {
                    return Invoice::where('supplier_id', userInfo()->company_id)
                                                ->whereIn('status',$status)
                                                ->with('soldproduct',function($query){
                                                    $query->with('product',function($q){
                                                        $q->with(['power','category']);
                                                    });
                                                })
                                                ->orderBy('created_at','desc')->get();
                }
            }
        }
}
