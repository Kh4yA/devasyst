<?php

namespace App\Controllers;

use App\Router\Route;

class ConnexionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    #[Route('/admin-connect')]
    public function index()
    {
        echo $this->render('connexion/index.html.twig');
    }
}