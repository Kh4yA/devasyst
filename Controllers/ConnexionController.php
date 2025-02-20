<?php

namespace App\Controllers;

use App\Framework\Service\Flash;
use App\Models\User;
use App\Router\Route;

class ConnexionController extends BaseController
{
    private Flash $flashMessage;
    public function __construct()
    {
        parent::__construct();
        $this->flashMessage = new Flash();
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
        $userCurrent = $user->findByOne($identifiant);
        if($userCurrent !== false){
            if(password_verify($password, $userCurrent['password'])){
                if($userCurrent['role'] === 'ADMIN'){
                    $_SESSION['user'] = $userCurrent;
                    return $this->redirectToRoute('/connected/admin-dashbord');
                }
            }else{
                $this->flashMessage->error('Identifiant ou mot de passe incorrect');
                return $this->redirectToRoute('/admin-connect');
            }
        }else{
            $this->flashMessage->error("Vous n'avez pas accès à cette page !");
            return $this->redirectToRoute('/admin-connect');
        }
    }
}