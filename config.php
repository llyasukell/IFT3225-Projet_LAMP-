<?php
/**
 * Fichier de configuration pour la connexion à la base de données.
 * Ce fichier est inclus dans tous les scripts qui nécessitent une connexion à la base de données.
 * Il établit la connexion et définit le charset pour éviter les problèmes d'encodage.
 */
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