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
      <a href="apropos.html">À propos</a>
      <a href="connexion.php" class="btn-menu">Connexion</a>
      <a href="inscription.php" class="actif">Inscription</a>
    </div>
  </header>

  <section class="page-connexion">

    <div class="box-connexion">

      <h2>Join</h2>

      <form method="post">

        <label for="name">Nom</label>
        <input type="text" name="name" placeholder="Nom" required>

        <label for="email">Courriel</label>
        <input type="email" name="email"  placeholder="Courriel" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit" name="register">Sign up</button>

      </form>

      <p class="signup-text">
        Have an account? <a href="connexion.html">Login</a>
      </p>

    </div>

  </section>

</body>
</html>