<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;

class Handler extends Exception
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return $this->sanctumJsonResponse($exception);
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Custom JSON response for Sanctum
     */
    protected function sanctumJsonResponse(AuthenticationException $exception): JsonResponse
    {
        $guards = $exception->guards();
        $isSanctum = in_array('sanctum', $guards);

        return response()->json([
            'status' => 'error',
            'code' => 401,
            'message' => $isSanctum
                ? 'Token akses tidak valid atau telah kadaluarsa'
                : 'Anda harus login terlebih dahulu',
            'recommendation' => $isSanctum
                ? 'Lakukan request token baru melalui endpoint /api/auth/refresh'
                : 'Silakan login melalui halaman login',
            'documentation' => url('/docs/api-authentication')
        ], 401);
    }
}
