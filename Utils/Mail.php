<?php

namespace App\Utils;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private $from;
    private $to;
    private $body;
    private $subject;
    private $pj = [];

    /**
     * Envoie un e-mail à une adresse spécifique
     *
     * @param string $to L'adresse e-mail du destinataire
     * @param string $subject L'objet de l'e-mail
     * @param string $body Le contenu du message
     */
    public function sendMail($to, $subject, $body, $pj = null)
    {
        // Crée une instance de PHPMailer, passer `true` permet de gérer les exceptions
        $mail = new PHPMailer(true);
        $config = require __DIR__ . '/../.config/mailPass.php';

        // Paramètres du serveur
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                       // Désactiver le débogage en production
        $mail->isSMTP();                                          // Utiliser SMTP pour l'envoi
        $mail->Host       = 'smtp.gmail.com';                     // Définir le serveur SMTP (ici Gmail)
        $mail->SMTPAuth   = true;                                 // Activer l'authentification SMTP
        $mail->Username   = $config['SMTP_USER'];                  // Récupérer le nom d'utilisateur SMTP via une variable d'environnement
        $mail->Password   = $config['SMTP_PASSWORD'];              // Récupérer le mot de passe SMTP via une variable d'environnement
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          // Activer le chiffrement TLS implicite
        $mail->Port       = 465;                                  // Port pour le serveur SMTP (utiliser 587 pour STARTTLS)

        // Destinataires
        $mail->setFrom('test.pharma.mail@gmail.com', 'Mailer');  // Définir l'adresse d'expéditeur
        $mail->addAddress($to);                                   // Ajouter un destinataire

        // Contenu de l'e-mail
        $mail->isHTML(true);                                      // Définir le format de l'e-mail en HTML
        $mail->Subject = $subject;                                // Objet de l'e-mail
        $mail->Body    = $body;                                   // Corps du message en HTML
        $mail->AltBody = strip_tags($body);                       // Corps du message en texte brut
        // Ajout des pièces jointes
        foreach ($pj as $file) {
            if (is_array($file)) {
                $mail->addAttachment($file['path'], $file['name']);
            } else {
                $mail->addAttachment($file); // Si $file est un chemin direct
            }
        }
        try {
            // Envoyer l'e-mail
            if (!empty($mail->Username) && !empty($mail->Password)) {
                $mail->send();
            } else {
                echo 'Erreur : Le nom d\'utilisateur ou le mot de passe SMTP est vide.';
            }
        } catch (Exception $e) {
            // Gérer les erreurs lors de l'envoi
            error_log("Erreur d'envoi de mail : " . $mail->ErrorInfo);
            echo "Une erreur est survenue lors de l'envoi de l'e-mail.";
        }
    }
    /**
     * Obtenir la valeur de l'expéditeur (from)
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Définir la valeur de l'expéditeur (from)
     *
     * @return  self
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Obtenir la valeur du destinataire (to)
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Définir la valeur du destinataire (to)
     *
     * @return  self
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Obtenir le contenu du message (body)
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Définir le contenu du message (body)
     *
     * @return  self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Obtenir l'objet de l'e-mail (subject)
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Définir l'objet de l'e-mail (subject)
     *
     * @return  self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of pj
     */
    public function getPj()
    {
        return $this->pj;
    }

    /**
     * Set the value of pj
     *
     * @return  self
     */
    public function setPj($pj)
    {
        if (is_array($pj)) {
            $this->pj = array_merge($this->pj, $pj); // Ajoute les nouvelles pièces jointes
        } else {
            $this->pj[] = $pj; // Ajoute une seule pièce jointe si $pj n'est pas un tableau
        }
    }
}
