<?php

namespace App\Utils;

final class UserRoles
{
    public const SUPER_ADMIN = 'SUPER_ADMIN';
    public const ADMIN = 'ADMIN';
    public const PHARMACIE = 'PHARMACIE';
    public const GESTIONNAIRE = 'GESTIONNAIRE';
    public const USER = 'USER'; // A supprmer apres modif

    /**
     * Retourne tous les rôles disponibles.
     */
    public static function getAllRoles(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::PHARMACIE,
            self::GESTIONNAIRE,
            self::USER, // A supprmer apres modif
        ];
    }
}