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
}
