<?php
/**
 * Script de gestion des likes pour les voyages.
 * Ce script reçoit une requête AJAX avec l'ID du voyage à liker ou unliker, 
 * vérifie si l'utilisateur a déjà liké ce voyage, puis ajoute ou supprime le like en conséquence.
 * Il retourne le nouveau total de likes pour ce voyage.
 */
session_start();
require_once "config.php";

header('Content-Type: application/json');


if (!isset($_SESSION['user_id']) || !isset($_POST['trip_id'])) {
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$trip_id = (int)$_POST['trip_id'];

# Vérifier si l'utilisateur a déjà liké ce voyage
$check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND trip_id = ?");
$check->bind_param("ii", $user_id, $trip_id);
$check->execute();
$res = $check->get_result();
$already_liked = ($res->num_rows > 0);
$check->close();

# Ajouter ou supprimer le like selon le cas
if ($already_liked) {
    $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND trip_id = ?");
    $status = 'unliked';
} else {
    $stmt = $conn->prepare("INSERT INTO likes (user_id, trip_id) VALUES (?, ?)");
    $status = 'liked';
}
$stmt->bind_param("ii", $user_id, $trip_id);
$stmt->execute();
$stmt->close();

# Récupérer le nouveau total de likes pour ce voyage
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM likes WHERE trip_id = ?");
$count_stmt->bind_param("i", $trip_id);
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();

echo json_encode(['status' => $status, 'total' => (int)$total]);