<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "config.php";

// Vérif connexion admin
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

if (!empty($_POST['creneau_id']) && !empty($_POST['nom']) && !empty($_POST['prenom'])) {
    $creneau_id = (int)$_POST['creneau_id'];
    $nom        = trim($_POST['nom']);
    $prenom     = trim($_POST['prenom']);
    $email      = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $telephone  = !empty($_POST['telephone']) ? trim($_POST['telephone']) : null;

    // Vérifier que le créneau existe
    $checkCreneau = $pdo->prepare("SELECT COUNT(*) FROM creneaux WHERE id=?");
    $checkCreneau->execute([$creneau_id]);
    if ($checkCreneau->fetchColumn() == 0) {
        $_SESSION['flash'] = "❌ Ce créneau n'existe pas.";
        header("Location: dashboard.php");
        exit;
    }

    // Vérifier si déjà réservé
    $checkResa = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE creneau_id=?");
    $checkResa->execute([$creneau_id]);
    if ($checkResa->fetchColumn() > 0) {
        $_SESSION['flash'] = "⚠️ Ce créneau est déjà réservé.";
        header("Location: dashboard.php");
        exit;
    }

    // Insertion
    $stmt = $pdo->prepare("
        INSERT INTO reservations (creneau_id, nom_client, prenom_client, email_client, telephone_client) 
        VALUES (?,?,?,?,?)
    ");
    $stmt->execute([$creneau_id, $nom, $prenom, $email, $telephone]);

    $_SESSION['flash'] = "✅ Réservation ajoutée pour $prenom $nom.";
} else {
    $_SESSION['flash'] = "❌ Formulaire incomplet.";
}

header("Location: dashboard.php");
exit;
