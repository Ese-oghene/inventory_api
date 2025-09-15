<?php

namespace App\Services\Auth;

use LaravelEasyRepository\BaseService;

interface AuthService extends BaseService{

    // Write something awesome :)

      public function register($request): AuthServiceImplement;

    public function login($request): AuthServiceImplement;

    public function logout($request): AuthServiceImplement;

    public function adminLogin($request): AuthServiceImplement;

    public function adminLogout($request): AuthServiceImplement;

// Add this to the new code on the server
  // ✅ New methods
    public function profile($request): AuthServiceImplement;

    public function updateProfile($request): AuthServiceImplement;

    // ✅ Cashier management
    public function listCashiers(): AuthServiceImplement;

    public function createCashier($request): AuthServiceImplement;

    public function updateCashier($request, $id): AuthServiceImplement;

    public function deleteCashier($id): AuthServiceImplement;
}
