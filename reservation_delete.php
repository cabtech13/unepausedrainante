<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Supprimer la réservation
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id=?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = "🗑️ Réservation supprimée.";
}

header("Location: dashboard.php");
exit;
