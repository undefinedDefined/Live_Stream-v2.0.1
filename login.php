<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>Live Stream : Connexion</title>

  <!-- Scripts Semantic-UI et jQuery -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
  <!-- CSS personnalisé -->
  <link rel="stylesheet" href="css/login.css">
</head>

<body>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui black image header">
      <div class="content">
        Se connecter
      </div>
    </h2>
    <form action="login_check.php" method="post" class="ui large form">
      <div class="ui stacked segment">

        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="login" placeholder="Adresse Email">
          </div>
        </div>

        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Mot de passe">
          </div>
        </div>

        <button class="ui animated fluid large black submit button">
                <div class="visible content">Connexion</div>
                <div class="hidden content">
                    <i class="right sign-in icon"></i>
                </div>
            </button>
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      Pas encore de compte ? <a href="register.php">S'inscrire</a>
    </div>
  </div>
</div>

<script src="js/login.js"></script>
</body>
</html>
