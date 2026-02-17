<?php
session_start();
require_once "config.php";

$user_id = $_SESSION['user_id']; // Récupère l'ID de la personne connectée

$sql = "SELECT * FROM trips WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$trips = [];
while ($row = $result->fetch_assoc()) {
    $trips[] = $row;
}

header('Content-Type: application/json');
echo json_encode($trips);
?>
