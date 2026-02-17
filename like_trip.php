<?php
session_start();
require_once "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['trip_id'])) {
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

$user_id = $_SESSION['user_id'];
$trip_id = (int)$_POST['trip_id'];

//  Vérifier si déjà liké
$check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND trip_id = ?");
$check->bind_param("ii", $user_id, $trip_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $sql = "DELETE FROM likes WHERE user_id = ? AND trip_id = ?";
    $status = 'unliked';
} else {
    $sql = "INSERT INTO likes (user_id, trip_id) VALUES (?, ?)";
    $status = 'liked';
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $trip_id);
$stmt->execute();


$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM likes WHERE trip_id = ?");
$count_stmt->bind_param("i", $trip_id);
$count_stmt->execute();
$count_res = $count_stmt->get_result()->fetch_assoc();

echo json_encode([
    'status' => $status,
    'total' => (int)$count_res['total']
]);
?>