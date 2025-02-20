<?php

namespace App\Models;

use App\Utils\_Model;

class User extends _Model
{
    protected $table = 'user';
    protected $fillable = ['identifiant', 'password', 'nom', 'prenom', 'role'];
    protected $hidden = ['password'];

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * charger un utilisateur par son indentifiant si le mot de passe corespond a celui ci
     *
     * @param [type] $identifiant
     * @return bool
     */
    public function findByOne($identifiant){
        $sql = "SELECT `nom`, `prenom`, `identifiant`, `password`, `role` FROM `user` WHERE `identifiant` = :identifiant";
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