<?php
$host = "localhost";
$user = "root";
$password = "root"; 
$database = "users_db";

$conn = new mysqli($host, $user, $password, $database);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
// Pas de balise de fermeture ici, c'est fait exprès !