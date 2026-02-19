<?php
session_start();

# Redirige vers l'accueil si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: MenuApresCo.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="barre-navigation">
    <div class="logo">TravelBook</div>
    <div class="menu-navigation">
      <a class="btn-menu" href="index.php">Accueil</a>
      <a href="apropos.php">À propos</a>
      <a href="connexion.php" class="actif">Connexion</a>
      <a href="inscription.php" class="btn-menu">Inscription</a>
    </div>
  </header>

  <section class="page-connexion">
    <div class="box-connexion">
      <h2>Connexion</h2>

      <?php
      if (isset($_SESSION['login_error'])) {
        echo '<p style="color:red; text-align:center;">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']);
      }
      ?>

      <form action="login_register.php" method="post">
        <label for="email">Courriel</label>
        <input type="email" name="email" placeholder="Courriel" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit" name="login">Se connecter</button>
      </form>

      <p class="signup-text">
        Pas de compte ? <a href="inscription.php">S'inscrire</a>
      </p>
    </div>
  </section>

</body>
</html>