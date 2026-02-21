<?php
/**
 * Page de déconnexion de l'utilisateur.
 */
session_start();


$_SESSION = array();

session_destroy();


header("Location: index.php");
exit();