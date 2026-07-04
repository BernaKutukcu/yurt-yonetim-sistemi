<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        //Giriş yapmamışsa login'e yönlendir
        if(!Auth::check())
        {
            return redirect('/login');
        }

        //Rolü uyuşmuyorsa login'e yönlendir
        if(Auth::user()->role !== $role)
        {
            return redirect('/login');
        }

        return $next($request);
    }
}
