<?php

// Fonction pour se connecter à la base de données
function db_connect() {
    $servername = "localhost"; // Nom de l'hôte
    $username = "root"; // Nom d'utilisateur
    $password = ""; // Mot de passe
    $dbname = "invites_db"; // Nom de la base de données

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function ajouter_invite($nom, $prenom, $email) {
    $conn = db_connect();
    $stmt = $conn->prepare('INSERT INTO invites (nom, prenom, email) VALUES (?, ?, ?)');
    $stmt->bind_param("sss", $nom, $prenom, $email);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

function get_invite_by_id($id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM invites WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $invite = null;
    if ($result->num_rows > 0) {
        $invite = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();
    return $invite;
}

// Fonction pour charger les invités depuis la base de données
function charger_invites() {
    $conn = db_connect();
    $query = "SELECT * FROM invites";
    $result = $conn->query($query);
    
    $invites = [];
    while ($row = $result->fetch_assoc()) {
        $invites[] = $row;
    }
    
    $conn->close();
    return $invites;
}

// Fonction pour sauvegarder des invités
function sauvegarder_invites($invites) {
    $conn = db_connect();
    
    foreach ($invites as $invite) {
        $stmt = $conn->prepare("INSERT INTO invites (id, nom, prenom, email) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE nom=VALUES(nom), prenom=VALUES(prenom), email=VALUES(email)");
        $stmt->bind_param("isss", $invite['id'], $invite['nom'], $invite['prenom'], $invite['email']);
        $stmt->execute();
    }
    
    $stmt->close();
    $conn->close();
}

// Fonction pour modifier un invité
function modifier_invite($id, $nom, $prenom, $email) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE invites SET nom = ?, prenom = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nom, $prenom, $email, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Fonction pour supprimer des invités
function supprimer_invite($ids) {
    if (empty($ids)) {
        return 0; // Aucune suppression si aucune ID fournie
    }

    $conn = db_connect();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "DELETE FROM invites WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($ids);
    $deletedCount = $stmt->affected_rows; // Nombre d'invités supprimés
    $stmt->close();
    $conn->close();
    
    return $deletedCount;
}

function selectionner_invite($id) {
    session_start();
    if (!isset($_SESSION['invites_selectionnes'])) {
        $_SESSION['invites_selectionnes'] = [];
    }
    $invite = get_invite_by_id($id);
    if ($invite && !in_array($invite, $_SESSION['invites_selectionnes'])) {
        $_SESSION['invites_selectionnes'][] = $invite;
    }
}

function enregistrer_dans_bdd() {
    session_start();
    if (!isset($_SESSION['invites_selectionnes'])) {
        return;
    }
    
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO invites (id, nom, prenom, email) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE nom=VALUES(nom), prenom=VALUES(prenom), email=VALUES(email)");

    foreach ($_SESSION['invites_selectionnes'] as $invite) {
        $stmt->bind_param("isss", $invite['id'], $invite['nom'], $invite['prenom'], $invite['email']);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
    $_SESSION['invites_selectionnes'] = []; // Réinitialiser la sélection
}
