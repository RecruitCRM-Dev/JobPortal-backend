<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployerAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $employer = Auth::user(); // Assuming employer is retrieved via authentication

        // dd($employer->id);

        // Retrieve the model (employer) from the request route
        $model = $request->route('employer');

        // dd($model->id);

        // Check if the authenticated employer is authorized to update the model
        if ($employer->id != $model->id) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
