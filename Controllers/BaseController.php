<?php

namespace App\Controllers;

use Exception;
use App\Utils\Mail;
use App\Models\Users;
use App\Router\Route;
use App\Utils\_Model;
use App\Utils\Session;
use App\Utils\UserRoles;
use App\Models\Pharmacie;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use App\Exceptions\ForbiddenPage;
use App\Framework\Service\FilterMemory;

abstract class BaseController extends _Model
{
    protected $twig;

    public function __construct(){

    }
    /**
     * role : gere le rendu de la vue
     * @param string $viewPath ( chemin a utiliser au format ($this->render('toto.html.twig')))
     * @param array $data ( tableau associatif des donnée a passer a la vue )
     * @return string string
     */
    public function render($viewPath, $data = [])
    {
        try {
        return $GLOBALS['twig']->render($viewPath, $data);
        } catch (LoaderError $e) {
            echo "Template non trouvé: " . $e->getMessage();
        } catch (RuntimeError $e) {
            echo "Erreur dans l'execution de twig: " . $e->getMessage();
        }
    }
    /**
     * Role : rediriger une vers une route
     * @param $viewPath ( chemin a utiliser )
     * @return string
     */
    public function redirectToRoute($viewPath): void
    {
        header("Location: {$viewPath}");
        exit;
    }
    /**
     * Vérifie qu'un utilisateur est connecté et s'assure qu'il a un des statuts requis.
     * @param array|string $roles (Le ou les roles à vérifier)
     * @return string la route de redirection
     * @throws ForbiddenPage Si l'utilisateur n'a pas un des statuts requis.
     */
    protected function ensureStatus($roles)
    {
        // Vérifie si un utilisateur ou une pharmacie est connecté(e)
        if (!Session::isConnected()) {
            return $this->redirectToRoute('/');
        }
        // Vérifie le type de l'entité connectée
        $type = $_SESSION['user']['type'];
        $connectedEntity = null;
        $token = $_SESSION['user']['csrf_token'];
    
        // Vérifie si l'entité est un utilisateur ou une pharmacie et charge l'entité appropriée
        if ($type === 'user') {
            $connectedEntity = Session::sessionUserConnect(); // Charge l'utilisateur
        } elseif ($type === 'pharmacie') {
            $connectedEntity = Session::sessionPharmacyConnect(); // Charge la pharmacie
        }
    
        // Si l'entité n'est pas correctement chargée, déconnexion
        if (!$connectedEntity || $connectedEntity->get('token') !== $token) {
            Session::sessionDeconnected();
            return $this->redirectToRoute('/');
        }
    
        // Vérification des rôles (convertit en tableau si nécessaire)
        if (!is_array($roles)) {
            $roles = [$roles];
        }
    
        // Vérifie si l'entité connectée possède l'un des rôles requis
        $roleEntity = ($type === 'user') ? $connectedEntity->getRoles() : $connectedEntity->get('role');
        if (!in_array($roleEntity, $roles)) {
            // Gère une tentative d'accès non autorisée (redirection ou erreur)
            try {
                throw new ForbiddenPage();
            } catch (ForbiddenPage $e) {
                $error = $e->getMessage();
                $errorCode = $e->getCode();
                require_once __DIR__.'/../templates/error/index.php';
                exit;
            }
        }
    }    /**
     * Définit les en-têtes CORS pour autoriser l'accès à l'API.
     */
    protected function CORSHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
    /**
     * Role : Prepare les principales données pour gerer la pagination
     *  @param $item ( données a paginer )
     *  @param $limit ( nombre de données a afficher a 10 par defaut)
     *  @return array ($nbEntry, $nbPage, $currentPage, $offset)
     */
    protected function paginate($items, $elementByPage = 10)
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $nbEntry = count($items);
        $nbPage = (int) ceil($nbEntry / $elementByPage);
        $currentPage = ($currentPage > $nbPage) ? 1 : $currentPage;
        $offset = ($currentPage - 1) * $elementByPage;
        return [
            'nbEntry' => $nbEntry,
            'nbPage' => $nbPage,
            'currentPage' => $currentPage,
            'offset' => $offset
        ];
    }
    /**
     * role : generer un token
     */
    protected function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        return $token;
    }
    /**
     * Envoie un e-mail pour une modification de mot de passe avec un token
     *
     * @param string $email L'adresse e-mail du destinataire
     * @param string $token Le token pour la modification de mot de passe
     */
    protected function sendMail($email, $template, $subject, $variables = [], $pj = null)
    {
        // Extraire les variables pour les rendre accessibles dans le template
        extract($variables);
        $mail = new Mail();
        $mail->setTo($email);
        $mail->setSubject($subject);
        $mail->setBody($template);
        if ($pj) {
            $mail->setPj($pj);
            }
        $mail->sendMail($email, $mail->getSubject(), $mail->getBody(), $mail->getPj());
    }
    /**
     * Verifie qu'un mot de passe correspond bien a la regex, et qu il correspond a la confirmation de mot de passe
     *  @param string $password (Le mot de passe principal)
     *  @param string $passwordConfirm (Le mot de passe de confirmation)
     *  @return string (Le mot de passe modifié)
     */
    protected function checkPassword($password, $passwordConfirm): string
    {
        $hashedPassword = '';
        $regex = "/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':\"\\\\|,.<>\/?]{8,}$/";
            if (!preg_match($regex, $password)) {
                throw new Exception("Le mot de passe doit contenir au minimum 8 caractères, 1 majuscule et 1 chiffre.");
            }
            if ($password !== $passwordConfirm) {
                throw new Exception('Les mots de passe ne correspondent pas !');
            }
            // Hachage du mot de passe avant de le stocker
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            return $hashedPassword;
    }
    public function envoyerEmailRejet($destinataire, $subject, $variables) {
        extract($variables);
        ob_start();
        require __DIR__ . "/../templates/mail/mailRejet.php";
        $content = ob_get_clean();
        if (!$content) {
            // Si le contenu est vide, affichez un message d'erreur
            echo 'Erreur : Impossible de récupérer le contenu du template.';
        }
        $this->sendMail($destinataire, $content, $subject, $variables);
    }
}
