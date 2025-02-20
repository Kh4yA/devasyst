<?php

// initialisation fichier a inclure au debut de chaque fichier
// gestion des erreurs

use Twig\Environment;
use App\Utils\Session;
use App\Utils\Database;
use App\Utils\AutoLoader;
use App\Framework\Service\Flash;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use App\Framework\Twig\TwigExtension;
use App\Framework\Service\FilterMemory;

// ini_set('display_errors', 1); // desactiver en production
ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__ ). '/error.log');
error_reporting(E_ALL);

// Définir le chemin de la racine du projet
define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/constantes.php';
// utilisation de la base de donnée
$config = require __DIR__ . '/../.config/datadb.php';
require "Database.php";

try {
    global $bdd;
    $bdd = new Database($config['name'], $config['host'], $config['user_name'], $config['password']);
} catch (Throwable $exception) {
    echo "Erreur dans la database $exception <br>";
}
// Mise en place de l'auto loader
require __DIR__.'/Autoloader.php';
if (class_exists("App\Utils\Autoloader")) {
    AutoLoader::register();
} else {
    echo "Erreur : class autoloader pas trouvée. <br>";
}
if (class_exists('App\Utils\Session')) {
    Session::sessionActivation();
} else {
    echo "Erreur : La classe session n'a pas été trouvée.<br>";
}
$loader = new FilesystemLoader(VIEW_PATH);
$twig = new Environment($loader, [
    'debug' => true, // Active le mode debug
    'cache' => false, // Désactive le cache (facultatif, utile en développement)
]);
$flashService = new Flash();
$filterMemory = new FilterMemory();
// Ajout des extensions Twig
$twig->addExtension(new TwigExtension($flashService, $filterMemory));
$twig->addExtension(new DebugExtension());
$GLOBALS['twig'] = $twig;