<?php
require_once 'invites.php'; // Include the file containing functions
require_once 'vendor/autoload.php';


// Get guests from the database
$invites = charger_invites();

$list_id = "3fc74538c9"; // Replace with your Mailchimp list ID
$api_key = "9bfe1f44960fef208710d051111e7f52-us9"; // Replace with your Mailchimp API key
$data_center = substr($api_key, strpos($api_key, '-') + 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $delete_ids = $_POST['selection'] ?? [];
    $deleted_count = supprimer_invite($delete_ids);
    if ($deleted_count > 0) {
        $message = "$deleted_count invité(s) ont été supprimé(s) avec succès.";
        $invites = charger_invites(); // Rafraîchir la liste après suppression
    } else {
        $message = "Aucun invité n'a été supprimé.";
    }
}


function sendMailchimpEmail($email) {
    global $list_id, $api_key, $data_center;
    
    // Hash de l'adresse email pour l'URL
    $subscriber_hash = md5(strtolower($email));
    
    $url = "https://{$data_center}.api.mailchimp.com/3.0/lists/{$list_id}/members/{$subscriber_hash}";

    $data = [
        'email_address' => $email,
        'status_if_new' => 'subscribed'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: apikey ' . $api_key,
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_announcement'])) {
    $selected_ids = $_POST['selection'] ?? [];
    $sent_count = 0;
    $error_count = 0;

    foreach ($selected_ids as $id) {
        $invite = array_filter($invites, function($inv) use ($id) {
            return $inv['id'] == $id;
        });
        
        if (!empty($invite)) {
            $invite = reset($invite);
            $result = sendMailchimpEmail($invite['email']);
            
            if (isset($result['id'])) {
                $sent_count++;
            } else {
                $error_count++;
            }
        }
    }

    $message = "Annonce envoyée à {$sent_count} invité(s).";
    if ($error_count > 0) {
        $message .= " {$error_count} erreur(s) rencontrée(s).";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des invités</title>
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
</head>
<body>
    <h1>Liste des invités</h1>
    <a href="index.html" class="save-button">Retour à l'accueil</a>

    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    
    <form method="post" action="">
        <table>
            <thead>
                <tr>
                    <th>Sélectionner</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invites as $invite): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selection[]" value="<?php echo $invite['id']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($invite['nom']); ?></td>
                        <td><?php echo htmlspecialchars($invite['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($invite['email']); ?></td>
                        <td>
                            <a href="modifier_invite.php?id=<?php echo $invite['id']; ?>" class="edit-button">Modifier</a>
                       
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <input type="submit" name="delete" value="Supprimer les invités sélectionnés" class="save-button">
        <a href="ajouter_invite_form.php" class="save-button">Ajouter un invité</a>
        <input type="submit" name="send_announcement" value="Envoyer l'annonce" class="save-button">
    </form>
</body>
</html>