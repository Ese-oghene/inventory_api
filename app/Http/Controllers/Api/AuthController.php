<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    //
    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

     /**
     * ✅ User Registration
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authService->register($request)->toJson();
    }

    /**
     * ✅ User Login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request)->toJson();
    }

    /**
     * ✅ User Logout
     */
    public function logout(Request $request): JsonResponse
    {
        return $this->authService->logout($request)->toJson();
    }

    /**
     * ✅ Admin Login
     */
    public function adminLogin(LoginRequest $request): JsonResponse
    {
        return $this->authService->adminLogin($request)->toJson();
    }

    /**
     * ✅ Admin Logout
     */
    public function adminLogout(Request $request): JsonResponse
    {
        return $this->authService->adminLogout($request)->toJson();
    }
}
