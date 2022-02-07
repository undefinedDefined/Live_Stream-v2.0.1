<?php

require_once 'table.class.php';

/**
 * Requête SQL pour laquelle on veut récupérer les informations
 */

$sql = "SELECT customer_id, first_name, last_name, email FROM customer";

/**
 * Instanciation de notre classe Table qui permet de récupérer diverses informations sur notre requête
 */

$table = new Table($sql);

/**
 * Tableau qui permet de limiter l'ordre de tri aux valeurs croissant ou décroissant
 */

$sortCondition = array('asc', 'desc');

/**
 * Récupération du nom des colonnes pour limiter l'ordre de tri par rapport au nom
 */

$nameCondition = $table->get_columns_names();

/**
 * Récupération de l'ordre de tri (croissant ou décroissant)
 * Valeur par défaut : ASC = croissant
 */

if (isset($_GET['sortBy']) && !empty($_GET['sortBy']) && in_array(strtolower($_GET['sortBy']), $sortCondition)) {
    $sortBy = htmlspecialchars($_GET['sortBy']);
} else {
    $sortBy = $sortCondition[0];
}

/**
 * Récupération de l'ordre de tri par rapport au nom
 * Valeur par défaut : nom de la première colonne
 */

if (isset($_GET['sortName']) && !empty($_GET['sortName']) && in_array(strtolower($_GET['sortName']), $nameCondition)) {
    $sortName = htmlspecialchars($_GET['sortName']);
} else {
    $sortName = $nameCondition[0];
}

/**
 * Récupération du numéro de page, redirection vers page 1 si introuvable ou invalide
 */

if (isset($_GET['page']) && !empty($_GET['page']) && (int) $_GET['page'] > 0) {
    $nbPage = htmlspecialchars($_GET['page']);
} else {
    header('Location: index.php?page=1&sortName='.$nameCondition[0].'&sortBy='.$sortCondition[0].'');
    exit;
}

/**
 * On récupère une nouvelle fois le nom des colonnes pour les afficher dans le tableau 
 * (clairement pas nécessaire mais je préfère que ça reste lisible)
 */

$colnames = $table->get_columns_names();

/**
 * On fixe l'offset (numéro à partir duquel afficher les lignes) calculé à partir du numéro de page
 * Puis on récupère toutes les données concernant notre requête SQL
 * Nb de lignes par page par défaut = 10 (à modifier dans les deux méthodes)
 */

$table->set_offset($nbPage);
$rows = $table->get_table_infos($sortName,$sortBy, null);


include_once 'vue.php';