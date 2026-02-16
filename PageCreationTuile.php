
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
  <title>Creation Tuile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>


  <div class="barre-navigation">
    <div class="logo">TravelBook</div>
    <div class="menu-navigation">
      <a href="next.php">next</a>
      <a href="MenuApresCo.php">Menu</a>
      <a href="PageCreationTuile.php" class="actif">Créer</a>
      <a href="logout.php">Déconnexion</a>
    </div>
  </div>


  <div class="page-connexion">
    <div class="tuile-creation">
      <h2>Créer un voyage</h2>

      <form action="create_trip.php" method="post" enctype="multipart/form-data">
        
        <label>Titre</label>
        <input type="text" name="title" required>

        <label>Pays</label>
        <input type="text" name="location" required>

        <label>Région</label>
        <select name="region">
            <option>Europe</option>
            <option>Asie</option>
            <option>Amérique</option>
            <option>Afrique</option>
        </select>

        <label>Date de voyage</label>
        <input type="date" name="travel_date">

        <label>Description</label>
        <textarea name="description" rows="5"></textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">

 
        <button type="submit" class="btn-principal">Créer</button>

      </form>
    </div>
  </div>

</body>
</html>