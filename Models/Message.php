<?php

namespace App\Models;

use App\Utils\_Model;

class Message extends _Model
{
    protected $table = 'messages';
    protected $fields = ['name', 'firstName', 'email', 'message', 'date_post'];
}