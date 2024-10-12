<?php

class User extends Model
{
    protected $table = 'users';  

    public function findAllUsers()
    {
        return $this->findAll(); 
    }

    public function findUser($user_id)
    {
        return $this->first(['user_id' => $user_id]); 
    }

    public function addUser($data)
    {
        return $this->insert($data); 
    }

    public function updateUser($user_id, $data)
    {
        return $this->update($user_id, $data, 'user_id');
    }

    public function deleteUser($user_id)
    {
        return $this->delete($user_id, 'user_id');
    }
}
