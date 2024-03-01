<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // dd($request->route('user'));

        // Retrieve the model (user) from the request route
        $model = $request->route('user');
        // dd($model);

        // dd($model->id);

        // Check if the authenticated user is authorized to update the model
        if ($user->id != $model->id) {
            abort(403, 'Unauthorized action');
        }
        return $next($request);
    }
}
