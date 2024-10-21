<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $userModel = new UserModel();

        $users = $userModel->showAllUsers();
        return view('server.users', compact('users'));
    }
    public function createUser()
    {

    }
    public function deleteUser($id)
    {
        $user = new UserModel();
        $user->deleteUser($id);
        return redirect()->route('users.index');
    }
}
