<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jour = $_POST['jour'] ?? '';
    $debut = $_POST['debut'] ?? '';
    $fin = $_POST['fin'] ?? '';

    if ($jour && $debut && $fin) {
        $stmt = $pdo->prepare("INSERT INTO disponibilites (jour_semaine, heure_debut, heure_fin) VALUES (?, ?, ?)");
        $stmt->execute([$jour, $debut, $fin]);

        $_SESSION['flash'] = "✅ Disponibilité ajoutée pour $jour de $debut à $fin";
    } else {
        $_SESSION['flash'] = "⚠️ Champs manquants";
    }
}

header("Location: dashboard.php");
exit;
