<?php
/**
 * Fichier d'en-tête commun à toutes les pages.
 * Gère la session utilisateur, la connexion à la base de données et l'affichage du menu de navigation.
 */
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

$photo = !empty($res['profile_pic']) 
    ? "uploads/".$res['profile_pic'] 
    : "https://www.w3schools.com/howto/img_avatar.png";
?>
