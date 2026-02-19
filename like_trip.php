<?php
session_start();
require_once "config.php";

header('Content-Type: application/json');

// Vérification de sécurité de base
if (!isset($_SESSION['user_id']) || !isset($_POST['trip_id'])) {
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$trip_id = (int)$_POST['trip_id'];

// 1. Vérifier si l'utilisateur a déjà liké
$check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND trip_id = ?");
$check->bind_param("ii", $user_id, $trip_id);
$check->execute();
$res = $check->get_result();
$already_liked = ($res->num_rows > 0);
$check->close();

// 2. Ajouter ou supprimer le like
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

// 3. Récupérer le nouveau total de likes pour ce voyage
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM likes WHERE trip_id = ?");
$count_stmt->bind_param("i", $trip_id);
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();

echo json_encode(['status' => $status, 'total' => (int)$total]);