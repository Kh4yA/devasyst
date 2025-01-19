<?php

namespace App\Utils;

class AutoLoader
{
    /**
     * Rôle: Chargement automatique des classes
     * @param object ($class : la classe a charger)
     */
    public static function autoloader($class): void
    {
        // Vérifie que la classe commence par le App
        // On supprime on remplace le App par une chaine vide
        // On remplace les '\' par '/'
        if (strpos($class, 'App' . '\\') === 0) {
            $class = str_replace('App' . '\\', '', $class);
            $classModel = str_replace('\\', '/', $class);
            // Création du chemin
            $chemin = dirname(__DIR__) . '/' . $classModel . '.php';
            if (file_exists($chemin)) {
                require $chemin;
                return;
            }
        }
    }
    /**
     * Enregistrement de l'autoloader
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoloader']);
    }
}
