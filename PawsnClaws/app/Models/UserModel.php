<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password'];

    public function showAllUsers()
    {
        return $this->all();
    }
    public function deleteUser($id)
    {
        return $this->where('user_id', $id)->delete();
    }
}