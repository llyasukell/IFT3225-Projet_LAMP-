<?php
/**
 * Script de suppression d'un voyage.
 * Ce script est appelé lorsque l'utilisateur clique sur "Supprimer" pour un voyage dans "mesvoyages.php". 
 * Il vérifie que l'utilisateur est connecté et que le voyage appartient à cet utilisateur, puis supprime le voyage et les photos associées de la base de données et du serveur.
 */
session_start();
require_once "config.php";

# Vérifie l'utilisateur 
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: mesvoyages.php");
    exit();
}

$trip_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

# Récupérer le chemin de l'image principale pour la supprimer physiquement
$sql_img = "SELECT image_path FROM trips WHERE id = ? AND user_id = ?";
$stmt_img = $conn->prepare($sql_img);
$stmt_img->bind_param("ii", $trip_id, $user_id);
$stmt_img->execute();
$res_img = $stmt_img->get_result();
$trip = $res_img->fetch_assoc();

# Récupérer les chemins des photos secondaires pour les supprimer physiquement
$sql_photos = "SELECT photo_path FROM trip_photos WHERE trip_id = ?";
$stmt_photos = $conn->prepare($sql_photos);
$stmt_photos->bind_param("i", $trip_id);
$stmt_photos->execute();
$res_photos = $stmt_photos->get_result();

# Supprimer le voyage de la base de données
$sql_del = "DELETE FROM trips WHERE id = ? AND user_id = ?";
$stmt_del = $conn->prepare($sql_del);
$stmt_del->bind_param("ii", $trip_id, $user_id);

if ($stmt_del->execute()) {
    if ($trip && !empty($trip['image_path'])) {
        $file_main = "uploads/" . $trip['image_path'];
        if (file_exists($file_main)) {
            unlink($file_main);
        }
    }

    # Supprimer les photos secondaires
    while ($p = $res_photos->fetch_assoc()) {
        $file_sec = "uploads/" . $p['photo_path'];
        if (file_exists($file_sec)) {
            unlink($file_sec);
        }
    }

    header("Location: mesvoyages.php");
    exit();
} else {
    echo "Erreur lors de la suppression.";
}

$conn->close();
?>