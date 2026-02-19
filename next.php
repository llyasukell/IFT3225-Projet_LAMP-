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
  <title>Accueil - Explore</title>
  <link rel="stylesheet" href="style.css">
  <script>
    const IS_CONNECTED = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
  </script>
</head>
<body>
  <header class="barre-navigation">
    <div class="logo">TravelBook</div>
    <div class="menu-navigation">
        <a class="actif" href="next.php">Explore</a>
        <a href="MenuApresCo.php">Menu</a>
        <a href="PageCreationTuile.php">Créer</a>
        <a href="MesVoyages.php">Mes Voyages</a>
        
        <a href="profil.php" style="display: inline-flex; align-items: center; gap: 8px;">
            <img src="<?php echo $photo; ?>" alt="Profil" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 1px solid white;">
            Mon Profil
        </a>

        <a href="logout.php">Déconnexion</a>
    </div>
  </header>

<main class="contenu-principal">
  <div class="toolbar-voyages" style="justify-content: flex-start; margin-bottom: 20px; gap: 15px; display: flex; flex-wrap: wrap;">
    
    <div class="search-container">
      <span class="search-icon">🔍</span>
      <input type="text" id="search-explore" placeholder="Rechercher par titre ou auteur...">
    </div>

    <div class="filter-container">
      <select id="filter-region" class="select-style">
        <option value="">Tous les continents</option>
        <option value="Europe">Europe</option>
        <option value="Asie">Asie</option>
        <option value="Amérique">Amérique</option>
        <option value="Afrique">Afrique</option>
      </select>
    </div>

    <div class="filter-container">
      <select id="sort-explore" class="select-style">
        <option value="recent">Plus récents</option>
        <option value="old">Plus anciens</option>
        <option value="popular">Plus aimés (Likes)</option>
      </select>
    </div>

  </div>

  <div class="grille-tuiles" id="grille-tuiles"></div>
</main>

 <div class="pagination" id="pagination-explore"></div>

 <script src="tuiles.js"></script>
</body>
</html>