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
  <title>Mes Voyages Partagés</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gris-clair">

  <header class="barre-navigation">
    <div class="logo">TravelBook</div>
    <div class="menu-navigation">
        <a href="next.php">Explore</a>
        <a href="MenuApresCo.php">Menu</a>
        <a href="PageCreationTuile.php">Créer</a>
        <a class="actif" href="MesVoyages.php">Mes Voyages</a>
        
        <a href="profil.php" style="display: inline-flex; align-items: center; gap: 8px;">
            <img src="<?php echo $photo; ?>" alt="Profil" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 1px solid white;">
            Mon Profil
        </a>

        <a href="logout.php">Déconnexion</a>
    </div>
  </header>

  <main class="contenu-mes-voyages">
    <h1 class="titre-page">MES VOYAGES PARTAGÉS</h1>

    <div class="toolbar-voyages">
      <div class="search-container">
        <span class="search-icon">🔍</span>
        <input type="text" id="search-mes-voyages" placeholder="Search">
      </div>
      
      <div class="sort-container">
        <span>TRIER PAR: <strong class="sort-highlight">DATE</strong></span>
      </div>

      <a href="PageCreationTuile.php" class="btn-nouveau-voyage">NOUVEAU VOYAGE</a>
    </div>

    <section class="grille-profil" id="grille-mes-voyages">
      
    </section>

    <div class="pagination" id="pagination-mes-voyages"></div>
    

  </main>

  <script src="tuiles.js"></script>
</body>
</html>