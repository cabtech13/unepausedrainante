<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM disponibilites WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $_SESSION['flash'] = "🗑️ Disponibilité supprimée";
}

header("Location: dashboard.php");
exit;
