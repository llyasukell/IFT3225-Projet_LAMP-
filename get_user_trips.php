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
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'recent';
$offset = ($page - 1) * $limit;

$whereSql = " WHERE t.user_id = ?";
$params = [$user_id];
$types = "i";

if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $whereSql .= " AND t.title LIKE ? ";
    $params[] = $searchTerm;
    $types .= "s";
}

// Compte
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM trips t" . $whereSql);
$countStmt->bind_param($types, ...$params);
$countStmt->execute();
$totalTrips = (int)$countStmt->get_result()->fetch_assoc()['total'];

// Tri
$orderSql = " ORDER BY t.created_at DESC";
if ($sort === 'old') $orderSql = " ORDER BY t.created_at ASC";
if ($sort === 'popular') $orderSql = " ORDER BY like_count DESC";

$sql = "SELECT t.*, (SELECT COUNT(*) FROM likes WHERE trip_id = t.id) as like_count FROM trips t" . $whereSql . $orderSql . " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$finalParams = $params;
$finalParams[] = $limit;
$finalParams[] = $offset;
$finalTypes = $types . "ii";

$stmt->bind_param($finalTypes, ...$finalParams);
$stmt->execute();
$result = $stmt->get_result();

$trips = [];
while ($row = $result->fetch_assoc()) {
    $trip_id = $row['id'];
    $pStmt = $conn->prepare("SELECT photo_path FROM trip_photos WHERE trip_id = ?");
    $pStmt->bind_param("i", $trip_id);
    $pStmt->execute();
    $pRes = $pStmt->get_result();
    $photos = [];
    while($pRow = $pRes->fetch_assoc()) { $photos[] = $pRow['photo_path']; }
    $row['extra_photos'] = $photos;
    $trips[] = $row;
}

echo json_encode(['trips' => $trips, 'total' => $totalTrips]);