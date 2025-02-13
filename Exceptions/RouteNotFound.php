<?php

namespace App\Exceptions;
/**
 * classe de gestion d'erreur en cas de route non trouvé
 */
class RouteNotFound extends \Exception
{
    protected $message = 'La page que vous cherchez semble introuvable.';
    protected $code = 404;
}