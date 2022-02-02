<?php

include_once 'inc/globals.php';

/**
 * Initialisation des variables pour la pagination
 * @numeroPage : correspond au numéro de page
 * @numeroPage est égal à 1 de base et ne peut pas être nul ou négatif
 * @premiereValeur permet d'afficher les bonnes valeurs dans le tableau en fonction du numéro de page
 */

if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0){
    $numeroPage = $_GET['page'];
}else{
    header('Location: customer.php?page=1&sortName=user_id&sortBy=asc');
    exit;
}

$premiereValeur = 0 + (($numeroPage - 1) * 10);


/**
* Initialisation des valeurs pour l'ordre d'affichage des élements du tableau
* @sortCondition : (array) contient les valeurs autorisées pour l'ordre d'affichage
* @nameCondition : (array) contient les valeurs autorisées pour le nom des colonnes
* @sortBy : (string) de base égal à 'asc' (ordre croissant)
* @sortName : (string) de base égale à 'user_id' (tri par rapport à l'id des utilisateurs)
 */

$sortCondition = array('asc', 'desc');
if(isset($_GET['sortBy']) && !empty($_GET['sortBy']) && in_array(strtolower($_GET['sortBy']), $sortCondition)){
    $sortBy = $_GET['sortBy'];
}else{
    $sortBy = 'asc';
}

$nameCondition = array('user_id', 'first_name', 'role', 'email', 'active', 'last_update');
if(isset($_GET['sortName']) && !empty($_GET['sortName']) && in_array(strtolower($_GET['sortName']), $nameCondition)){
    $sortName = $_GET['sortName'];
}else{
    $sortName = 'user_id';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

      <!-- Scripts Semantic-UI et jQuery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
</head>
<body>
    
<div class="ui container">

<h2 class="ui dividing middle centered header" style="margin: 3rem 0">Tableau des utilisateurs</h2>

<table class="ui striped center aligned celled selectable table">

<?php

try{

    /**
     * On instancie PDO dans notre variable $dbh avec nos constantes définies dans inc/globals.php
     * @sql : notre requête SQL contenant trois paramètres
     * @sortName : (string) correspond à la colonne par rapport à laquelle on tri (de base user_id)
     * @sortBy : (string) correspond à l'ordre de tri (croissant ou décroissant : de base croissant)
     */

    $dbh = new PDO('mysql:host='.SERVER.';port='.PORT.';dbname='.DBB.';charset=utf8', 
    USER, 
    PASS, 
    PDO_OPTIONS);

    $sql = "SELECT user_id AS code, LOWER(first_name) AS Prénom, role, LOWER(email) AS email, active, last_update AS MAJ FROM customer
            INNER JOIN user ON user.user_id = customer_id
            ORDER BY $sortName $sortBy
            LIMIT $premiereValeur,10";
    
    $stmt = $dbh -> prepare($sql);
    $stmt -> execute();
    
    /**
     * On récupère le nom de chaque colonne de notre requête SQL précédente
     * @columnCount : (int) correspond au nombre de colonnes liées à notre requête SQL
     * @column : (string) contient le nom de la colonne $i
     * @sort : (string) Variable qui permet de toggle l'ordre de tri des colonnes (égal à ASC ou DESC) 
     * en fonction de l'ordre de tri actuel (donné par @sortBy)
     */
    
    $html = '';
    
    $html .= '<thead><tr>';
    
    $columnCount = $stmt -> columnCount();
    for($i = 0; $i < $columnCount; $i++){
        $column = $stmt -> getColumnMeta($i);
        
        $sort = $sortBy == 'asc' ? 'desc' : 'asc';
        $html .= '<th><a href="customer.php?page='.$numeroPage.'&sortName='.$column["name"].'&sortBy='.$sort.'">'.$column["name"].'</a></th>';
    }
    $html .= '<th>Modifier</th>';
    
    $html.= '</tr></thead>';
    
    
    /**
     * On récupère les résultats de notre requête et on en affiche un par ligne
     * @row[x] correspond à la valeur de la x ième colonne de notre requête
     * 
     */
    $html.= '<tbody>';
    
    foreach($stmt -> fetchAll(PDO::FETCH_NUM) AS $row){
        $html .= '<tr>';
        for($i = 0; $i < $columnCount; $i++){
            $html .= '<td>'.$row[$i].'</td>';
        }
        $html .= '<td style="text-align:center;"><a data-id="'.$row[0].'" class="updateUser"><i class="edit outline icon"></i></a></td>';
        
        $html .= '</tr>';
    }
    
    $html.= '</tbody>';
    $html .= '</table>';
    
    echo $html; 
    
    /**
     * On crée notre pagination pour naviguer entre les différentes pages de notre tableau
     * @paginationFin (int) : correspond au nombre max de pages nécessaires pour contenir toutes les données demandée
     * On affiche différemment notre pagination en fonction de la page sur laquelle on se trouve
     */
    
    $html = '';
    
    $html .= '<div class="ui pagination menu">';
    
    $stmt = $dbh -> prepare("SELECT * FROM customer");
    $stmt -> execute();
    $paginationFin = floor($stmt->rowCount() / 10);

    if($numeroPage < 4){
        
        for($i = 1; $i < 5; $i++){
            $i == $numeroPage ? 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="active item">'.$i.'</a>' : 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$i.'</a>';
        }

        $html .=  '<div class="disabled item">..</div>';
        $html .=  '<a href="customer.php?page='.$paginationFin.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$paginationFin.'</a>';

    }elseif($numeroPage >= 4 && $numeroPage < $paginationFin - 3){

        $html .= '<a href="customer.php?page=1&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">1</a>';
        $html .=  '<div class="disabled item">..</div>';

        for($i = $numeroPage - 1; $i < $numeroPage + 2; $i++){
            $i == $numeroPage ? 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="active item">'.$i.'</a>' :
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$i.'</a>';
        }

        $html .= '<div class="disabled item">..</div>';
        $html .= '<a href="customer.php?page='.$paginationFin.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$paginationFin.'</a>';

    }else{

        $html .= '<a href="customer.php?page=1&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">1</a>';
        $html .= '<div class="disabled item">..</div>';

        for($i = $numeroPage - 3; $i <= $paginationFin; $i++){
            $i == $numeroPage ? 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="active item">'.$i.'</a>' :
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$i.'</a>';
        }
    }

    /**
     * Fin pagination
     */

    echo $html;


}catch(PDOException $e){

    echo "Erreur PDO !";

}

?>

</div>

<!-- 
    Modal pour modifier les utilisateurs
 -->
 
<div class="ui modal">
  <i class="close icon"></i>
    <div class="header">
        Informations utilisateur 
    </div>
    <div class="ajax content">
        <!-- Formulaire client inséré grâce à Ajax -->
    </div>
    <div class="actions">
        <div class="ui button submit">Submit</div>
        <div class="ui button">Cancel</div>
    </div>
</div>

<script src="js/customer.js"></script>
</body>
</html>