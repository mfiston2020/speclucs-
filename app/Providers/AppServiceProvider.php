<?php

namespace App\Providers;

use App\Models\UnavailableProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        view()->composer('manager.includes.layouts.header', function ($view) {
            $count_notification     =   count(\App\Models\SupplierNotify::where('supplier_id', Auth::user()->company_id)->where('status', '0')->get());
            $notifications           =   \App\Models\SupplierNotify::where('supplier_id', Auth::user()->company_id)->where('status', '0')->orderBy('created_at', 'desc')->get();

            $pendingProducts    =   UnavailableProduct::where('company_id',userInfo()->company_id)->where('status','pending')->count();

            $view->with('count_notification', $count_notification)
                ->with('notifications', $notifications)
                ->with('pending_product_on_invoice',$pendingProducts);
        });
    }
}
