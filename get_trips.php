<?php
require_once "config.php";

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $limit;

// Compter le nombre total de voyages (avec filtre si search)
if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM trips t JOIN users u ON t.user_id = u.id WHERE t.title LIKE ? OR u.name LIKE ?");
    $countStmt->bind_param("ss", $searchTerm, $searchTerm);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
} else {
    $countResult = $conn->query("SELECT COUNT(*) as total FROM trips");
}
$totalRow = $countResult->fetch_assoc();
$totalTrips = (int)$totalRow['total'];

// Récupérer les voyages avec pagination et filtre
$sql = "SELECT t.*, u.name as author_name, 
        (SELECT COUNT(*) FROM likes WHERE trip_id = t.id) as like_count
        FROM trips t 
        JOIN users u ON t.user_id = u.id ";

if ($search !== '') {
    $sql .= " WHERE t.title LIKE ? OR u.name LIKE ? ";
    $stmt = $conn->prepare($sql . " ORDER BY t.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
} else {
    $stmt = $conn->prepare($sql . " ORDER BY t.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$trips = [];
while ($row = $result->fetch_assoc()) {
    $trips[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'trips' => $trips,
    'total' => $totalTrips,
    'page' => $page,
    'limit' => $limit
]);

$conn->close();
?>