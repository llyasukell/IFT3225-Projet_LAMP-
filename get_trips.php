<?php
require_once "config.php";

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$region = isset($_GET['region']) ? trim($_GET['region']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'recent';
$offset = ($page - 1) * $limit;

$whereClauses = [];
$params = [];
$types = "";

if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $whereClauses[] = "(t.title LIKE ? OR u.name LIKE ? OR t.region LIKE ? OR t.location LIKE ?)";
    array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $types .= "ssss";
}

if ($region !== '') {
    $whereClauses[] = "t.region = ?";
    $params[] = $region;
    $types .= "s";
}

$whereSql = "";
if (!empty($whereClauses)) {
    $whereSql = " WHERE " . implode(" AND ", $whereClauses);
}

// Compte Total
$countSql = "SELECT COUNT(*) as total FROM trips t JOIN users u ON t.user_id = u.id" . $whereSql;
$countStmt = $conn->prepare($countSql);
if (!empty($params)) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$totalRow = $countStmt->get_result()->fetch_assoc();
$totalTrips = (int)$totalRow['total'];

// Tri
$orderSql = " ORDER BY t.created_at DESC";
if ($sort === 'old') $orderSql = " ORDER BY t.created_at ASC";
if ($sort === 'popular') $orderSql = " ORDER BY like_count DESC";

// Requête principale
$sql = "SELECT t.*, u.name as author_name, 
        (SELECT COUNT(*) FROM likes WHERE trip_id = t.id) as like_count
        FROM trips t 
        JOIN users u ON t.user_id = u.id" . $whereSql . $orderSql . " LIMIT ? OFFSET ?";

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

header('Content-Type: application/json');
echo json_encode(['trips' => $trips, 'total' => $totalTrips]);
$conn->close();
?>