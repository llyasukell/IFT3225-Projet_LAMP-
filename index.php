<?php
/**
 * Page d'accueil
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
  <title>TravelBook - Accueil</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="barre-navigation">
    <div class="logo">TravelBook</div>
    <nav class="menu-navigation">
      <a class="actif" href="index.php">Accueil</a>
      <a href="apropos.php">À propos</a>
      <a href="connexion.php" class="btn-menu">Connexion</a>
      <a href="inscription.php" class="btn-menu">Inscription</a>
    </nav>
  </header>

  <section class="banniere-principale">
    <div class="overlay-banniere">
      <img class="image-principale" src="worldmap.png" alt="Carte du monde TravelBook">
      <h2>Le monde à votre portée</h2>
      
      <a href="inscription.php" class="btn-principal">Découvrir</a>
    </div>
  </section>

  <main class="contenu-principal">
    <section class="tuiles">
      <h3>À la une</h3>
      <hr>
      <div class="grille-tuiles" id="grille-tuiles">
        </div>
    </section>
  </main>

  <script src="tuiles.js"></script>
</body>
</html>