<?php

// Import des scripts externes

include_once 'inc/globals.php';

// 1. Verifie et sécurise les données passées par POST
if( isset($_POST['login']) && !empty($_POST['login'])){
    $login = htmlspecialchars($_POST['login']);
}
if( isset($_POST['password']) && !empty($_POST['password'])){
    $password = htmlspecialchars($_POST['password']);
}

// 2. Assigne les valeurs cryptées pour comparaison
$login = strtolower($login);
$password = hash('sha256',(hash('md5', $password).hash('sha1', $login)));
// $password = password_hash(hash('md5', $password).hash('sha1', $login), CRYPT_SHA256);

// 3. Teste si le couple login/password est correct
try{

    // Connexion à la BDD
    $dbh = new PDO('mysql:host='.SERVER.';dbname='.DBB.';charset=UTF8', 
    USER, 
    PASS, 
    PDO_OPTIONS);

    // Préparation de la requête
    $sql = "SELECT first_name, customer.customer_id, role, avatar, email, password
            FROM user
                INNER JOIN customer ON customer.customer_id = user.user_id
            WHERE email = ? AND password = ?";

    $stmt = $dbh->prepare($sql);

    // tableau de paramètres
    $params = array($login, $password);

    // Execution de la requête avec les paramètres
    $stmt->execute($params);

    // Vérification du nombre de données en sortie de notre requête
    if($stmt->rowCount() === 1){

        // Démarre une session pour l'utilisateur
        session_start();
        $row = $stmt->fetch();
        $_SESSION['isauth'] = true;
        $_SESSION['fname'] = ucwords(strtolower($row['first_name']));
        $_SESSION['userid'] = (int) $row['customer_id'];
        $_SESSION['role'] = (int) $row['role'];
        $_SESSION['avatar'] = $row['avatar'];

        header('location: index.php?code=1');
    }else{
        header('location: index.php?code=0');
    }

}catch(PDOException $e){
    // Si erreur on redirige l'utilisateur vers la page login.php avec le code erreur 2 (erreur inconnue)
    header('location: index.php?code=2');
}



