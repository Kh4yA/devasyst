<?php

namespace App\Controllers;

use App\Router\Route;

class RGPDController extends BaseController
{
    /**
     * Afficher la page des mentions légale
     */
    #[Route('/mentions-legales')]
    public function legalNotice()
    {
        return $this->render('contact/legalNotice.html.twig');
    }
    /**
     * Afficher la page de politique det de confidentialité
     */
    #[Route('/politique-de-confidentialite')]
    public function privacyPolicy()
    {
        return $this->render('contact/privacyPolicy.html.twig');
    }
}
