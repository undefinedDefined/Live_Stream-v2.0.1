<?php

define("APP_NAME", "Live Stream");

/*
* Fonction qui renvoi un entier 
* contenant le nombre de jours passés depuis la date passsée en paramètre
* @param int $dd : numéro du jour en 1 et 31
* @param int $mm : numéro du mois entre 1 et 12
* @param int $yyyy : numéro de l'année
* @return int du nombre de jours passés depuis la date demandée
* @author Sofiane
* @version 1.0
*/
function daysAgo(int $dd = 01, int $mm = 01, int $yyyy = 1970) : int
{
    define("SEC_DAY", 60 * 60 * 24);
    $start = strtotime("$yyyy-$mm-$dd");
    $daysAgo = (time() - $start) / SEC_DAY;
    return floor($daysAgo);
}

/*
* Fonction qui renvoi un élement HTML p
* contenant la durée écoulée au format XX mois, XX jours, XX heures et XX minutes
* @param int $dd : numéro du jour en 1 et 31
* @param int $mm : numéro du mois entre 1 et 12
* @param int $yyyy : numéro de l'année
* @return string paragraphe HTML contenant la durée écoulée depuis la date demandée
* @author Sofiane
* @version 1.0
*/

function timeAgo(int $dd = 01,int $mm = 01,int $yyyy = 1970): string {

    $html = "Temps écoulé depuis la date demandée : ";

    $start = strtotime("$yyyy-$mm-$dd"); // timestamp de la date rentrée en paramètre
    $time = time() - $start; // timestamp actuel

    
    $min = floor($time / 60) ;
    if($min > 59){
        $heure = floor($min / 60);
        $min -= $heure * 60;
        if($heure > 23){
            $jour = floor($heure / 24);
            $heure -= $jour * 24;
            if($jour > 29){
                $mois = floor($jour / 30);
                $jour -= $mois * 30;
            }
        }
    }

    isset($mois) ? $html .= "$mois mois, " : "";
    isset($jour) ? $html .= "$jour jours, " : "";
    isset($heure) ? $html .= "$heure heures, " : "";

    $html .= "et $min minutes.";

    return $html;

}

$weekdays = [
    'lu' => 'Lundi',
    'ma' => 'Mardi',
    'me' => 'Mercredi',
    'je' => 'Jeudi',
    've' => 'Vendredi'
];

$weekend = [
    'sa' => 'Samedi',
    'di' => 'Dimanche'
];

$week = array_merge($weekdays, $weekend);

/*
* Fonction qui renvoi une liste à partir d'un tableau passé en paramètre
* @param array $data : tableau associatif simple
* @param $ordered : true = liste ordonnée / false = liste non ordonnée
* @return string : element HTML contenant la liste
*/
function build_list(array $data, bool $ordered=false): string {
    $html = "";

    if($ordered){
        $html = '<ol>%s</ol>';
    }else{
        $html = '<ul>%s</ul>';
    }

    $items = '';

    foreach($data as $val){
        $items .= '<li>'.$val.'</li>';
    }
     
    return sprintf($html, $items);
}

/*
* Fonction qui renvoie un mot aléatoire compris entre 8 et 16 caractères
* @return string
* @version 1.0
*/
function create_pass() : string {
    $consonnes = "bcdfghjklmnpqrstvwxz";
    $voyelles = "aeiouy";
    $randomString = '';
    for ($i = 0 ; $i < rand(4, 8); $i++) {
        $randomString .= $consonnes[rand(0, strlen($consonnes) - 1)].$voyelles[rand(0, strlen($voyelles) - 1)];
    }
    return $randomString;
}

/* 
* Fonction qui renvoi true si le paramètre est une date, renvoi false sinon
* @param $date : verifie si c'est une date
* @return bool : true si c'est une date / false sinon
*/
function is_date($date) : bool {
    return strtotime($date);
}

/*
* Fonction qui renvoi la différence en années entre les deux date passées en paramètre
* @param string $date1
* @param string $date2
* @return int le nombre d'années entre les deux dates demandées
*/
function age(string $date1, string $date2) : string {
    define("SEC_YEAR", 60*60*24*365.25);

    if(!is_date($date1)|| !is_date($date2)) {
        trigger_error("L'un des arguments n'est pas une date", E_USER_WARNING);
        exit;
    }

    $alpha = strtotime("$date1");
    $beta = strtotime("$date2");

    if($alpha > $beta){
        $gamma = $alpha - $beta;
    }elseif($alpha < $beta){
        $gamma = $beta - $alpha;
    }else{
        $gamma = 0;
    }

    $lambda = floor($gamma / SEC_YEAR);

    return $lambda;
}


/*
* Fonction qui renvoi le prix TTC à partir d'un prix HT et d'un taux de TVA
* @param float $prixHT
* @param float $TVA (20%, 10%, 5.5%)
* @return string le prix TTC
*/

function prixTTC(float $prixHT, float $TVA=0.2) : float {
    $taux = [0.2, 0.1, 0.055];

    if(!is_float($TVA)){
        $TVA /= 100;
    }

    if(!in_array($TVA, $taux) || $prixHT < 0){
        trigger_error("Le taux de TVA n'est pas valide", E_USER_WARNING);
    }

    $prixTTC = $prixHT * (1 + $TVA);

    return $prixTTC;
}

/*
* Fonction qui renvoi le code HTML d'un tableau de taille $nb * $nb
* contenant au croisement de chaque colonne le résultat de la multiplication 
* @param int $nb : taille du tableau
* @return string élement HTML de type tableau
*/
function excel(int $nb=10): string {
    $html = "";
    $html .= '<table style="border: 1px solid black; border-collapse: collapse"';

    // Indexes colonnes
    $html .= '<tr>';
    $html .= '<th scope="col" style="padding : 5px; background : #F3BAC3"></th>';
    for($i = 1; $i <= $nb; $i++){
        $html .= "<th scope=\"col\" style=\"border-bottom : 1px solid black; padding: 5px; background : #F3BAC3\">$i</th>";
    }
    $html .= "</tr>";

    for($i = 1 ; $i <= $nb; $i++){
        // Indexe lignes
        $html .= "<tr>
        <th scope=\"row\" style=\"border-right : 1px solid black; padding: 5px; background : #F3BAC3\">$i</th>";
        // valeurs des multiplications
        for($z = 1; $z <= $nb; $z ++){
            $result = $i * $z;
            $i == $z ? $html .= '<td style="padding : 5px; text-align: center; border: 1px solid black; background: #F9DCE1">'.$result.'</td>' : $html .= "<td style=\"padding : 5px; text-align: center; border: 1px solid black\">".$result."</td>";
        }
        $html .= "</tr>";
    }
    $html .= '<table>';

    return $html;
}


/*
* Fonction qui renvoi un decimal
* correspond à la moyenne calculée des paramètres 
* @param array : 1 seul paramètre de type tableau
* @param numeric : plusieurs paramètres de type numerique
* @return float moyenne calculée des paramètres numériques
* @author Sofiane
* @version 1.0
*/
function average(){
    $somme = 0;
    $tabArgs = [];
    $nbArgs = 0;
    $nbFalse = 0;
    if(func_num_args() === 1 && is_array(func_get_arg(0))){
        $tabArgs = func_get_arg(0);
    }else{
        $tabArgs = func_get_args();    
    }

    foreach($tabArgs AS $val){
        if(is_numeric($val)){
            $somme += $val;
            $nbArgs++;
        }else{
            $nbFalse++;
        }
    }

    $moyenne = $somme / $nbArgs;
    $nbFalse != 0 ? trigger_error("$nbFalse argument(s) invalide(s) !", E_USER_WARNING) : "" ;

    return $moyenne;
}