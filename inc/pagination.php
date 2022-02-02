<?php

$html = '';

/**
 * Requête pour récupérer le nombre de données à insérer dans notre tableau
 * A noter : Ce fichier sera inclu dans notre customer.php qui contient déjà une instance PDO
 * et ne nécessite donc pas d'en instancier une nouvelle
 */

$stmt = $dbh -> prepare("SELECT * FROM customer");
$stmt -> execute();

/**
 * Variables utiles pour la mise en place de la pagination 
 * @paginationFin (int) : le numéro de page maximum en fonction des valeurs à afficher
 * PAGINATION_RANGE (int) : constante qui définit le nombre total de boutons à afficher dans la pagination
 * Remarque :   PAGINATION_RANGE (paire) => pagination avec 1 bouton de plus que demandé
 *              PAGINATION_RANGE (impaire) = > pagination exacte
 * 
 * @paginationSide (int) : correspond au nombre de boutons à afficher de chaque côté du bouton de page active
 * @paginationLimit (int) : correspond au bouton de page active 
 */

$paginationFin = floor($stmt->rowCount() / 10);
define('PAGINATION_RANGE', 8);
$paginationSide = floor(PAGINATION_RANGE / 2);
$paginationLimit = $paginationSide + 1;

$html .= '<div class="three column stackable row">';

// Colonne vide pour la mise en page responsive
$html .= '<div class="three wide column"></div>';

    // Colonne contenant notre pagination
    $html .= '<div class="ten wide column center aligned">';
    $html .= '<div class="ui pagination menu">';

    // Boutons Première page/ Page précédente : disabled si la page active = 1
    if($numeroPage == 1){

        $html .= '<a class="icon item disabled"><i class="angle double left icon"></i></a>';
        $html .=  '<a class="icon disabled item"><i class="angle left icon"></i></a>';

    }else{

        $html .= '<a class="icon item" href="customer.php?page=1&sortName='.$sortName.'&sortBy='.$sortBy.'"><i class="angle double left icon"></i></a>';
        $html .=  '<a class="icon item" href="customer.php?page='.($numeroPage-1).'&sortName='.$sortName.'&sortBy='.$sortBy.'"><i class="angle left icon"></i></a>';

    }

    // Boutons principaux
    if($numeroPage <= $paginationLimit){

        $x = PAGINATION_RANGE - ($numeroPage - 1);

        for($i = 1; $i <= ($numeroPage + $x); $i++){
            $i == $numeroPage ? 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="active item">'.$i.'</a>' : 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$i.'</a>';
        }

    }elseif($numeroPage > $paginationLimit && $numeroPage <= ($paginationFin - $paginationSide)){

        for($i = $numeroPage - $paginationSide; $i <= ($numeroPage + $paginationSide); $i++){
            $i == $numeroPage ? 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="active item">'.$i.'</a>' : 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$i.'</a>';
        }

    }else{

        $x = PAGINATION_RANGE - ($paginationFin - $numeroPage);

        for($i = ($numeroPage - $x); $i <= $paginationFin; $i++){
            $i == $numeroPage ? 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="active item">'.$i.'</a>' : 
                $html .= '<a href="customer.php?page='.$i.'&sortName='.$sortName.'&sortBy='.$sortBy.'" class="item">'.$i.'</a>';
        }

    }

    // Boutons Page précédente/ Dernière page : disabled si page active = dernière page
    if($numeroPage == $paginationFin){

        $html .=  '<a class="icon disabled item"><i class="angle right icon"></i></a>';
        $html .= '<a class="icon item disabled"><i class="angle double right icon"></i></a>';

    }else{

        $html .=  '<a class="icon item" href="customer.php?page='.($numeroPage+1).'&sortName='.$sortName.'&sortBy='.$sortBy.'"><i class="angle right icon"></i></a>';
        $html .= '<a class="icon item" href="customer.php?page='.$paginationFin.'&sortName='.$sortName.'&sortBy='.$sortBy.'"><i class="angle double right icon"></i></a>';

    }

    $html .= '</div>';
    $html .= '</div>';

// Colonne contenant le bouton 'Ajouter"
$html .= '  <div class="three wide column right aligned">
            <button class="ui right labeled icon basic button"><i class="plus icon"></i>Ajouter</button>
            </div>';

$html .= '</div>';

echo $html;