<?php

namespace App\Exceptions;
/**
 * Gere l'erreur a la connexion
 */
class ErrorConnexion extends \Exception
{
    protected $message = 'Mot de passe ou login incorrect.';
}