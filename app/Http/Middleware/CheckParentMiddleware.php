<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckParentMiddleware
{

//            parent=3

    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Get the authenticated user's role ID
        $roleId = Auth::user()->role;

        // Check if the user is an admin (assuming admin role ID is 1)
        if ($roleId == 3) {
            // User is an admin, so call the next middleware function
            return $next($request);
        } else {
            // User is not an admin, so return an error response
            return response()->json(['message' => 'Access denied: parent privileges required'], 403);

        }
    }

}
