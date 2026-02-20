<?php
/**
 * Page de création de tuile
 * Permet aux utilisateurs de créer une nouvelle tuile de voyage en remplissant un formulaire avec les détails du voyage, y compris le titre, la localisation, la région, la date, la description et les images. Les données sont ensuite traitées et stockées dans la base de données.
 */
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
  <title>Créer un voyage - TravelBook</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <header class="barre-navigation">
    <div class="logo">TravelBook</div>
    <nav class="menu-navigation">
        <a href="next.php">Explore</a>
        <a href="MenuApresCo.php">Menu</a>
        <a class="actif" href="PageCreationTuile.php">Créer</a>
        <a href="MesVoyages.php">Mes Voyages</a>
        <a href="profil.php" style="display: inline-flex; align-items: center; gap: 8px;">
            <img src="<?php echo $photo; ?>" alt="Profil" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 1px solid white;">
            Mon Profil
        </a>
        <a href="logout.php">Déconnexion</a>
    </nav>
  </header>

  <section class="page-connexion">
    <div class="tuile-creation">
      <h1>Créer un voyage</h1>

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
        
        <label for="title">Titre</label>
        <input type="text" id="title" name="title" value="<?php echo isset($_SESSION['old_data']['title']) ? htmlspecialchars($_SESSION['old_data']['title']) : ''; ?>" required>

        <label for="location">Pays</label>
        <input type="text" id="location" name="location" value="<?php echo isset($_SESSION['old_data']['location']) ? htmlspecialchars($_SESSION['old_data']['location']) : ''; ?>" required>

        <label for="region">Région</label>
        <select id="region" name="region" required>
            <option value="" disabled <?php echo !isset($_SESSION['old_data']['region']) ? 'selected' : ''; ?>>Choisir un continent</option>
            <option value="Europe" <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Europe') ? 'selected' : ''; ?>>Europe</option>
            <option value="Asie" <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Asie') ? 'selected' : ''; ?>>Asie</option>
            <option value="Amérique" <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Amérique') ? 'selected' : ''; ?>>Amérique</option>
            <option value="Afrique" <?php echo (isset($_SESSION['old_data']['region']) && $_SESSION['old_data']['region'] == 'Afrique') ? 'selected' : ''; ?>>Afrique</option>
        </select>

        <label for="travel_date">Date de voyage</label>
        <input type="date" id="travel_date" name="travel_date" required value="<?php echo isset($_SESSION['old_data']['travel_date']) ? htmlspecialchars($_SESSION['old_data']['travel_date']) : ''; ?>" >

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"><?php echo isset($_SESSION['old_data']['description']) ? htmlspecialchars($_SESSION['old_data']['description']) : ''; ?></textarea>

        <label for="image">Image principale</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <label for="trip_photos">Photos de voyage </label>
        <input type="file" id="trip_photos" name="trip_photos[]" accept="image/*" multiple>

        <button type="submit" class="btn-principal">Créer</button>
      </form>
      <?php unset($_SESSION['old_data']); ?>
    </div>
  </section>

</body>
</html>