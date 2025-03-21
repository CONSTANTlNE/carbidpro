<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {


        $user = $request->user();
        $currentRoute = $request->route();
        $currentRouteName = $currentRoute->getName();


        if ($user->hasRole('Broker') && !$user->hasAnyRole(['Admin', 'Developer']) && $currentRouteName !== 'car.showStatus') {
            return redirect()->route('car.showStatus',['slug' => 'for-dispatch']);
        }

        if ($user->hasRole('Loader') && !$user->hasAnyRole(['Admin', 'Developer']) && $currentRouteName !== 'container.showStatus') {
            return redirect()->route('container.showStatus',['slug' => 'for-dispatch']);
        }

        if ($user->hasRole('Terminal Agent') && !$user->hasAnyRole(['Admin', 'Developer']) && $currentRouteName !== 'arrived.index') {
            return redirect()->route('arrived.index');
        }

        return $next($request);
    }

}
