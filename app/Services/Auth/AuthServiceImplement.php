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

    // Admin user management (CRUD for cashiers)
    // List all cashiers
    public function listCashiers(): AuthServiceImplement
    {
        $users = $this->userRepository->getUsersByRole('cashier');
        return $this->setCode(200)
            ->setMessage("Cashiers retrieved successfully")
            ->setData(UserResource::collection($users));
    }

    // Create cashier
    public function createCashier($request): AuthServiceImplement
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            $data['role'] = 'cashier';
            $data['password'] = bcrypt($data['password']);

            $user = $this->userRepository->createUser($data);

            return $this->setCode(201)
                ->setMessage("Cashier created successfully")
                ->setData(new UserResource($user));
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Failed to create cashier")
                ->setError($e->getMessage());
        }
    }

    // Update cashier
    public function updateCashier($request, $id): AuthServiceImplement
    {
        try {
            $user = $this->userRepository->findUserById($id);

            if (!$user || $user->role !== 'cashier') {
                return $this->setCode(403)->setMessage("Cannot update this user");
            }

            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:6|confirmed',
            ]);

            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user = $this->userRepository->updateUser($id, $data);

            return $this->setCode(200)
                ->setMessage("Cashier updated successfully")
                ->setData(new UserResource($user));
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Failed to update cashier")
                ->setError($e->getMessage());
        }
    }

    // Delete cashier
    public function deleteCashier($id): AuthServiceImplement
    {
        try {
            $user = $this->userRepository->findUserById($id);

            if (!$user || $user->role !== 'cashier') {
                return $this->setCode(403)->setMessage("Cannot delete this user");
            }

            $this->userRepository->deleteUser($id);

            return $this->setCode(200)->setMessage("Cashier deleted successfully");
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Failed to delete cashier")
                ->setError($e->getMessage());
        }
    }



    // View own profile (only admin)
    public function profile($request): AuthServiceImplement
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return $this->setCode(403)->setMessage("Forbidden: Admin only");
        }

        return $this->setCode(200)
            ->setMessage("Profile retrieved")
            ->setData(new UserResource($user));
    }

    // Update own profile (only admin)
    public function updateProfile($request): AuthServiceImplement
    {
        try {
            $user = $request->user();

            if ($user->role !== 'admin') {
                return $this->setCode(403)->setMessage("Forbidden: Admin only");
            }

            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:6|confirmed',
            ]);

            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user = $this->userRepository->updateUser($user->id, $data);

            return $this->setCode(200)
                ->setMessage("Profile updated successfully")
                ->setData(new UserResource($user));
        } catch (\Exception $e) {
            return $this->setCode(400)
                ->setMessage("Failed to update profile")
                ->setError($e->getMessage());
        }
    }

}
