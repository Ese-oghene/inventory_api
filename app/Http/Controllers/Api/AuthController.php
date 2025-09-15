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


    // ==========================
    // 👤 Admin Profile
    // ==========================
    public function profile(Request $request): JsonResponse
    {
        return $this->authService->profile($request)->toJson();
    }

    public function updateProfile(Request $request): JsonResponse
    {
        return $this->authService->updateProfile($request)->toJson();
    }

    // ==========================
    // 👥 User Management (Cashiers)
    // ==========================
    public function index(): JsonResponse
    {
        return $this->authService->listCashiers()->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        return $this->authService->createCashier($request)->toJson();
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->authService->updateCashier($request, $id)->toJson();
    }

    public function destroy($id): JsonResponse
    {
        return $this->authService->deleteCashier($id)->toJson();
    }



}
