<?php
require_once "config.php";


$sql = "SELECT t.id, t.title, t.location, t.description, t.image_path, u.name as author_name 
        FROM trips t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC LIMIT 10";

$result = $conn->query($sql);
$trips = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trips[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($trips);
$conn->close();
?>