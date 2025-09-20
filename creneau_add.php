<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (!empty($_POST['type_creneau']) && !empty($_POST['debut']) && !empty($_POST['fin'])) {
    $type_creneau = $_POST['type_creneau'];
    $date = !empty($_POST['date']) ? $_POST['date'] : null;
    $jour_semaine = !empty($_POST['jour_semaine']) ? $_POST['jour_semaine'] : null;
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $service_id = (int)$_POST['service_id'];
    $prof_id = (int)$_POST['prof_id'];
    $type = $_POST['type']; // ouvert / sur_reservation

    $stmt = $pdo->prepare("
        INSERT INTO creneaux (type_creneau, date_creneau, jour_semaine, heure_debut, heure_fin, service_id, prof_id, type) 
        VALUES (?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$type_creneau, $date, $jour_semaine, $debut, $fin, $service_id, $prof_id, $type]);

    $_SESSION['flash'] = "✅ Créneau ajouté avec succès.";
}

header("Location: dashboard.php");
exit;
