<?php

include_once 'inc/globals.php';

/**
 * Récupération et nettoyage des donnés reçues par le formulaire
 * On utilise des noms de variable dynamique pour affecter chaque valeur néttoyée à une variable du nom de la clé
 * exemple : first_name => Lancy (couple $key => $val) deviendra $first_name = Lancy
 */

foreach($_POST AS $key => $val){
    if(isset($key) && !empty($key)){
        $key = htmlspecialchars($key);
        ${$key} = htmlspecialchars(trim($val));
    }
}

/**
 * On crypte le mot de passe à insérer si les valeurs email et password ont bien été reçues
 * Attention à bien crypter le mot de passe de la même façon que dans la vérification du login_check.php
 */

(isset($login) && isset($password)) ? $password = hash('sha256',(hash('md5', $password).hash('sha1', strtolower($login)))) : '';

try{

    // Connexion à la BDD
    $dbh = new PDO('mysql:host='.SERVER.';port='.PORT.';dbname='.DBB.';charset=utf8', 
    USER, 
    PASS, 
    PDO_OPTIONS);

    /**
     * On vérifie si l'émail renseigné est déjà utilisé
     * @params (array) : le seul paramètre de notre requête sera la valeur nettoyée de l'émail reçue
     * @mailCheck (PDOStatement) contient le résultat de notre requête
     * La méthode rowCount() de PDO permet d'avoir le nombre de lignes retournées par notre requête (contenu dans @mailCheck)
     */

    $sql = "SELECT * FROM customer WHERE email = ?";

    $params = array($login);
    $mailCheck = $dbh -> prepare($sql);
    $mailCheck -> execute($params);

    if($mailCheck->rowCount() === 0){

        /**
         * Dans le cas ou l'émail n'est pas utilisé, on prépare une requête @sql pour ajouter l'utilisateur
         * @params (array) : notre tableau de paramètres qui contient les différentes valeurs à remplacer dans la requête @sql
         * @insertCustomer (PDOStatement) : contient le résultat de notre requête (vide dans ce cas car c'est un INSERT)
         * @stateCustomer (bool) : donne l'état de notre requête (true si c'est fait, false sinon)
         */

        $sql = "INSERT INTO customer (last_name, first_name, email, address_id, store_id) 
                VALUES (?,?,?,?,?)";
        $params = array($last_name, $first_name, $login, $address, 1);

        $insertCustomer = $dbh -> prepare($sql);
    
        $stateCustomer = $insertCustomer -> execute($params);

        if($stateCustomer){
    
            /**
             * Si notre requête précedente a abouti ($stateCustomer = true) alors 
             * On ajoute également l'utilisateur dans la table user pour y affecter son mot de passe crypté
             * @params (array) : tableau associatif de paramètres, qui permet d'affecter sa valeur à chaque variable nomée
             * dans notre requête @sql
             * La méthode lastInsertId() de PDO permet de récupérer le dernier Id ajouté par notre requête @sql
             * @stateUser (bool) : donne l'état de notre requête (true si c'est fait, false sinon)
             */

            $sql = "INSERT INTO user (user_id, password, role) 
                    VALUES (:user_id, :password, :role);";
            $params = array(
                ':user_id' => $dbh->lastInsertId(),
                ':password' => $password, 
                ':role' => 1
            );
            $insertUser = $dbh -> prepare($sql);
            $stateUser = $insertUser -> execute($params);

            /**
             * Si cette dernière requête à également abouti ($stateUser = true) alors
             * On renvoi l'utilisateur sur la page d'accueil avec un message code=5
             */

            if($stateUser){
                unset($dbh);
                header('location: index.php?code=5');
            }
        }

    }else{
        // Si l'émail est déjà utilisé on renvoi l'utilisateur sur la page register avec un code=1
        header('location: register.php?code=1');
    }
    
}catch(PDOException $erreur){

    // Si erreur PDO survient, alors on renvoi le message d'erreur
    echo '<p> Échec de la connexion : ' . $erreur->getMessage().'</p>';
}