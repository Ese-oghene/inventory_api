<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;

class UserRepositoryImplement extends Eloquent implements UserRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createUser(array $data)
    {
        // No need to bcrypt manually because of "password => hashed" in User model
        return $this->model->create($data);
    }

    public function findUserByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }


    //add this to the code on the server
     public function findUserById($id)
    {
        return $this->model->find($id);
    }

    public function updateUser($id, array $data)
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->model->findOrFail($id);
        return $user->delete();
    }

    public function getUsersByRole($role)
    {
        return $this->model->where('role', $role)->get();
    }

    // Write something awesome :)
}
