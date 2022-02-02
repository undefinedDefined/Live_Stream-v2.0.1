<?php
    // Démarre ou restaure la session
    session_start();

    // Détruit toutes les variables de session
    session_unset();

    // Arrête la session en cours
    session_destroy();

    // Redirige vers index.php
    header('Location: index.php?code=4'); 
?>