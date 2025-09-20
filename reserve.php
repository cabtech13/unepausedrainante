<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";
require "send_mail.php"; // utilise PHPMailer
header('Content-Type: application/json');

try {
    // Vérifier si formulaire complet
    if (
        empty($_POST['slot_id']) || 
        empty($_POST['nom']) || 
        empty($_POST['prenom']) || 
        empty($_POST['email']) || 
        empty($_POST['telephone'])
    ) {
        echo json_encode(["success" => false, "message" => "❌ Champs manquants"]);
        exit;
    }

    $slot_id   = $_POST['slot_id'];
    $nom       = trim($_POST['nom']);
    $prenom    = trim($_POST['prenom']);
    $email     = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);

    // Découper le slot_id → "2025-09-12_09:00:00_11:00:00"
    if (!preg_match('/^(\d{4}-\d{2}-\d{2})_(\d{2}:\d{2}:\d{2})_(\d{2}:\d{2}:\d{2})$/', $slot_id, $m)) {
        echo json_encode(["success" => false, "message" => "❌ Format de créneau invalide"]);
        exit;
    }
    $date  = $m[1];
    $debut = $m[2];
    $fin   = $m[3];

    // Vérifier si déjà réservé
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE date_resa=? AND heure_debut=? AND heure_fin=?");
    $stmt->execute([$date, $debut, $fin]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["success" => false, "message" => "⚠️ Ce créneau est déjà réservé"]);
        exit;
    }

    // Enregistrer la réservation
    $stmt = $pdo->prepare("INSERT INTO reservations 
        (date_resa, heure_debut, heure_fin, nom_client, prenom_client, email_client, telephone_client, statut) 
        VALUES (?,?,?,?,?,?,?, 'confirmé')");
    $stmt->execute([$date, $debut, $fin, $nom, $prenom, $email, $telephone]);

    // Emails
    $sujetClient = "Confirmation réservation";
    $msgClient = "Bonjour $prenom $nom,\n\nVotre réservation est confirmée :\n📅 $date de $debut à $fin\n\nMerci de votre confiance.\n\n- Une Pause Drainante";

    $sujetMasseuse = "Nouvelle réservation";
    $msgMasseuse = "📅 $date $debut-$fin\n👤 Client : $prenom $nom\n📧 Email : $email\n📞 Téléphone : $telephone";

    // Envoi via PHPMailer (SMTP IONOS)
    sendMail($email, $sujetClient, $msgClient);          // Client
    sendMail(EMAIL_MASSEUSE, $sujetMasseuse, $msgMasseuse); // Masseuse/admin

    echo json_encode(["success" => true, "message" => "✅ Réservation confirmée"]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "❌ Erreur serveur : ".$e->getMessage()]);
}
