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
  <title>Mes Voyages Partagés</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gris-clair">

  <header class="barre-navigation">
    <div class="logo">MonSite</div>
    <div class="menu-navigation">
      <a href="next.php">Explore</a>
      <a href="MenuApresCo.php">Menu</a>
      <a href="PageCreationTuile.php">Créer</a>
      <a class="actif" href="MesVoyages.php">Mes Voyages</a>
      <a href="logout.php">Déconnexion</a>
    </div>
  </header>

  <main class="contenu-mes-voyages">
    <h1 class="titre-page">MES VOYAGES PARTAGÉS</h1>

    <div class="toolbar-voyages">
      <div class="search-container">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Search">
      </div>
      
      <div class="sort-container">
        <span>TRIER PAR: <strong class="sort-highlight">DATE</strong></span>
      </div>

      <a href="PageCreationTuile.php" class="btn-nouveau-voyage">NOUVEAU VOYAGE</a>
    </div>

    <section class="grille-profil">
      
      <div class="carte-voyage">
        <div class="carte-header">
          <span class="tag-continent europe">EUROPE</span>
          <span class="likes">3 LIKES</span>
        </div>
        <img src="uploads/paris.jpg" alt="Paris" class="img-voyage">
        <div class="carte-info">
          <p class="date">15 JANVIER 2026</p>
          <p class="titre">PARIS C'EST MAGIQUE</p>
        </div>
        <div class="carte-actions">
          <button class="btn-modifier">MODIFIER</button>
          <button class="btn-supprimer">SUPPRIMER</button>
        </div>
      </div>

      <div class="carte-voyage">
        <div class="carte-header">
          <span class="tag-continent afrique">AFRIQUE</span>
          <span class="likes">0 LIKES</span>
        </div>
        <img src="uploads/afrique.jpg" alt="Johannesburg" class="img-voyage">
        <div class="carte-info">
          <p class="date">28 JUIN 2025</p>
          <p class="titre">VISIT JOHANNESBURG</p>
        </div>
        <div class="carte-actions">
          <button class="btn-modifier">MODIFIER</button>
          <button class="btn-supprimer">SUPPRIMER</button>
        </div>
      </div>

      <div class="carte-voyage">
        <div class="carte-header">
          <span class="tag-continent amerique">AMÉRIQUE</span>
          <span class="likes">31 LIKES</span>
        </div>
        <img src="uploads/ny.jpg" alt="New York" class="img-voyage">
        <div class="carte-info">
          <p class="date">20 FÉVRIER 2026</p>
          <p class="titre">VISITE AVEC MOI NEW YORK</p>
        </div>
        <div class="carte-actions">
          <button class="btn-modifier">MODIFIER</button>
          <button class="btn-supprimer">SUPPRIMER</button>
        </div>
      </div>

      </section>

    <div class="pagination">
      <button>«</button>
      <button>‹</button>
      <button class="actif">1</button>
      <button>2</button>
      <button>3</button>
      <button>...</button>
      <button>10</button>
      <button>›</button>
      <button>»</button>
    </div>

  </main>

</body>
</html>
