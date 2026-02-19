
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
    <div class="logo">TravelBook</div>
    <div class="menu-navigation">
      <a class="btn-menu" href="index.php">Accueil</a>
      <a href="apropos.php" class="actif">À propos</a>
      <a href="connexion.php" class="btn-menu" >Connexion</a>
      <a href="inscription.php" class="btn-menu" >Inscription</a>
    </div>
  </header>

    <main>
    <div style="padding: 20px; max-width: 800px; margin: auto;">
        <h1>À propos de TravelBook</h1>
        <p>Bienvenue sur TravelBook, votre plateforme dédiée au partage d'expériences de voyage. Notre mission est de connecter les voyageurs du monde entier en leur offrant un espace pour documenter leurs aventures et découvrir de nouvelles destinations.</p>

        <h3 style="margin-top: 20px;">Ce que vous pouvez faire sur le site :</h3>
        <ul>
            <li><strong>Créer vos carnets de voyage :</strong> Publiez vos propres aventures avec des photos et des descriptions.</li>
            <li><strong>Explorer le monde :</strong> Parcourez les publications des autres pour trouver l'inspiration.</li>
            <li><strong>Filtrer vos recherches :</strong> Trouvez des voyages par continent ou par mots-clés.</li>
            <li><strong>Interagir :</strong> Aimez les voyages qui vous font rêver.</li>
            <li><strong>Gérer vos souvenirs :</strong> Retrouvez et modifiez vos partages dans "Mes Voyages".</li>
            <li><strong>Profil :</strong> Personnalisez votre compte avec une photo de profil.</li>
        </ul>

        <p style="margin-top: 20px;"><em>Prêt pour l'aventure ? Inscrivez-vous dès maintenant !</em></p>
    </div>
</main>

   
  



</body>
</html>