<?php
/**
 * Page de profil de l'utilisateur.
 * Permet de visualiser ses informations et de mettre à jour sa photo de profil.
 */
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

# Récupérer les informations de l'utilisateur pour afficher son nom et sa photo actuelle
$stmt = $conn->prepare("SELECT name, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

# Gérer la mise à jour de la photo de profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_pic'])) {
    $dossier = "uploads/";
    if(!is_dir($dossier)) mkdir($dossier, 0777, true);

    $nom_fichier = time() . "_" . basename($_FILES["new_pic"]["name"]);
    $chemin_complet = $dossier . $nom_fichier;

    if (move_uploaded_file($_FILES["new_pic"]["tmp_name"], $chemin_complet)) {
        $update = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $update->bind_param("si", $nom_fichier, $user_id);
        $update->execute();
        
        $message = "Photo mise à jour avec succès !";
        $user['profile_pic'] = $nom_fichier; 
    }
}

# Déterminer la photo à afficher (celle de l'utilisateur ou une image par défaut)
$photo_a_afficher = !empty($user['profile_pic']) ? "uploads/" . $user['profile_pic'] : "https://www.w3schools.com/howto/img_avatar.png";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - TravelBook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="barre-navigation">
        <div class="logo">TravelBook</div>
        <nav class="menu-navigation">
            <a href="next.php">Explore</a>
            <a href="MenuApresCo.php">Menu</a>
            <a href="PageCreationTuile.php">Créer</a>
            <a href="MesVoyages.php">Mes Voyages</a>
            
            <a href="profil.php" class="actif" style="display: inline-flex; align-items: center; gap: 8px;">
                <img src="<?php echo $photo_a_afficher; ?>" alt="Miniature Profil" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 1px solid white;">
                Mon Profil
            </a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>

    <main class="page-connexion">
        <div class="tuile-creation" style="text-align: center;">
            <h1>Profil de <?php echo htmlspecialchars($user['name']); ?></h1>
            
            <img src="<?php echo $photo_a_afficher; ?>" alt="Photo de profil de <?php echo htmlspecialchars($user['name']); ?>" style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:20px; border: 2px solid #007bff;">

            <?php if($message) echo "<p style='color:green; margin-bottom:15px;'>$message</p>"; ?>

            <form action="profil.php" method="post" enctype="multipart/form-data">
                <label for="new_pic" style="display:block; margin-bottom:10px; cursor:pointer;">Choisir une nouvelle photo :</label>
                <input type="file" id="new_pic" name="new_pic" accept="image/*" required>
                
                <button type="submit" class="btn-principal" style="margin-top:20px;">Changer ma photo</button>
            </form>
        </div>
    </main>
</body>
</html>