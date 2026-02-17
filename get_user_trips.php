<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['trips' => [], 'total' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $limit;

// Compter le nombre total de voyages de l'utilisateur (avec filtre)
if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM trips WHERE user_id = ? AND title LIKE ?");
    $countStmt->bind_param("is", $user_id, $searchTerm);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
} else {
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM trips WHERE user_id = ?");
    $countStmt->bind_param("i", $user_id);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
}
$totalRow = $countResult->fetch_assoc();
$totalTrips = (int)$totalRow['total'];

// Récupérer les voyages avec filtre et pagination
$sql = "SELECT t.*, 
        (SELECT COUNT(*) FROM likes WHERE trip_id = t.id) as like_count 
        FROM trips t 
        WHERE t.user_id = ? ";

if ($search !== '') {
    $sql .= " AND t.title LIKE ? ";
    $stmt = $conn->prepare($sql . " ORDER BY t.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("isii", $user_id, $searchTerm, $limit, $offset);
} else {
    $stmt = $conn->prepare($sql . " ORDER BY t.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $user_id, $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$trips = [];
while($row = $result->fetch_assoc()) {
    $trips[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'trips' => $trips,
    'total' => $totalTrips,
    'page' => $page,
    'limit' => $limit
]);

$stmt->close();
$conn->close();
?>