<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {
            try {
                $customer = JWTAuth::parseToken()->getPayload();

                if (!$customer) {
                    return response()->json(['ERROR' => 'Usuario no encontrado.'], 401);
                }

                return $next($request);
            } catch (JWTException $e) {
                return response()->json(['ERROR' => 'Token inválido.'], 401);
            }
        }

        return response()->json(['ERROR' => 'Token de autorización no proporcionado.'], 401);
    }
}
