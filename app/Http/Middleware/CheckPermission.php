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
        $currentRouteName = $currentRoute ? $currentRoute->getName() : null;

        if ($user->hasRole('Dispatch') && !$user->hasRole('Admin') && $currentRouteName !== 'car.showStatus') {
            return redirect()->route('car.showStatus',['slug' => 'for-dispatch']);
        }

        if ($user->hasRole('Loader') && !$user->hasRole('Admin') && $currentRouteName !== 'container.showStatus') {
            return redirect()->route('container.showStatus',['slug' => 'for-dispatch']);
        }

        if ($user->hasRole('Terminal Agent') && !$user->hasRole('Admin') && $currentRouteName !== 'arrived.index') {
            return redirect()->route('arrived.index');
        }

        return $next($request);
    }

}
