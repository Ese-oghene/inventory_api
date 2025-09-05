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

   

    // Write something awesome :)
}
