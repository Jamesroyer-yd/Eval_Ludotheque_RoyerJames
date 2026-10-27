<?php
session_start();
include('utils/utils.php');

// Traitements PHP
// Je redirige vers la connexion si personne n'est connecté
if (!isset($_SESSION['id'])) {
    header("Location:connexion.php");
    exit;
}

$message = "";
$classe = "erreur";

try {
    $pdo = connexionBdd();

    // Je vérifie que le formulaire est envoyé
    if (isset($_POST['ajout'])) {

        // Je vérifie que tous les champs sont remplis (sans empty pour accepter "0" et afficher le bon message ensuite)
        if (!isset($_POST['titre'], $_POST['min'], $_POST['max'], $_POST['duree'], $_POST['categorie'])
            || trim($_POST['titre']) == "" || trim($_POST['min']) == "" || trim($_POST['max']) == ""
            || trim($_POST['duree']) == "" || trim($_POST['categorie']) == "") {
            $message = "Erreur : tous les champs doivent être remplis.";
        }
        // Je vérifie que les nombres sont des entiers
        else if (filter_var(trim($_POST['min']), FILTER_VALIDATE_INT) === false
            || filter_var(trim($_POST['max']), FILTER_VALIDATE_INT) === false
            || filter_var(trim($_POST['duree']), FILTER_VALIDATE_INT) === false
            || filter_var(trim($_POST['categorie']), FILTER_VALIDATE_INT) === false) {
            $message = "Erreur : les nombres de joueurs, la durée et la catégorie doivent être des nombres entiers.";
        }
        // Je vérifie le minimum de joueurs
        else if ((int) $_POST['min'] < 1) {
            $message = "Erreur : le nombre minimum de joueurs doit être au moins 1.";
        }
        // Je vérifie que le maximum est supérieur ou égal au minimum
        else if ((int) $_POST['max'] < (int) $_POST['min']) {
            $message = "Erreur : le nombre maximum de joueurs doit être supérieur ou égal au minimum.";
        }
        // Je vérifie la durée
        else if ((int) $_POST['duree'] <= 0) {
            $message = "Erreur : la durée doit être supérieure à 0.";
        } else {
            // Je nettoie les données
            $titre = nettoyer($_POST['titre']);
            $min = (int) $_POST['min'];
            $max = (int) $_POST['max'];
            $duree = (int) $_POST['duree'];
            $categorie = (int) $_POST['categorie'];

            // Je vérifie qu'aucun jeu n'a déjà ce titre
            $request = $pdo->prepare("SELECT id_jeu FROM jeu WHERE titre = ?");
            $request->execute([$titre]);
            $data = $request->fetchAll();

            if (!empty($data)) {
                $message = "Erreur : un jeu portant ce titre existe déjà dans le catalogue.";
            } else {
                // J'enregistre le jeu avec l'id du membre connecté
                $request = $pdo->prepare("INSERT INTO jeu (titre, joueurs_min, joueurs_max, duree, id_categorie, id_membre) VALUES (?, ?, ?, ?, ?, ?)");
                $request->execute([$titre, $min, $max, $duree, $categorie, $_SESSION['id']]);

                $message = "Le jeu a bien été ajouté au catalogue.";
                $classe = "succes";
            }
        }
    }

    // Je récupère les catégories pour la liste déroulante
    $request = $pdo->prepare("SELECT id_categorie, nom_categorie FROM categorie ORDER BY nom_categorie ASC");
    $request->execute();
    $categories = $request->fetchAll();
} catch (EXCEPTION $error) {
    die($error->getMessage());
}

include('includes/header.php');
?>

<main>
    <h2>Ajouter un jeu</h2>

    <p class="<?= $classe ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <form action="" method="post">
        <label for="titre">Titre</label>
        <input type="text" name="titre" id="titre">

        <label for="min">Nombre minimum de joueurs</label>
        <input type="number" name="min" id="min">

        <label for="max">Nombre maximum de joueurs</label>
        <input type="number" name="max" id="max">

        <label for="duree">Durée d'une partie (en minutes)</label>
        <input type="number" name="duree" id="duree">

        <label for="categorie">Catégorie</label>
        <select name="categorie" id="categorie">
            <option value="">-- Choisir une catégorie --</option>
            <!-- J'affiche chaque catégorie de la base -->
            <?php foreach ($categories as $categorie) { ?>
                <option value="<?= htmlspecialchars($categorie['id_categorie'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($categorie['nom_categorie'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php } ?>
        </select>

        <input type="submit" name="ajout" value="Ajouter le jeu">
    </form>
</main>

<?php include('includes/footer.php'); ?>
