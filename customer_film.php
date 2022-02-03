<?php

include_once 'inc/globals.php';

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $customerId = htmlspecialchars($_POST['id']);
} else {
    $customerId = 1;
}

try{
    
    $dbh = new PDO(
        'mysql:host=' . SERVER . ';dbname=' . DBB . ';charset=UTF8',
        USER,
        PASS,
        PDO_OPTIONS
    );
    
    $sql = "SELECT CONCAT(title, ' (', release_year, ')') Film,CONCAT(length, ' min') Duree, description, rental_date Emprunt, return_date Retour
            FROM rental
                INNER JOIN inventory ON inventory.inventory_id = rental.inventory_id
                INNER JOIN film ON film.film_id = inventory.film_id
            WHERE customer_id = ?;";

    $stmt = $dbh -> prepare($sql);
    $params = array($customerId);
    $stmt -> execute($params);

    /**
     * On récupère le nom de chaque colonne de notre requête SQL précédente
     * @columnCount : (int) correspond au nombre de colonnes liées à notre requête SQL
     * @column : (string) contient le nom de la colonne $i
     * @sort : (string) Variable qui permet de toggle l'ordre de tri des colonnes (égal à ASC ou DESC) 
     * en fonction de l'ordre de tri actuel (donné par @sortBy)
     */

    $html = '';

    
    $html .= '<table class="ui celled table">';
    $html .= '<thead><tr>';
    
    $columnCount = $stmt -> columnCount();
    for($i = 0; $i < $columnCount; $i++){

        $column = $stmt -> getColumnMeta($i);

        $html .= '<th>'.$column["name"].'</th>';
    }

    $html .= '</tr></thead>';

    
    /**
     * On récupère les résultats de notre requête et on en affiche un par ligne
     * @row[x] correspond à la valeur de la x ième colonne de notre requête
     */

    $html .= '<tbody>';

    foreach($stmt -> fetchAll(PDO::FETCH_NUM) AS $row){
        $html .= '<tr>';
        for($i = 0; $i < $columnCount; $i++){
            $html .= '<td>'.$row[$i].'</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    echo $html;
    
    exit();

}catch(PDOException $e){

    echo 'Problème pdo !';

}