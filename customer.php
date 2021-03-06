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
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Stream : Utilisateurs</title>

      <!-- Scripts Semantic-UI et jQuery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/customer.css">
</head>
<body>
    
<div class="ui grid container">

<div class="row">
    <div class="column">
        <h2 class="ui dividing middle centered header" style="margin: 3rem 0 0 0 ">Tableau des utilisateurs</h2>
    </div>
</div>

<?php

/**
 * On récupère le code d'état des Insert/ Update dans la cas d'une requête GET
 */

if(isset($_GET['code']) && !empty($_GET['code']) || isset($_GET['code']) && $_GET['code'] === '0'){
    switch($_GET['code']){
        case 0 :
            $col = 'warning';
            $header = 'Une erreur s\'est produite.';
            $msg = 'La modification a echoué. Veuillez réessayer.';
            break;
        case 1 :
            $col = 'success';
            $header = 'Succès';
            $msg = 'Modifications effectuées avec succès';
            break;
    }

    if(isset($col) && isset($msg)){
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
    }

}

?>

<div class="row">
    <div class="column">
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

        $sql = "SELECT user_id AS code, first_name AS Prénom, role, email AS email, active, last_update AS MAJ
                FROM customer
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
        $html .= '<th>Locations</th>';
        
        $html.= '</tr></thead>';
        
        
        /**
         * On récupère les résultats de notre requête et on en affiche un par ligne
         * @row[x] correspond à la valeur de la x ième colonne de notre requête
         */

        $html.= '<tbody>';
        
        foreach($stmt -> fetchAll(PDO::FETCH_NUM) AS $row){
            $html .= '<tr>';
            for($i = 0; $i < $columnCount; $i++){
                $html .= '<td>'.$row[$i].'</td>';
            }
            $html .= '<td style="text-align:center;"><i data-id="'.$row[0].'" class="updateUser edit outline icon ajax"></i></td>';
            $html .= '<td style="text-align:center;"><i data-id="'.$row[0].'" class="viewLoc eye icon ajax"></i></td>';
            
            $html .= '</tr>';
        }
        
        $html.= '</tbody>';
        $html .= '</table>';
        
        echo $html; 
        
        echo '</div>
        </div>';

        // On appelle notre fichier pagination.php
        include_once 'inc/pagination.php';

        // Une fois les données récupérées, on ferme notre instance PDO
        unset($dbh);

    }catch(PDOException $e){

        echo '<p> Échec de la connexion : ' . $e->getMessage() . '</p>';

    }

    ?>
    
</div>

<!-- 
    Modal pour modifier les utilisateurs
-->
 
<div class="ui coupled modal" id="updateUser">
  <i class="close icon"></i>
    <div class="header">
        Informations utilisateur 
    </div>
    <div class="content">
        <!-- résultat de customer_info.php via notre requête Ajax -->
    </div>
    <div class="actions">
        <div class="ui close button">Annuler</div>
        <button class="ui primary right labeled icon submit button" id="submitUpdateUser">
            <i class="right arrow icon"></i>
            Modifier
        </button>
    </div>
</div>

<!-- Modal confirmation modification utilisateur -->

<div class="ui coupled mini modal" id="confirmUpdateUser">
  <div class="header">Modification utilisateur</div>
  <div class="content">
    <p>Etes vous sûr de vouloir modifier cet utilisateur ?</p>
  </div>
  <div class="actions">
    <div class="ui negative cancel button">Annuler</div>
    <div class="ui positive approve button">Confirmer</div>
  </div>
</div>

<!-- Modal pour voir les locations de chaque utilisateur -->

<div class="ui modal" id="viewLoc">
  <i class="close icon"></i>
    <div class="header">
        Locations utilisateur 
    </div>
    <div class="scrolling content">
       <!-- Liste des emprunts inséré grâce à Ajax -->
    </div>
    <div class="actions">
        <div class="ui close button">Fermer</div>
    </div>
</div>


<!-- Javascript personnalisé : choisir la méthode voulue (changer également dans customer_info.php) -->

<!-- <script src="js/customer.js"></script> -->
<script src="js/customer_ajax.js"></script>

</body>
</html>