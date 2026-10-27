<?php
// Connexion à la base de données (à appeler dans un try...catch)
function connexionBdd()
{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque;charset=utf8mb4', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// Nettoie une donnée reçue d'un formulaire
// J'enlève les espaces et les balises (ce n'est pas une protection XSS)
// Je n'utilise pas htmlspecialchars ici pour garder les apostrophes en base
// Je protège du XSS à l'affichage et du SQL avec prepare()
function nettoyer($donnee)
{
    return strip_tags(trim($donnee));
}
