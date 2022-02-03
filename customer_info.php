<?php

include_once 'inc/globals.php';

// Url du formulaire en cas de changement de méthode de requête
// DEFINE('FORM_URL', 'customer_update.php');
DEFINE('FORM_URL', 'customer_update_ajax.php');


if (isset($_POST['id']) && !empty($_POST['id'])) {
    $customerId = htmlspecialchars($_POST['id']);
} else {
    $customerId = 1;
}

try {
    $dbh = new PDO(
        'mysql:host=' . SERVER . ';dbname=' . DBB . ';charset=UTF8',
        USER,
        PASS,
        PDO_OPTIONS
    );

    $sql = "SELECT c.first_name prenom, c.last_name nom, c.email, CONCAT(a.address, ' ', a.district, ' (', country, ')') adresse, role, active, a.address_id adresseID, c.customer_id customerID
            FROM user u
                INNER JOIN customer c ON c.customer_id = user_id
                INNER JOIN address  a ON a.address_id = c.address_id
                INNER JOIN city ON city.city_id = a.city_id
                INNER JOIN country ON country.country_id = city.country_id
            WHERE customer_id = ?";
    $param = array($customerId);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($param);

    $infos = $stmt->fetch();

    $html = '';

    $html.='
    <form action="'.FORM_URL.'" method="post" class="ui form container" id="formUpdate">

    <div class="three fields">
    <div class="seven wide field">
        <label>Prénom</label>
        <input type="text" name="first_name" value="' . $infos['prenom'] . '">
    </div>
    <div class="seven wide field">
        <label>Nom</label>
        <input type="text" name="last_name" value="' . $infos['nom'] . '">
    </div>
    <div class="three wide field">
        <label>Role</label>   
        <select name="role" class="ui search dropdown">';

        $nbRoles = 5;
        for($i = 1; $i <= $nbRoles; $i++){
            $html .= '<option '.($infos['role'] == $i ? 'selected' : '').' value="'.$i.'">'.$i.'</option>';
        }

    $html.='
        </select>
    </div>
    </div>
    <div class="two fields">
        <div class="fourteen wide field">
            <label>Email</label>
            <input type="text" name="login" value="' . $infos['email'] . '">
        </div>
        <div class="two wide field">
            <label>Active</label>
            <select name="active" class="ui search dropdown">';
                
            $nbActiveStates = 2;
            for($i = 0; $i < $nbActiveStates; $i++){
                $html .= '<option '.($infos['active'] == $i ? 'selected' : '').' value="'.$i.'">'.$i.'</option>';
            }

    $html.= '
            </select>
        </div>
    </div>
    <div class="field"> 
        <input id="id" name="id" type="hidden" value="'.$infos['customerID'].'">
    </div>
    <div class="field">
        <label>Adresse</label>
        <select name="adresse" class="ui search dropdown">
            <option value="' . $infos['adresseID'] . '">' . $infos['adresse'] . '</option>';

            /**
             * On récupère les données de toutes les adresses
             */

            $sql = "SELECT a.address_id, CONCAT(a.address, ' ', a.district, ' (', country, ')') adresse 
            FROM address a
                INNER JOIN city ON city.city_id = a.city_id
                INNER JOIN country ON country.country_id = city.country_id";

            $sth = $dbh -> prepare($sql);
            $sth -> execute();

            foreach($sth -> fetchAll() as $row){
                $html.= '<option '.($row['address_id'] == $infos['adresseID'] ? 'selected' : '').' value="' . $row['address_id'] . '">' . $row['adresse'] . '</option>';
            }
            
    $html.= 
        '</select>
    </div>
    </form>';

    echo $html;
    exit();

} catch (PDOException $e) {

    echo '<p> Échec de la connexion : ' . $e->getMessage() . '</p>';
}
