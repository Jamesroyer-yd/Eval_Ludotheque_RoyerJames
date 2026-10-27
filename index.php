<?php
session_start();
include('utils/utils.php');

// Traitements PHP
try {
    $pdo = connexionBdd();

    // Je récupère tous les jeux avec leur catégorie et le membre, triés par titre
    $request = $pdo->prepare("SELECT jeu.titre, jeu.joueurs_min, jeu.joueurs_max, jeu.duree, categorie.nom_categorie, membre.prenom
        FROM jeu
        INNER JOIN categorie ON jeu.id_categorie = categorie.id_categorie
        INNER JOIN membre ON jeu.id_membre = membre.id_membre
        ORDER BY jeu.titre ASC");
    $request->execute();
    $data = $request->fetchAll();
} catch (EXCEPTION $error) {
    die($error->getMessage());
}

include('includes/header.php');
?>

<main>
    <h2>Catalogue des jeux</h2>

    <?php if (empty($data)) { ?>
        <p>Aucun jeu dans le catalogue.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Joueurs</th>
                <th>Durée</th>
                <th>Ajouté par</th>
            </tr>
            <!-- J'affiche chaque jeu -->
            <?php foreach ($data as $jeu) { ?>
                <tr>
                    <td><?= htmlspecialchars($jeu['titre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($jeu['nom_categorie'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars(formatJoueurs($jeu['joueurs_min'], $jeu['joueurs_max']), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($jeu['duree'] . " min - " . categorieDuree($jeu['duree']), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($jeu['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</main>

<?php include('includes/footer.php'); ?>
