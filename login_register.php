<?php
/**
 * Page de traitement de la connexion et de l'inscription.
 * Gère à la fois les requêtes de connexion et d'inscription en fonction des données POST reçues.
 */

session_start();
require_once "config.php";



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: connexion.php");
    exit();
}


#INSCRIPTION
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['register_error'] = "Cet email est déjà utilisé.";
        header("Location: inscription.php");
        exit();
    }

 
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        header("Location: next.php");
        exit();
    } else {
        $_SESSION['register_error'] = "Erreur technique lors de l'inscription.";
        header("Location: inscription.php");
        exit();
    }
}

#CONNEXION 
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            header("Location: next.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Mot de passe incorrect.";
            header("Location: connexion.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Aucun compte trouvé avec cet email.";
        header("Location: connexion.php");
        exit();
    }
}


header("Location: connexion.php");
exit();
?>