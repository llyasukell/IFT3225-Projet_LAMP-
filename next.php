
<?php
session_start();

// Si l'id de l'utilisateur n'est pas dans la session, on le renvoie à la connexion
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
  <title>Accueil - Bienvenue </title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="barre-navigation">
    <div class="logo">MonSite</div>
    <div class="menu-navigation">
      <a class="actif" href="next.php">Next</a>
      <a href="MenuApresCo.php">Menu</a>
      <a href="PageCreationTuile.php" >Créer</a>
      <a href="logout.php">Déconnexion</a>
    </div>
  </header>

 <h1>allo</h1>

 <a href="logout.php">Se déconnecter</a>
</body>
</html>