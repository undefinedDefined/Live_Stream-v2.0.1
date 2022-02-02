<?php


include_once 'inc/globals.php';

if(isset($_POST['id']) && !empty($_POST['id'])){
    $customerId = htmlspecialchars($_POST['id']);
}else{
    $customerId = 1;
}

try{
    $dbh = new PDO('mysql:host='.SERVER.';dbname='.DBB.';charset=UTF8', 
    USER, 
    PASS, 
    PDO_OPTIONS);
    
    $sql = "SELECT first_name, last_name, email, role 
            FROM customer 
            INNER JOIN user ON user.user_id = customer_id
            WHERE customer_id = ?";
    $param = array($customerId);
    $stmt = $dbh -> prepare($sql);
    $stmt -> execute($param);
    
    $infos = $stmt -> fetch();
    
    $html = '';
    
    $html .= '<form action="" class="ui form container">
    <div class="two fields">
    <div class="field">
        <label>Prénom</label>
        <input type="text" name="first-name" value="'.$infos['first_name'].'">
    </div>
    <div class="field">
        <label>Nom</label>
        <input type="text" name="last-name" value="'.$infos['last_name'].'">
    </div>
    </div>
    <div class="field">
        <label>Email</label>
        <input type="text" name="first-name" value="'.$infos['email'].'">
    </div>
    </form>';
    
    echo $html;
    exit();

}catch(PDOException $e){

    echo '<p> Échec de la connexion : ' . $erreur->getMessage().'</p>';

}