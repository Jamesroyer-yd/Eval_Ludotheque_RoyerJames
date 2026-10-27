<?php
// Connexion à la base de données (à appeler dans un try...catch)
function connexionBdd()
{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque;charset=utf8mb4', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// Nettoie une donnée reçue d'un formulaire
function nettoyer($donnee)
{
    return htmlspecialchars(strip_tags(trim($donnee)), ENT_QUOTES, 'UTF-8');
}
