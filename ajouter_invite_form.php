<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un invité</title>
    <style>
        /* Copiez le style de votre fichier CSS existant ici */
    </style>
</head>
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
<body>
    <h1>Ajouter un invité</h1>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>
    <form method="post" action="ajouter_invite.php">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required><br><br>
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Ajouter" class="save-button">
    </form>
    <br>
    <a href="list.php">Retour à la liste</a>
</body>
</html>