<?php

namespace App\Controllers;

use App\Router\Route;

class HomeController extends BaseController
{
    #[Route('/')]
    public function index()
    {
        return $this->render('home/index.html.twig');
    }
}