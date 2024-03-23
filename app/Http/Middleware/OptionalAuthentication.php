<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class OptionalAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a token is provided in the request
        $token = $request->header('Authorization');

        // If a token is provided, authenticate the request
        if ($token) {
            Auth::onceUsingId($this->getUserIdFromToken($token));
        }

        // Proceed with the request handling
        return $next($request);
    }

    /**
     * Get the user ID from the token
     */
    private function getUserIdFromToken(string $token): int
    {
        $token = Str::replaceFirst('Bearer ', '', $token);
        $token = PersonalAccessToken::findToken($token);
        if (!$token) {
            return 0;
        }
        $user = $token->tokenable;
        return $user->id;
    }
}
