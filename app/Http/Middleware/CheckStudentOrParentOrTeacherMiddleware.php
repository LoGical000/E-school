<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentOrParentOrTeacherMiddleware
{
    use ApiResponseTrait;

//            student=2
//            parent=3
//            teacher=4
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Get the authenticated user's role ID
        $roleId = Auth::user()->role;

        // Check if the user is an admin (assuming admin role ID is 1)
        if ($roleId == 2 || $roleId == 3 || $roleId == 4) {
            // User is an admin, so call the next middleware function
            return $next($request);
        } else {
            // User is not an admin, so return an error response
            return $this->apiResponse('Access denied: student or teacher or parent privileges required',null,false);

        }
    }
}
