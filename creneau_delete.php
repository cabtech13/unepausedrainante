<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Supprimer le créneau
    $stmt = $pdo->prepare("DELETE FROM creneaux WHERE id=?");
    $stmt->execute([$id]);

    // Supprimer les réservations liées
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE creneau_id=?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = "🗑️ Créneau (et ses réservations) supprimé.";
}

header("Location: dashboard.php");
exit;
