<?php

include_once 'inc/globals.php';

?>

<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>Live Stream : Inscription</title>

  <!-- Scripts Semantic-UI et jQuery -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
  <!-- CSS personnalisé -->
  <link rel="stylesheet" href="css/register.css">
</head>

<body>
    
<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui black image header">
      <div class="content">
        Inscription à Live Stream
      </div>
    </h2>
    <form action="" method="post" class="ui large form">
        <div class="ui stacked segment">

            <div class="two fields">
                <div class="required field">
                    <label for="">Prénom</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Harry">
                </div>

                <div class="required field">
                    <label for="last_name">Nom</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Potter">
                </div>
            </div>

            <div class="required field">
                <label for="">Email</label>
                <input type="email" name="login" id="login" placeholder="email@example.com">
            </div>

            <div class="required field">
                <label for="">Adresse</label>
                <select name="address" class="ui search dropdown">
                    <option value="">Adresse</option>

                    <?php 

                    try{

                        // Connexion à la BDD
                        $dbh = new PDO('mysql:host='.SERVER.';dbname='.DBB.';charset=UTF8', 
                        USER, 
                        PASS, 
                        PDO_OPTIONS);

                        $sql = "SELECT address_id, CONCAT(address, ', ', postal_code, ', ', city, ' (', country, ')') AS address
                        FROM address
                        INNER JOIN city ON city.city_id = address.city_id
                        INNER JOIN country ON country.country_id = city.country_id";
                        $stmt = $dbh -> prepare($sql);
                        $stmt -> execute();

                        $options = '';
                        foreach($stmt->fetchAll() AS $row){
                        $options .= '<option value="'.$row['address_id'].'">'.$row['address'].'</option>';
                        }
                        echo $options;


                    }catch(PDOException $e){
                        echo '<p class="alert alert-danger"> Échec de la connexion : ' . $e->getMessage().'</p>';
                    }

                    ?>
                </select>
            </div>

            <div class="required field">
                <label for="">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>

            <div class="required field">
                <label for="">Confirmez le mot de passe</label>
                <input type="password" name="password_check" id="password_check">
            </div>

            <div class="inline field">
                <div class="ui checkbox">
                    <input type="checkbox" name="cguCheck">
                    <label>J'accepte les conditions générales d'utilisation</label>
                </div>
            </div>


            <button class="ui animated fluid large black submit button">
                <div class="visible content">Inscription</div>
                <div class="hidden content">
                    <i class="right edit icon"></i>
                </div>
            </button>

      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
    </div>
  </div>
</div>

<!-- Script JS  -->
<script src="js/register.js"></script>

</body>
</html>