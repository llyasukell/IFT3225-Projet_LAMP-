<?php
session_start();
require_once "config.php";

# Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
# Traitement du formulaire de création de tuile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id     = $_SESSION['user_id'];
    $title       = trim($_POST['title']);
    $location    = trim($_POST['location']);
    $region      = $_POST['region'];
    $travel_date = $_POST['travel_date'];
    $description = trim($_POST['description']);

    
    $image_path = ""; 
# Gestion du téléchargement de l'image    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dossier_cible = "uploads/";
        
        
        if (!is_dir($dossier_cible)) {
            mkdir($dossier_cible, 0777, true);
        }

        
        $nom_fichier = time() . "_" . basename($_FILES["image"]["name"]);
        $chemin_complet = $dossier_cible . $nom_fichier;

        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $chemin_complet)) {
            $image_path = $nom_fichier; 
        } else {
            die("Erreur lors du téléchargement de l'image.");
        }
    }

 # Insertion des données dans la base de données
    $stmt = $conn->prepare("INSERT INTO trips (user_id, title, location, region, travel_date, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    
    $stmt->bind_param("issssss", $user_id, $title, $location, $region, $travel_date, $description, $image_path);

    if ($stmt->execute()) {
        
        header("Location: index.html"); 
        exit();
    } else {
        echo "Erreur lors de la création de la tuile : " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>