<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (!empty($_POST['nom'])) {
    $nom = trim($_POST['nom']);
    $couleur = !empty($_POST['couleur']) ? $_POST['couleur'] : '#58624B';

    $stmt = $pdo->prepare("INSERT INTO services (nom, couleur) VALUES (?, ?)");
    $stmt->execute([$nom, $couleur]);

    $_SESSION['flash'] = "✅ Service ajouté avec succès.";
}
header("Location: dashboard.php");
exit;
