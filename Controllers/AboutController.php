<?php

namespace App\Controllers;

use App\Router\Route;

class AboutController extends BaseController
{
    #[Route('/l-entreprise')]
    public function index()
    {
        return $this->render('about/index.html.twig');
    }
}