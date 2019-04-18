<?php

namespace App\Http\Middleware;

use Closure;

class corsglobal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        return $next($request)
        ->header('Access-Control-Allow-Origin','*')
        ->header('Access-Control-Allow-Headers','Content-Type, Authorization');
        //->header('Access-Control-Allow-Methods','GET, POST, PUT, OPTION');
    }
}
