<?php
// In a middleware in the external service
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VerifySiptaToken
{
    public function handle(Request $request, Closure $next, ...$allowedRoles)
    {
        // Check #1: Is the user logged in AND has one of the allowed roles?
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            // If no specific roles are required, or the user has one of the required roles
            if (empty($allowedRoles) || in_array($userRole, $allowedRoles)) {
                // Add the role to the request for controllers to use
                $request->merge(['user_role' => $userRole]);
                return $next($request);
            }
        }

        // Get token from the query parameter
        $token = $request->query('token');
        
        if (!$token) {
            // If we reach here, the user is either not logged in or doesn't have the required role,
            // and they haven't provided a token
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized - You need to either be logged in with proper permissions or provide a valid token'], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in with proper permissions to access this page.');
        }
        
        try {
            $siptaPort = env('SIPTA_SERVICE_PORT', '8000');

            // check if siptaPort is set
            if (!$siptaPort) {
                return response()->json(['message' => 'Service unavailable - SIPTA service port not set'], 503);
            }

            // Verify token with your SIPTA service
            $response = Http::get("http://host.docker.internal:{$siptaPort}/sipta/usermanagement/v1/role", [
                'token' => $token
            ]);
            
            if (!$response->successful()) {
                return response()->json(['message' => 'Unauthorized - Invalid token from KelolaJadwal'], 401);
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

            Session::put('token_authenticated', true);
            Session::put('token_user_role', $userRole);
            Session::put('token_user_name', $data['name'] ?? 'SIPTA User');
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('SIPTA service error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Service unavailable - ' . $e->getMessage()], 503);
            }
            return redirect()->route('login')->with('error', 'Unable to connect to authentication service. Please try logging in directly.');
        }
    }
}