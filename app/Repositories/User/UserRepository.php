<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository{

    // Write something awesome :)
     public function createUser(array $data);
    public function findUserByEmail(string $email);
    
}
