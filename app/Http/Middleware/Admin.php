<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->check()) {
            return redirect(route('login'));
        }
        if(Auth::user()->role=='admin')
        {
            return $next($request);
        }
        if(Auth::user()->role == 'client')
        {
            return redirect()->route('client');
        }
        if(Auth::user()->role == 'supplier')
        {
            return redirect()->route('supplier');
        }
        if(Auth::user()->role == 'manager')
        {
            return redirect()->route('manager');
        }
        if(Auth::user()->role == 'seller')
        {
            return redirect()->route('seller');
        }
    }
}
