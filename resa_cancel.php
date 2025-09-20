<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "config.php";
require "send_mail.php"; // ton wrapper PHPMailer

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

if (empty($_GET['id'])) { 
    header("Location: dashboard.php"); 
    exit; 
}

$id = (int)$_GET['id'];

// Récup réservation
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id=?");
$stmt->execute([$id]);
$res = $stmt->fetch();

if (!$res) {
    $_SESSION['flash'] = "❌ Réservation introuvable.";
    header("Location: dashboard.php");
    exit;
}

// Supprimer la réservation
$stmt = $pdo->prepare("DELETE FROM reservations WHERE id=?");
$stmt->execute([$id]);

// Infos client
$clientMail = $res['email_client'];
$clientNom  = trim($res['prenom_client'] . " " . $res['nom_client']);
$date       = $res['date_resa'];
$debut      = $res['heure_debut'];
$fin        = $res['heure_fin'];

// Mail au client
$sujetClient = "❌ Annulation de votre réservation";
$messageClient = "Bonjour $clientNom,\n\n" .
    "Votre réservation prévue le $date de $debut à $fin a été annulée par notre équipe.\n\n" .
    "Merci de votre compréhension.\n\n" .
    "— Une Pause Drainante";

sendMail($clientMail, $sujetClient, $messageClient);

// Mail à l’admin
$sujetAdmin = "❌ Réservation supprimée";
$messageAdmin = "Un rendez-vous a été annulé et supprimé de la base :\n\n" .
    "👤 Client : $clientNom\n" .
    "📧 Email : $clientMail\n" .
    "📅 Date : $date\n" .
    "🕒 Heure : $debut - $fin\n";

sendMail(EMAIL_MASSEUSE, $sujetAdmin, $messageAdmin);

// Flash message + retour
$_SESSION['flash'] = "✅ Réservation supprimée, client et admin notifiés.";
header("Location: dashboard.php");
exit;
