<?php
session_start();
require_once "config.php";

# vérifie  l'utilisateur 
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: mesvoyages.php");
    exit();
}

$trip_id = $_GET['id'];
$user_id = $_SESSION['user_id'];


$sql = "DELETE FROM trips WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $trip_id, $user_id);

if ($stmt->execute()) {
    header("Location: mesvoyages.php");
    exit();
} else {
    echo "Erreur lors de la suppression.";
}

$stmt->close();
$conn->close();
?>