<?php

namespace App\Models;

use App\Utils\_Model;

class User extends _Model
{
    protected $table = 'users';
    protected $fillable = ['identifiant', 'password', 'email', 'role'];
    protected $hidden = ['password'];

    /**
     * charger un utilisateur par son indentifiant si le mot de passe corespond a celui ci
     *
     * @param [type] $identifiant
     * @return void
     */
    public function findByOne($identifiant){
        $sql = "SELECT `name`, `firstName`, `identifiant`, `password` FROM `user` WHERE `identifiant` = :identifiant";
        $param = [':identifiant' => $identifiant];
        global $bdd;
        $req = $bdd->sqlExecute($sql, $param);
        $user = $req->fetch();
        if($user){
            return $user;
        }
        return false;
    }
}