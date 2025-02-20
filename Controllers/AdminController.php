<?php

namespace App\Controllers;

use App\Framework\Service\Flash;
use App\Models\Message;
use App\Router\Route;
use App\Utils\_Model;

class AdminController extends BaseController {

    protected $flashMessage;

    public function __construct() {

        parent::__construct();
        $this->flashMessage = new Flash();
    }

    #[Route('/connected/admin-dashbord')]
    public function index() {
        $messages = new Message();
        $messages = $messages->listAll();
        return $this->render('admin/index.html.twig', compact('messages'));
    }
    /**
     * Supprimer un message
     */
    #[Route('/admin-message-delete/{id}')]
    public function deleteMessage($id) {
        $message = new Message($id);
        if($message->delete()){
            $this->flashMessage->success('Le message a été supprimé avec succès');
            return $this->redirectToRoute('/connected/admin-dashbord');
        }
        else{
            $this->flashMessage->error('Une erreur est survenue lors de la suppression du message');
            return $this->redirectToRoute('/connected/admin-dashbord');
        }
    }
    /**
     * Afficher un message en detail 
     * @param int $id
     */
    #[Route('/connected/admin-dashbord/view/{id}')]
    public function viewMessage($id) {
        $message = new Message($id);
        return $this->render('admin/view.html.twig', compact('message'));
    }
    /**
     * deconnexion et suppression du $_SESSION
     */
    #[Route('/connected/admin-logout')]
    public function logout() {
        session_destroy();
        return $this->redirectToRoute('/');
    }
}