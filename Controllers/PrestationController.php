<?php
/**
 * Controleur qui gere les pages coté prestation
 */
namespace App\Controllers;

use App\Router\Route;

class PrestationController extends BaseController
{
    /**
     * Undocumented function
     *
     * @return void
     */
    #[Route('/prestations/developpement/site-vitrine')]
    public function indexSiteVitrine()
    {
        return $this->render('prestations/developpement/site-vitrine/index.html.twig');
    }/**
     * Undocumented function
     *
     * @return void
     */
    #[Route('/presatations/developpement/application-web')]
    public function indexApplicationWeb()
    {
        return $this->render('prestations/developpement/app-web/index.html.twig');
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    #[Route('/prestations/assistance/recuperation-de-donnees')]
    public function indexRecuperationDonnee()
    {
        return $this->render('prestations/assistance/reccuperation-donnees/index.html.twig');
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    #[Route('/prestations/assistance/assistance-technique')]
    public function indexAssistanceTechnique()
    {
        return $this->render('prestations/assistance/assistance-technique/index.html.twig');
    }
}