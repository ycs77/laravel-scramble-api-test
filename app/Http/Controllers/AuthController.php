<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Group('[ 1 - 身分驗證 ]')]
class AuthController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login']),
        ];
    }

    /**
     * @unauthenticated
     */
    #[Endpoint(title: '[ 1 - 001 ] 登入')]
    public function login(Request $request)
    {
        $credentials = $request->validate([
            /**
             * 使用者 Email
             * @example "test@example.com"
             */
            'email' => ['required', 'string'],
            /**
             * 使用者密碼
             * @example "password"
             */
            'password' => ['required', 'string'],
        ]);

        if (! $token = Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => [
                __('auth.failed'),
            ]]);
        }

        return $this->respondWithToken($token);
    }

    #[Endpoint(title: '[ 1 - 002 ] 登出')]
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    #[Endpoint(title: '[ 1 - 003 ] 取得使用者資訊')]
    public function me()
    {
        return UserResource::make(Auth::user());
    }

    #[Endpoint(title: '[ 1 - 004 ] 刷新 Token')]
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            /** @example "api_token_1234567890" */
            'access_token' => $token,
            /** @example "bearer" */
            'token_type' => 'bearer',
            /**
             * @var int
             * @example 3600
             */
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }
}
