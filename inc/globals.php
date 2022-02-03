<?php

/**
 * Variables globales pour gérer les différents
 * environnements (TEST ou PROD)
*/

if($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1'){

    // Connexion à la BDD
    define('SERVER', 'localhost' );
    define('DBB', 'colombes');
    define('USER', 'root');
    define('PASS', '');
    define('PORT', '3306');

    // Gestion des erreurs Apache (local uniquement)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Gestion des options PDO
    define('PDO_OPTIONS', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO ::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
    
}else{

    // Connexion à la BDD
    define('SERVER', '' );
    define('DBB', '');
    define('USER', '');
    define('PASS', '');
    define('PORT', '');

    // Gestion des options PDO
    define('PDO_OPTIONS', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO ::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
}