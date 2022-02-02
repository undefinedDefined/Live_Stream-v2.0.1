<?php

include_once 'functions.php';

$girls = array('Rokia', 'Raf', 'Maelis', 'Ines', 'Joelle');

echo build_list($girls, true);
echo build_list($girls);

function createPassword(int $longueur = 10) : string {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($caracteres);
    $randomString = '';
    for ($i = 0; $i < $longueur; $i++) {
        $randomString .= $caracteres[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/* $tab = [];
for($i = 0; $i < 20; $i++){
    array_push($tab, createPassword(5));
}

var_dump($tab); */

function createPass(int $longueur = 10) : string {
    $consonnes = ["b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "z" ];
    $voyelles = ["a", "e", "i", "o", "u", "y"];
    $randomString = '';
    for ($i = 0; $i < ($longueur/2); $i++) {
        $randomString .= $consonnes[rand(0, count($consonnes) - 1)];
        $randomString .= $voyelles[rand(0, count($voyelles) - 1)];
    }
    return $randomString;
}

/* $tab2 = [];
for($i = 0; $i < 20; $i++){
    array_push($tab2, createPass(4));
}

var_dump($tab2); */

function create_password(int $longueur = 10) : string {
    $consonnes = "bcdfghjklmnpqrstvwxz";
    $voyelles = "aeiouy";
    $randomString = '';
    for ($i = 0; $i < ($longueur/2); $i++) {
        $randomString .= $consonnes[rand(0, strlen($consonnes) - 1)];
        $randomString .= $voyelles[rand(0, strlen($voyelles) - 1)];
    }
    return $randomString;
}

// $tab3 = [];
// for($i = 0; $i < 20; $i++){
//     array_push($tab3, create_password(5));
// }

// var_dump($tab3);

// Même fonction qui renvoi aléatoirement un mdp entre 8 et 16 caractères

/*
*
*/

// function create_pass() : string {
//     $consonnes = "bcdfghjklmnpqrstvwxz";
//     $voyelles = "aeiouy";
//     $randomString = '';
//     for ($i = 0 ; $i < rand(4, 8); $i++) {
//         $randomString .= $consonnes[rand(0, strlen($consonnes) - 1)].$voyelles[rand(0, strlen($voyelles) - 1)];
//     }
//     return $randomString;
// }

$tab = [10,20,10,20];

echo average($tab);