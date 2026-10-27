<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Dé Fêlé - Ludothèque</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body>
    <header>
        <h1>Le Dé Fêlé</h1>
        <nav>
            <ul>
                <?php if (isset($_SESSION['id'])) { ?>
                    <!-- J'affiche le menu membre -->
                    <li>Bonjour <?= htmlspecialchars($_SESSION['prenom'], ENT_QUOTES, 'UTF-8') ?></li>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="ajout-jeu.php">Ajouter un jeu</a></li>
                    <li><a href="deconnexion.php">Se déconnecter</a></li>
                <?php } else { ?>
                    <!-- J'affiche le menu visiteur -->
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="inscription.php">Inscription</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
