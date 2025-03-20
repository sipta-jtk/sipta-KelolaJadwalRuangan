<?php
// In a middleware in the external service
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifySiptaToken
{
    public function handle(Request $request, Closure $next, ...$allowedRoles)
    {
        // Get token from the query parameter
        $token = $request->query('token');
        
        if (!$token) {
            return response()->json(['message' => 'Unauthorized - Token not provided'], 401);
        }
        
        try {
            // Verify token with your SIPTA service
            $response = Http::get('http://host.docker.internal:8000/usermanagement/v1/role', [
                'token' => $token
            ]);
            
            if (!$response->successful()) {
                return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
            }
            
            // Get user role
            $data = $response->json();
            $userRole = $data['role'];
            
            // Check if user has required role (if roles are specified)
            if (!empty($allowedRoles) && !in_array($userRole, $allowedRoles)) {
                return response()->json(['message' => 'Forbidden - Insufficient permissions'], 403);
            }
            
            // Add the role to the request for controllers to use
            $request->merge(['user_role' => $userRole]);
            
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Service unavailable - ' . $e->getMessage()], 503);
        }
    }
}