<?php
session_start();

if (!isset($_SESSION['user_id'])) {
header("Location: connexion.php");
exit();
}
?>

<!DOCTYPE html>

<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu - TravelBook</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="barre-navigation">
<div class="logo">MonSite</div>
<div class="menu-navigation">
<a href="next.php">Explore</a>
<a class="actif" href="MenuApresCo.php">Menu</a>
<a href="PageCreationTuile.php">Créer</a>
<a href="MesVoyages.php">Mes Voyages</a>
<a href="logout.php">Déconnexion</a>
</div>
</header>

<section class="banniere-principale">
<div class="overlay-banniere">
<img class="image-principale" src="worldmap.png" alt="Carte du monde">
<h2>Le monde à votre portée</h2>
<button class="btn-principal">Découvrir</button>
</div>
</section>

<main class="contenu-principal">
<section class="tuiles">
<h3>À la une</h3>
<hr>
<div class="grille-tuiles" id="grille-tuiles"></div>
</section>
</main>

<script src="tuiles.js"></script>

</body>
</html>
