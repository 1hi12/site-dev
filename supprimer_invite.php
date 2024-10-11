<?php
require_once 'invites.php'; // Inclure le fichier contenant les fonctions

// Récupérer l'identifiant de l'invité à supprimer
$id = $_GET['id'] ?? null;

if ($id) {
    // Assurez-vous que l'identifiant est un entier
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        // Supposons que vous ayez une fonction `supprimer_invite` dans `invites.php`
        $deleted_count = supprimer_invite([$id]); // Passer l'ID sous forme de tableau
        if ($deleted_count > 0) {
            // Rediriger vers la liste des invités après la suppression
            header("Location: index.php?message=suppression_reussie");
            exit;
        } else {
            echo "Erreur lors de la suppression de l'invité ou invité non trouvé.";
        }
    } else {
        echo "Identifiant invalide.";
    }
} else {
    echo "Aucun invité à supprimer.";
}
?>
