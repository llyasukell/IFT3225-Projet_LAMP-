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
      <a href="next.php">Explore</a>
      <a href="MenuApresCo.php">Menu</a>
      <a class="actif" href="PageCreationTuile.php">Créer</a>
      <a href="MesVoyages.php">Mes Voyages</a>
      <a href="logout.php">Déconnexion</a>
    </div>
  </div>

  <div class="page-connexion">
    <div class="tuile-creation">
      <h2>Créer un voyage</h2>

      <?php if(isset($_SESSION['trip_errors'])): ?>
          <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 15px; font-size: 0.9em;">
              <strong>Erreur :</strong>
              <ul style="margin: 5px 0 0 20px;">
                  <?php foreach($_SESSION['trip_errors'] as $error): ?>
                      <li><?php echo htmlspecialchars($error); ?></li>
                  <?php endforeach; ?>
              </ul>
          </div>
          <?php unset($_SESSION['trip_errors']); ?>
      <?php endif; ?>

      <form action="create_trip.php" method="post" enctype="multipart/form-data">
        
        <label>Titre</label>
        <input type="text" name="title" value="<?php echo isset($_SESSION['old_data']['title']) ? htmlspecialchars($_SESSION['old_data']['title']) : ''; ?>">

        <label>Pays</label>
        <input type="text" name="location" value="<?php echo isset($_SESSION['old_data']['location']) ? htmlspecialchars($_SESSION['old_data']['location']) : ''; ?>">

        <label>Région</label>
        <select name="region">
            <option <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Europe') ? 'selected' : ''; ?>>Europe</option>
            <option <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Asie') ? 'selected' : ''; ?>>Asie</option>
            <option <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Amérique') ? 'selected' : ''; ?>>Amérique</option>
            <option <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Afrique') ? 'selected' : ''; ?>>Afrique</option>
        </select>

        <label>Date de voyage</label>
        <input type="date" name="travel_date" value="<?php echo isset($_SESSION['old_data']['travel_date']) ? htmlspecialchars($_SESSION['old_data']['travel_date']) : ''; ?>">

        <label>Description</label>
        <textarea name="description" rows="5"><?php echo isset($_SESSION['old_data']['description']) ? htmlspecialchars($_SESSION['old_data']['description']) : ''; ?></textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn-principal">Créer</button>
      </form>
      <?php unset($_SESSION['old_data']); // On vide les anciennes données après remplissage ?>
    </div>
  </div>

</body>
</html>
