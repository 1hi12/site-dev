<?php
require_once 'invites.php';  // Assurez-vous que ce fichier contient toutes vos fonctions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    if (ajouter_invite($nom, $prenom, $email)) {
        // Redirection vers la liste des invités après un ajout réussi
        header("Location: list.php?message=Invité ajouté avec succès");
        exit();
    } else {
        // En cas d'échec, redirigez vers la page d'ajout avec un message d'erreur
        header("Location: ajouter_invite_form.php?error=Erreur lors de l'ajout de l'invité");
        exit();
    }
}
?>