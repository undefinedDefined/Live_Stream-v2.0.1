<?php

include_once 'inc/functions.php';
include_once 'inc/team.php';
include_once 'inc/globals.php';
session_start();

/**
 * Vérifications des valeurs de connexion
 * @isauth (bool) : correspond à l'état de connexion (true pour connecté, false sinon)
 * @role (int) : correspond au role de l'utilisateur connecté (égal à 0 si aucune connexion)
 */

if(!isset($_SESSION['isauth']) || !$_SESSION['isauth']){
    $isauth = false;
  }else {$isauth = true;}

if(isset($_SESSION['role']) && !empty($_SESSION['role'])){
  $role = htmlspecialchars($_SESSION['role']);
}else{$role = 0;}

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

<div class="ui grid container">

    <div class="row">
        <div class="column">
            <div class="ui padded segment">

                <h1 class="ui dividing header">
                    <img class="ui image" src="img/icon.gif">
                    <div class="content">
                        <?php echo APP_NAME ?>
                        <div class="sub header">Site de location de films de qualité</div>
                    </div>
                </h1>

                <p>Bienvenue sur la plateforme <?php echo APP_NAME ?>. Ce site a été mis en ligne par la Garamont Coders Crew il y a 
                <?php echo daysAgo(13,01,2022) ?>  
                jours. Elle permet de louer des films HD en ligne.</p>

                <!-- 
                  On affiche les bon boutons en fonction de l'état de connexion de l'utilisateur, et de son rôle
                 -->
                <div class="ui labeled button" <?php echo $isauth? 'style="display:none"' : ''; ?>>
                  <a class="ui primary button" href="login.php">
                    <i class="sign-in icon"></i>
                    Connexion
                  </a>
                </div>

                <div class="ui labeled button" <?php echo $isauth? 'style="display:none"' : ''; ?>>
                  <a class="ui inverted primary button" href="register.php">
                    <i class="edit icon"></i>
                    Inscription
                  </a>
                </div>

                <div class="ui labeled button" <?php echo !$isauth? 'style="display:none"' : ''; ?>>
                  <a class="ui primary button" href="logout.php">
                    <i class="sign-out icon"></i>
                    Deconnexion
                  </a>
                </div>

                <div class="ui labeled button" <?php echo  $role == 5 ? '' : 'style="display:none"'; ?>>
                  <a class="ui inverted primary button" href="#">
                    <i class="pencil icon"></i>
                    Utilisateur
                  </a>
                </div>

            </div>
        </div>
    </div>
        
        <?php

        /**
         * On affiche un message différent en fonction du code reçu par la méthode GET
         * La couleur et le message varient grâce à un switch
         * On n'affiche aucun message si le code ne correspond pas à {0,1,2,3,4,5}
         */

        if(isset($_GET['code']) && !empty($_GET['code']) || isset($_GET['code']) && $_GET['code'] === '0'){
            switch($_GET['code']){
                case 0 :
                    $col = 'warning';
                    $header = 'Nom d\'utilisateur ou mot de passe incorrect.';
                    $msg = 'Veuillez réessayer ou visiter notre page d\'inscription';
                    break;
                case 1 :
                    $col = 'success';
                    $header = 'Bienvenue ';
                    $isauth ? $header .= $_SESSION['fname']." !" : " !";
                    $msg = 'Vous pouvez maintenant accéder aux fonctionnalités de notre site';
                    break;
                case 2 :
                    $col = 'error';
                    $header = 'Oops ! Un problème est survenu';
                    $msg = 'Veuillez réessayer';
                    break;
                case 4 :
                    $col = 'info';
                    $header = 'Deconnexion reussie';
                    $msg = 'Nous espérons vous revoir très prochainement';
                    break;
                case 5 :
                    $col = 'success';
                    $header = 'Votre inscription a bien été prise en compte.';
                    $msg = 'Vous pouvez dès à présent vous connecter avec les identifiants que vous avez choisi';
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
            <h2 class="ui dividing header">
                Les membres de l'équipe
            </h2>
            
            <div class="ui link stackable four cards middle centered">
                
                <?php

                /**
                 * On crée une carte pour chaque membre de notre tableau associatif contenu dans inc/team.php
                 * L'image de chaque membre varie en fonction de son sexe, aléatoirement parmi le nombre d'image de chaque
                 */

                foreach($crew as $membre){

                    $img = $membre['sexe'] == "F" ? 'img/female'.rand(1,3).'.svg' : 'img/male'.rand(1,6).'.svg';

                    $html = '';

                    $html .= '
                        <div class="fluid card">';
                    
                    $membre["fname"] == 'Sofiane' ? 
                    $html .= '
                            <a class="ui yellow right corner label">
                            <i class="star icon"></i>
                            </a>' : '';
                            
                    $html .= '
                            <div class="image">
                                <img src="'.$img.'">
                            </div>
                            <div class="content">
                                <div class="header">'.$membre['fname'].'</div>
                                <div class="meta">
                                    <p>'.$membre['age'].' ans</p>
                                </div>
                                <div class="description">
                                    '.$membre['fname'].' aime : '.implode(", ", $membre["hobbies"]).'
                                </div>
                            </div>
                            <div class="extra content">
                            <span class="right floated">
                                Joined in 2022
                            </span>
                            <span>
                                <i class="user icon"></i>
                                14 Friends
                            </span>
                            </div>
                        </div>';

                    echo $html;

                }

                ?>

            </div>
        </div>
    </div>
</div>

<script>
  // Script pour fermer le message
  $('.message .close').click(function() {
    $(this).closest('.message').transition('fade');
  });
</script>
</body>
</html>