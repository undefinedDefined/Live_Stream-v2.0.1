<?php


include_once 'inc/globals.php';

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

    $sql = "SELECT c.first_name prenom, c.last_name nom, c.email, CONCAT(a.address, ' ', a.district, ' (', country, ')') adresse, role, phone telephone, active, a.address_id adresseID, c.customer_id customerID
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

    $html .= '<form action="customer_update.php" method="post" class="ui form container" id="formUpdate">
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
        <select name="role" class="ui search dropdown">
            <option value="' . $infos['role'] . '">' . $infos['role'] . '</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    </div>
    <div class="three fields">
        <div class="ten wide field">
            <label>Email</label>
            <input type="text" name="login" value="' . $infos['email'] . '">
        </div>
        <div class="four wide field">
            <label>Telephone</label>
            <input type="text" name="phone" value="' . $infos['telephone'] . '">
        </div>
        <div class="two wide field">
            <label>Active</label>
            <select name="active" class="ui search dropdown">
                <option value="' . $infos['active'] . '">' . $infos['active'] . '</option>
                <option value="'.($infos['active'] == 1 ? 0 : 1).'">'.($infos['active'] == 1 ? 0 : 1).'</option>
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
             * On récupère les données de toutes les adresses qui ne correspondent pas à celle de l'utilisateur
             */

            $sql = "SELECT a.address_id, CONCAT(a.address, ' ', a.district, ' (', country, ')') adresse 
            FROM address a
                INNER JOIN city ON city.city_id = a.city_id
                INNER JOIN country ON country.country_id = city.country_id
            WHERE NOT address_id  = ?";

            $params = array($infos['adresseID']);
            $sth = $dbh -> prepare($sql);
            $sth -> execute($params);

            foreach($sth -> fetchAll() as $row){
                $html.= '<option value="' . $row['address_id'] . '">' . $row['adresse'] . '</option>';
            }
            
    $html.= 
        '</select>
    </div
    </form>';

    echo $html;
    exit();

} catch (PDOException $e) {

    echo '<p> Échec de la connexion : ' . $e->getMessage() . '</p>';
}
