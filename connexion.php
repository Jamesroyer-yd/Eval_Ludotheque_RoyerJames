<?php
session_start();
include('utils/utils.php');

// Traitements PHP
$message = "";

// Je vérifie que le formulaire est envoyé
if (isset($_POST['connexion'])) {

    // Je vérifie que les deux champs sont remplis
    if (empty($_POST['email']) || empty($_POST['mdp'])) {
        $message = "Erreur : tous les champs doivent être remplis.";
    } else {
        $email = nettoyer($_POST['email']);

        try {
            $pdo = connexionBdd();

            // Je récupère le membre par son email
            $request = $pdo->prepare("SELECT id_membre, prenom, nom, email, mot_de_passe FROM membre WHERE email = ?");
            $request->execute([$email]);
            $data = $request->fetchAll();

            // Je vérifie que le membre existe et que le mot de passe est bon
            if (!empty($data) && password_verify($_POST['mdp'], $data[0]['mot_de_passe'])) {
                $_SESSION['id'] = $data[0]['id_membre'];
                $_SESSION['prenom'] = $data[0]['prenom'];
                $_SESSION['nom'] = $data[0]['nom'];
                $_SESSION['email'] = $data[0]['email'];

                header("Location:index.php");
                exit;
            } else {
                $message = "Email et/ou mot de passe incorrect";
            }
        } catch (EXCEPTION $error) {
            die($error->getMessage());
        }
    }
}

include('includes/header.php');
?>

<main>
    <h2>Connexion</h2>

    <p class="erreur"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <form action="" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">

        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" id="mdp">

        <input type="submit" name="connexion" value="Se connecter">
    </form>
</main>

<?php include('includes/footer.php'); ?>
