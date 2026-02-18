<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 1. Récupérer les infos de l'utilisateur
$stmt = $conn->prepare("SELECT name, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 2. Traitement du téléchargement de la photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_pic'])) {
    $dossier = "uploads/";
    if(!is_dir($dossier)) mkdir($dossier, 0777, true);

    $nom_fichier = time() . "_" . basename($_FILES["new_pic"]["name"]);
    $chemin_complet = $dossier . $nom_fichier;

    if (move_uploaded_file($_FILES["new_pic"]["tmp_name"], $chemin_complet)) {
        // Mise à jour du nom de l'image dans la base de données
        $update = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $update->bind_param("si", $nom_fichier, $user_id);
        $update->execute();
        
        $message = "Photo mise à jour avec succès !";
        $user['profile_pic'] = $nom_fichier; // Pour afficher la nouvelle photo de suite
    }
}

// Déterminer quelle image afficher (la sienne ou une par défaut)
$photo_a_afficher = !empty($user['profile_pic']) ? "uploads/" . $user['profile_pic'] : "https://www.w3schools.com/howto/img_avatar.png";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="barre-navigation">
        <div class="logo">TravelBook</div>
        <div class="menu-navigation">
            <a href="MenuApresCo.php">Menu</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>

    <div class="page-connexion">
        <div class="tuile-creation" style="text-align: center;">
            <h2>Profil de <?php echo htmlspecialchars($user['username']); ?></h2>
            
            <img src="<?php echo $photo_a_afficher; ?>" style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:20px; border: 2px solid #007bff;">

            <?php if($message) echo "<p style='color:green;'>$message</p>"; ?>

            <form action="profil.php" method="post" enctype="multipart/form-data">
                <input type="file" name="new_pic" accept="image/*" required>
                <button type="submit" class="btn-principal" style="margin-top:10px;">Changer ma photo</button>
            </form>
        </div>
    </div>
</body>
</html>
