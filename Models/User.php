<?php

namespace App\Models;

use App\Utils\_Model;

class User extends _Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'password', 'email', 'role'];
    protected $hidden = ['password'];
}