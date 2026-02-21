# IFT3225 - Projet LAMP : TravelBook

Bienvenue sur TravelBook, une plateforme web interactive permettant aux utilisateurs de documenter et partager leurs récits de voyage sous forme de tuiles dynamiques.

## Fonctionnalités clés
- Authentification sécurisée : Inscription et connexion avec hachage des mots de passe.
- Gestion des voyages : Création, modification et suppression de récits de voyage.
- Interactivité moderne : Système de "Likes" et recherche en temps réel via l'API Fetch (AJAX) sans rechargement de page.
- Design Adaptatif : Interface réalisée avec Flexbox et CSS Grid pour une navigation fluide.
- Pagination : Système de navigation par pages pour optimiser l'affichage des tuiles.

## Technologies utilisées
- Backend : PHP 8 (Requêtes préparées / Prepared Statements pour la sécurité SQL).
- Frontend : HTML5 sémantique, CSS3, JavaScript Natif.
- Base de données : MySQL.

## Installation et Déploiement Local
1. Dépôt des fichiers : Placez le dossier IFT3225-Projet_LAMP- dans votre répertoire htdocs (MAMP/XAMPP).
2. Base de données :
   - Créez une base de données nommée users_db dans phpMyAdmin.
   - Importez le fichier users_db.sql situé à la racine du projet.
3. Configuration : Le fichier config.php contient les identifiants de connexion par défaut (root / root).
4. Accès :
   - URL standard : http://localhost/IFT3225-Projet_LAMP-/index.php
   - URL MAMP (Mac) : http://localhost:8888/IFT3225-Projet_LAMP-/index.php

## Note sur le fichier config.php
Le fichier config.php est inclus dans ce dépôt uniquement pour faciliter la correction académique. Dans un environnement de production, ce fichier serait ignoré (via .gitignore) pour protéger les identifiants de la base de données.