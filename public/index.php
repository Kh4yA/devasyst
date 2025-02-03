<?php

use App\Router\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ConnexionController;
use App\Controllers\ContactController;
use App\Controllers\PrestationController;

// Appel du fichier d'initialisation (init.php)
if (file_exists(__DIR__."/../Utils/init.php")) {
    require_once __DIR__."/../Utils/init.php";
} else {
    echo 'init non trouvé';
}

// Création de l'instance du routeur
$router = new Router();

// Enregistrer toutes les routes à partir des contrôleurs

// Définir les routes
$router->registerControllerRoutes(HomeController::class);
$router->registerControllerRoutes(AboutController::class);
$router->registerControllerRoutes(PrestationController::class);
$router->registerControllerRoutes(ContactController::class);
$router->registerControllerRoutes(ConnexionController::class);




try{
    echo $router->resolve($_SERVER['REQUEST_URI']);
}catch(Exception $e){
    echo $e->getMessage();
}
