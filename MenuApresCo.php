<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

require_once "header.php";

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
    <div class="logo">TravelBook</div>
    <div class="menu-navigation">
        <a href="next.php">Explore</a>
        <a class="actif" href="MenuApresCo.php">Menu</a>
        <a href="PageCreationTuile.php">Créer</a>
        <a href="MesVoyages.php">Mes Voyages</a>
        
        <a href="profil.php" style="display: inline-flex; align-items: center; gap: 8px;">
            <img src="<?php echo $photo; ?>" alt="Profil" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 1px solid white;">
            Mon Profil
        </a>

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
        <div class="grille-tuiles" id="grille-tuiles"></div>
    </section>
</main>

<script src="tuiles.js"></script>

</body>
</html>
