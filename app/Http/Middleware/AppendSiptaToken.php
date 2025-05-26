<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

class AppendSiptaToken
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Add the token as a default query parameter for URL generation
        if (Session::has('sipta_token')) {
            URL::defaults(['token' => Session::get('sipta_token')]);
        }
        
        return $response;
    }
}