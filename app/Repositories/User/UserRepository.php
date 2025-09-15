<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository{

    // Write something awesome :)
     public function createUser(array $data);
    public function findUserByEmail(string $email);

  //  add this to the code on the server
  
    public function findUserById($id);
    public function updateUser($id, array $data);
    public function deleteUser($id);
    public function getUsersByRole($role);

}
