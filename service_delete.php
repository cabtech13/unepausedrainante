<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM services WHERE id=?");
    $stmt->execute([$id]);

    $_SESSION['flash'] = "🗑️ Service supprimé.";
}
header("Location: dashboard.php");
exit;
