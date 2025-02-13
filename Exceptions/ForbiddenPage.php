<?php

namespace App\Exceptions;
/**
 * class qui retourne un code erreur 403
 */
class ForbiddenPage extends \Exception
{
    protected $message = 'Vous n\'avez pas acces a cette page !.';
    protected $code = 403;
    public function __construct()
    {
        parent::__construct($this->message);
    }
}