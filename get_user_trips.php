<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travelbook"; // Remplace par le nom de ta base

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// On vérifie que l'utilisateur est bien connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// On récupère uniquement les voyages de cet utilisateur
// Assure-toi que ta table s'appelle 'trips' et possède une colonne 'user_id'
$sql = "SELECT * FROM trips WHERE user_id = ? ORDER BY travel_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$trips = [];
while($row = $result->fetch_assoc()) {
    $trips[] = $row;
}

// On renvoie le résultat au format JSON pour le JavaScript
header('Content-Type: application/json');
echo json_encode($trips);

$stmt->close();
$conn->close();
?>
