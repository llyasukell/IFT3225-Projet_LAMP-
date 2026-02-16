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
  <title>Inscription</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="barre-navigation">
    <div class="logo">MonSite</div>
    <div class="menu-navigation">
      <a class="btn-menu" href="index.html">Accueil</a>
      <a href="apropos.php">À propos</a>
      <a href="connexion.php" class="btn-menu">Connexion</a>
      <a href="inscription.php" class="actif">Inscription</a>
    </div>
  </header>

  <section class="page-connexion">
    <div class="box-connexion">
      <h2>Rejoindre</h2>

      <?php
      if (isset($_SESSION['register_error'])) {
        echo '<p style="color:red; text-align:center;">' . $_SESSION['register_error'] . '</p>';
        unset($_SESSION['register_error']);
      }
      ?>

      <form action="login_register.php" method="post">
        <label for="name">Nom</label>
        <input type="text" name="name" placeholder="Nom" required>

        <label for="email">Courriel</label>
        <input type="email" name="email" placeholder="Courriel" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit" name="register">S'inscrire</button>
      </form>

      <p class="signup-text">
        Déjà un compte ? <a href="connexion.php">Se connecter</a>
      </p>
    </div>
  </section>

</body>
</html>