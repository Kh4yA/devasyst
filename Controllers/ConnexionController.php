<?php

namespace App\Controllers;

use App\Models\User;
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
        return $this->render('connexion/index.html.twig');
    }
    #[Route('/admin-connect-verif', methods:['POST'])]
    public function connect()
    {
        $identifiant = $_POST['identifiant'];
        $password = $_POST['password'];
        $user = new User();
        $user->findByOne($identifiant);
        if ($user && password_verify($password, $user->get('password'))) {
            $_SESSION['user'] = $user;
            print_r($_SESSION);

            return $this->redirectToRoute('/admin-dashbord');
        } else {
            $this->flash->error('Identifiant ou mot de passe incorrect.');
            return $this->render('connexion/index.html.twig');
        }
    }
}