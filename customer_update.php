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

try{

    $dbh = new PDO(
        'mysql:host=' . SERVER . ';dbname=' . DBB . ';charset=UTF8',
        USER,
        PASS,
        PDO_OPTIONS
    );

    $sql = "UPDATE customer
            SET first_name = ?,
                last_name = ?,
                email = ?,
                address_id =  ?,
                active = ?
            WHERE customer_id = ?";
    
    $params = array($first_name, $last_name, $login, $adresse, $active, $id);

    $stmt = $dbh -> prepare($sql);
    $stateInsert = $stmt -> execute($params);

    if($stateInsert){

        header('location: customer.php?page=1&sortName=user_id&sortBy=asc&code=1');

    }else{

        header('location: customer.php?page=1&sortName=user_id&sortBy=asc&code=0');
    }
    



}catch(PDOException $e){

    echo '<p> Échec de la connexion : ' . $e->getMessage() . '</p>';

}