<?php

namespace App\Utils;

use App\Models\Pharmacie;
use App\Models\Users;

/**
 * Classe qui gère la session
 */
class Session
{
    /**
     * Active la session
     */
    public static function sessionActivation()
    {
        session_start();
        if (self::isConnected()) {
            // Obtenir l'objet connecté
            if (self::isUser()) {
                $user = self::sessionUserConnect();
            } elseif (self::isPharmacy()) {
                $pharmacy = self::sessionPharmacyConnect();
            }
        }
    }

    /**
     * Vérifie si un utilisateur ou une pharmacie est connecté
     * @return bool true si $_SESSION a les clés "id" et "type" et qu'elles ne sont pas vides
     */
    public static function isConnected()
    {
        return !empty($_SESSION['user']['id']) && !empty($_SESSION['user']['type']);
    }

    /**
     * Vérifie si c'est un utilisateur connecté
     * @return bool true si le type est 'user'
     */
    public static function isUser()
    {
        return self::isConnected() && $_SESSION['user']['type'] === 'user';
    }

    /**
     * Vérifie si c'est une pharmacie connectée
     * @return bool true si le type est 'pharmacy'
     */
    public static function isPharmacy()
    {
        return self::isConnected() && $_SESSION['user']['type'] === 'pharmacie';
    }

    /**
     * Connecte un utilisateur ou une pharmacie
     * @param int $id
     * @param string $type 'user' ou 'pharmacy'
     */
    public static function connected($id, $type)
    {
        $_SESSION['user']["id"] = $id;
        $_SESSION['user']["type"] = $type;
    }

    /**
     * Vide et détruit la session en cours
     */
    public static function sessionDeconnected()
    {
        session_unset();
        session_destroy();
    }

    /**
     * @return int|null ID de la session si non null
     */
    public static function sessionIdConnect()
    {
        if (self::isConnected()) {
            return $_SESSION['user']['id'];
        }
        return null;
    }

    /**
     * Charge un nouvel objet Utilisateur
     * @return Users|null Un objet utilisateur si connecté, sinon null
     */
    public static function sessionUserConnect()
    {
        if (self::isUser()) {
            return new Users(self::sessionIdConnect());
        }
        return null;
    }

    /**
     * Charge un nouvel objet Pharmacie
     * @return Pharmacy|null Un objet pharmacie si connecté, sinon null
     */
    public static function sessionPharmacyConnect()
    {
        if (self::isPharmacy()) {
            return new Pharmacie(self::sessionIdConnect());
        }
        return null;
    }
}