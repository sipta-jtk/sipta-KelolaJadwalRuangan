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
        $token = null;
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

        // Check #2: If the token is in bearer format, extract it
        if ($request->bearerToken()) {
            $token = $request->bearerToken();
        } else {
            // If the token is not in bearer format, check if it's in the query parameter
            $token = $request->query('token');
        }
        
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

            $url = "https://polban-space.cloudias79.com/sipta/usermanagement/v1/role?token=" . urlencode($token);
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            $userRole = $data['role'] ?? null;
            
            // $response = Http::get("https://polban-space.cloudias79.com/sipta-dev/usermanagement/v1/role", [
            //     'token' => $token
            // ]);
            // $data = $response->json();
            // $userRole = $data['role'];
            
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
            
            return response()->json(['message' => 'Service unavailable - ' . $e->getMessage()], 200);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Service unavailable - ' . $e->getMessage()], 503);
            }
            return redirect()->route('login')->with('error', 'Unable to connect to authentication service. Please try logging in directly.');
        }
    }
}
