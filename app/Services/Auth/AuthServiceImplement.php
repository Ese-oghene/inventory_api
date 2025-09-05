<?php

namespace App\Services\Auth;
use Illuminate\Validation\ValidationException;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\User\UserRepository;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthServiceImplement extends ServiceApi implements AuthService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "";
     /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
      $this->userRepository = $userRepository;
    }

    // Define your custom methods :)

     // ✅ User Registration
    public function register($request): AuthServiceImplement
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->createUser($validated);

            $token = $user->createToken('token')->plainTextToken;

            return $this->setCode(201)
                ->setMessage("Registration successful")
                ->setData([
                    'user' => new UserResource($user),
                    'token' => $token
                ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Registration Failed")
                ->setError($e->getMessage());
        }
    }

    // ✅ User Login (Cashier & Admin)
    public function login($request): AuthServiceImplement
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->findUserByEmail($validated['email']);

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->setCode(401)->setMessage("Invalid credentials");
            }

             // ✅ Add role check for cashiers only
            // if (!$user->isCashier()) {
            //     return $this->setCode(403)->setMessage("Forbidden: Cashiers only");
            // }

            // Revoke old tokens
            $user->tokens()->delete();

            // Generate token
            $token = $user->createToken('token')->plainTextToken;

            return $this->setCode(200)
                ->setMessage("Login Success")
                ->setData([
                    'user' => new UserResource($user),
                    'token' => $token
                ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Login Failed")
                ->setError($e->getMessage());
        }
    }

    // ✅ Logout
    public function logout($request): AuthServiceImplement
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->setCode(200)
                ->setMessage("Logout Successful");
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Logout Failed")
                ->setError($e->getMessage());
        }
    }

    // ✅ Admin Login (restrict only admins)
    public function adminLogin($request): AuthServiceImplement
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->findUserByEmail($validated['email']);

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->setCode(401)->setMessage("Invalid credentials");
            }

            if (!$user->isAdmin()) {
                return $this->setCode(403)->setMessage("Forbidden: Admins only");
            }

            $user->tokens()->delete();

            $token = $user->createToken('token')->plainTextToken;

            return $this->setCode(200)
                ->setMessage("Admin Login Success")
                ->setData([
                    'user' => new UserResource($user),
                    'token' => $token
                ]);
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Admin Login Failed")
                ->setError($e->getMessage());
        }
    }

    // ✅ Admin Logout
    public function adminLogout($request): AuthServiceImplement
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->setCode(200)
                ->setMessage("Admin Logout Successful");
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Admin Logout Failed")
                ->setError($e->getMessage());
        }
    }
}
