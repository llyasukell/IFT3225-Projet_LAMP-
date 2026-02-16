
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
  <title>Page Exemple</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="barre-navigation">
    <div class="logo">MonSite</div>
    <div class="menu-navigation">
      <a class="btn-menu" href="index.php">Accueil</a>
      <a href="apropos.php" class="actif">À propos</a>
      <a href="connexion.php" class="btn-menu" >Connexion</a>
      <a href="inscription.php" class="btn-menu" >Inscription</a>
    </div>
  </header>

    <main >
        
        <div><p>About</p></div>


    
    </main>

   
  



</body>
</html>