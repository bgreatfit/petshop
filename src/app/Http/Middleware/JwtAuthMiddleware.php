<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use App\Services\TokenService;

class JwtAuthMiddleware
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $tokenClaims = $this->tokenService->parseToken($token);

        if (!$tokenClaims) {
            return response()->badRequest('Unauthorized');
        }

        if (!$this->tokenService->verifyToken($token)) {
            return response()->badRequest('Unauthorized');
        }

        if ($this->tokenService->isTokenExpired($token)) {
            return response()->badRequest('Unauthorized');
        }



        $jwt_token = JwtToken::where('unique_id', $tokenClaims['user_uuid'])->first();
        if (!$jwt_token) {
            return response()->badRequest('Unauthorized');
        }



        auth()->setUser($jwt_token->user);

        return $next($request);
    }


}
