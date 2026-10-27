<?php
session_start();
include('utils/utils.php');

// Traitements PHP
$message = "";

// Je vérifie que le formulaire est envoyé
if (isset($_POST['inscription'])) {

    // Je vérifie que tous les champs sont remplis
    if (empty($_POST['prenom']) || empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['mdp']) || empty($_POST['mdp_confirm'])) {
        $message = "Erreur : tous les champs doivent être remplis.";
    }
    // Je vérifie le format de l'email
    else if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $message = "Erreur : le format de l'email n'est pas valide.";
    }
    // Je vérifie que le mot de passe fait 12 caractères minimum
    else if (strlen($_POST['mdp']) < 12) {
        $message = "Erreur : le mot de passe doit contenir au moins 12 caractères.";
    }
    // Je compare le mot de passe et sa confirmation
    else if ($_POST['mdp'] != $_POST['mdp_confirm']) {
        $message = "Erreur : le mot de passe et sa confirmation ne sont pas identiques.";
    } else {
        // Je nettoie les données
        $prenom = nettoyer($_POST['prenom']);
        $nom = nettoyer($_POST['nom']);
        $email = nettoyer($_POST['email']);

        try {
            $pdo = connexionBdd();

            // Je vérifie que l'email n'est pas déjà pris
            $request = $pdo->prepare("SELECT id_membre FROM membre WHERE email = ?");
            $request->execute([$email]);
            $data = $request->fetchAll();

            if (!empty($data)) {
                $message = "Erreur : cet email est déjà utilisé par un autre membre.";
            } else {
                // Je hache le mot de passe
                $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                // J'enregistre le membre
                $request = $pdo->prepare("INSERT INTO membre (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
                $request->execute([$prenom, $nom, $email, $mdp]);

                $message = "Votre inscription a bien été enregistrée, vous pouvez vous connecter.";
            }
        } catch (EXCEPTION $error) {
            die($error->getMessage());
        }
    }
}

include('includes/header.php');
?>

<main>
    <h2>Inscription</h2>

    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <form action="" method="post">
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom">

        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom">

        <label for="email">Email</label>
        <input type="email" name="email" id="email">

        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" id="mdp">

        <label for="mdp_confirm">Confirmation du mot de passe</label>
        <input type="password" name="mdp_confirm" id="mdp_confirm">

        <input type="submit" name="inscription" value="S'inscrire">
    </form>
</main>

<?php include('includes/footer.php'); ?>
