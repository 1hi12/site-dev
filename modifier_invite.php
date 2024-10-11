<?php
require_once 'invites.php'; // Inclure le fichier contenant les fonctions

// Vérifiez si l'identifiant de l'invité est passé dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli = db_connect(); // Assurez-vous que cette fonction utilise mysqli
    
    // Récupérer l'invité par ID
    $stmt = $mysqli->prepare("SELECT * FROM invites WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" pour integer
    $stmt->execute();
    $result = $stmt->get_result();
    $invite = $result->fetch_assoc(); // Utilisez fetch_assoc() sans arguments

    // Si l'invité n'existe pas, rediriger ou afficher un message d'erreur
    if (!$invite) {
        die("Invité non trouvé.");
    }
} else {
    die("ID de l'invité non spécifié.");
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    // Mettre à jour l'invité dans la base de données
    $stmt = $mysqli->prepare("UPDATE invites SET nom = ?, prenom = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nom, $prenom, $email, $id);
    if ($stmt->execute()) {
        header('Location: list.php'); // Redirigez vers la liste après modification
        exit();
    } else {
        echo "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFE6E6;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        h1 {
            color: #D81B60;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #FFF0F5;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #FF69B4;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #FF69B4;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #FFB6C1;
        }
        a {
            color: #C2185B;
            text-decoration: none;
            margin-right: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
        .save-button {
            display: inline-block;
            background-color: #D81B60;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .save-button:hover {
            background-color: #AD1457;
        }
        .message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un invité</title>
    <link rel="stylesheet" href="h1.css">
</head>
<body>
    <h1>Modifier un invité</h1>
    <form method="post" action="">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($invite['nom']); ?>" required><br><br>
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($invite['prenom']); ?>" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($invite['email']); ?>" required><br><br>
        <input type="submit" value="Modifier">
    </form>
    <br>
    <a href="list.php">Retour à la liste</a>
</body>
</html>
