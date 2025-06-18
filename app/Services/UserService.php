<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make('123456789');
//        dd($data);
        return User::create($data);
    }
}
