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
  <title>Accueil - Explore</title>
  <link rel="stylesheet" href="style.css">
  <script>
    const IS_CONNECTED = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
  </script>
</head>
<body>
  <header class="barre-navigation">
    <div class="logo">MonSite</div>
    <div class="menu-navigation">
      <a class="actif" href="next.php">Explore</a>
      <a href="MenuApresCo.php">Menu</a>
      <a href="PageCreationTuile.php">Créer</a>
      <a href="MesVoyages.php">Mes Voyages</a>
      <a href="logout.php">Déconnexion</a>
    </div>
  </header>

 <main class="contenu-principal">

  <div class="toolbar-voyages" style="justify-content: flex-start; margin-bottom: 20px;">
    <div class="search-container">
      <span class="search-icon">🔍</span>
      <input type="text" id="search-explore" placeholder="Rechercher par titre ou auteur...">
    </div>
  </div>


    <div class="grille-tuiles" id="grille-tuiles">
        </div>
 </main>

 <div class="pagination" id="pagination-explore"></div>

 <script src="tuiles.js"></script>
</body>
</html>