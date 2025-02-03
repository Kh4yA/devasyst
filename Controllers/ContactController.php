<?php

namespace App\Controllers;

use App\Framework\Service\Flash;
use App\Models\Message;
use App\Models\User;
use App\Router\Route;
use Exception;

class ContactController extends BaseController
{
    protected $message;
    protected $flash; 

    public function __construct()
    {
        $this->message = new Message();
        $this->flash = new Flash();
    }


    #[Route('/contact')]
    public function index()
    {
        return $this->render('contact/index.html.twig');
    }
    #[Route('/contact/saveMessage', methods:['POST'])]
    public function saveMessage()
    {
        try{
            $name = $_POST['name'];
            $firstName = $_POST['firstName'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            if (empty($name) || empty($firstName) || empty($email) || empty($message)) {
                throw new Exception('Tous les champs sont obligatoires.');
            }
            $this->message->loadFromTab($_POST);
            $this->message->set('date_post',  date('Y-m-d H-i-s'));
            if ($this->message->insert()) {
                $this->flash->success('Message envoyé avec succès.');
            } else {
                $this->flash->error('Erreur lors de l\'envoi du message.');
            }
            return $this->render('contact/index.html.twig');
        } catch (Exception $e) {
                $this->flash->error($e->getMessage());
                return $this->render('contact/index.html.twig');
            }
    }
}
