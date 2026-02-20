<?php
/**
 * Page de connexion 
 * Permet aux utilisateurs de se connecter à leur compte.
 */
session_start();

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
  <title>Connexion - TravelBook</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="barre-navigation">
    <div class="logo">TravelBook</div>
    <nav class="menu-navigation">
      <a class="btn-menu" href="index.php">Accueil</a>
      <a href="apropos.php">À propos</a>
      <a href="connexion.php" class="actif">Connexion</a>
      <a href="inscription.php" class="btn-menu">Inscription</a>
    </nav>
  </header>

  <section class="page-connexion">
    <div class="box-connexion">
      <h1>Connexion</h1>

      <?php
      if (isset($_SESSION['login_error'])) {
        echo '<p style="color:red; text-align:center;">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
        unset($_SESSION['login_error']);
      }
      ?>

      <form action="login_register.php" method="post">
        <label for="email">Courriel</label>
        <input type="email" id="email" name="email" placeholder="Courriel" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="password" placeholder="Mot de passe" required>

        <button type="submit" name="login">Se connecter</button>
      </form>

      <p class="signup-text">
        Pas de compte ? <a href="inscription.php">S'inscrire</a>
      </p>
    </div>
  </section>

</body>
</html>