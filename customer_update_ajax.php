<?php

include_once 'inc/globals.php';


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
        
        $col = 'success';
        $header = 'Succès';
        $msg = 'Modifications effectuées avec succès';

    }else{

        $col = 'warning';
        $header = 'Une erreur s\'est produite.';
        $msg = 'La modification a echoué. Veuillez réessayer.';

    }

    echo '    
          <div class="row">
            <div class="column">
              <div class="ui '.$col.' message">
                  <i class="close icon"></i>
              <div class="header">
                  '.$header.'
              </div>
                  '.$msg.'
              </div>
            </div>
          </div>';
    



}catch(PDOException $e){

    echo 'Probleme PDO !';

}