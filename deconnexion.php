<?php
// Je démarre la session pour y avoir accès
session_start();
include('utils/utils.php');

// Traitements PHP
// Je détruis la session
session_destroy();

// Je redirige vers l'accueil
header("Location:index.php");
// J'arrête le script après la redirection
exit;
?>
