<?php

namespace App\Utils;

use ErrorException;
use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class errorManager
{
    private $twig;

    public function __construct()
    {
        // Initialise Twig
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader);

        // Enregistre les gestionnaires d'exceptions et d'erreurs
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    /** Méthode pour afficher un template avec Twig
     * @param string $template - Nom du fichier de template (par exemple, 'error.html.twig')
     * @param array $param - Tableau de paramètres à passer au template
     * @return bool - Retourne true si l'affichage est réussi, false en cas d'erreur
     */
    public function display($template, $param = [])
    {
        try {
            // Rend et affiche le template avec Twig
            echo $this->twig->render($template, $param);
            return true;
        } catch (Throwable $e) {
            // En cas d'exception, enregistre l'erreur et retourne false
            $this->logError($e);
            return false;
        }
    }

    // Gestionnaire d'exceptions
    public function handleException($e)
    {
        echo "Exception interceptée par le handler<br>";
        echo "Erreur : " . $e->getMessage() . " sur " . $e->getFile() . ":" . $e->getLine();
        echo "<b>backTrace</b><pre>";
        print_r($e->getTrace());
        echo "</pre>";

        // Enregistre l'exception dans le fichier de log
        $this->logError($e);
    }

    // Gestionnaire d'erreurs
    public function handleError($errno, $errmsg, $file, $line, $context)
    {
        echo "Erreur $errno ($errmsg) interceptée dans $file:$line par le handler<br>";
        echo "<b>backTrace</b><pre>";
        echo "</pre>";

        // Enregistre l'erreur en tant qu'exception dans le fichier de log
        $this->logError(new ErrorException($errmsg, 0, $errno, $file, $line));
        return true;
    }

    // Méthode pour enregistrer une erreur ou une exception dans le fichier de log
    private function logError(Throwable $e)
    {
        // Crée un message d'erreur formaté en HTML
        $errorMessage = sprintf(
            "<details>\n" .
                "<summary><strong>Erreur capturée le %s</strong></summary>\n" .
                "<div style='margin-left: 20px; font-family: Arial, sans-serif;'>\n" .
                "<p><strong>Message :</strong> <span style='color: red;'>%s</span></p>\n" .
                "<p><strong>Fichier :</strong> %s</p>\n" .
                "<p><strong>Ligne :</strong> %d</p>\n" .
                "<p><strong>Stack Trace :</strong></p>\n<pre style='background-color: #f9f9f9; border: 1px solid #ccc; padding: 10px;'>%s</pre>\n" .
                "</div>\n" .
                "</details>\n",
            date('Y-m-d H:i:s'),  // Date et heure de l'erreur
            htmlspecialchars($e->getMessage()),  // Message d'erreur, échappé pour éviter les failles XSS
            htmlspecialchars($e->getFile()),     // Fichier où l'erreur s'est produite
            $e->getLine(),        // Ligne où l'erreur s'est produite
            htmlspecialchars($e->getTraceAsString()) // Pile d'appels (stack trace), échappée
        );
        // Chemin vers le fichier de log
        $logFilePath = __DIR__ . '/error.log';  // Ou vous pouvez ajuster le chemin
        // Si le fichier n'existe pas, créez-le
        if (!file_exists($logFilePath)) {
            touch($logFilePath);
        }
        // Assurez-vous que le fichier est accessible en écriture
        if (is_writable($logFilePath)) {
            // Écrit le message d'erreur dans le fichier de log
            file_put_contents($logFilePath, $errorMessage, FILE_APPEND);
        } else {
            // Optionnel : afficher un message ou gérer l'erreur si le fichier n'est pas accessible
            echo "Le fichier de log n'est pas accessible en écriture.";
        }
    }
}
