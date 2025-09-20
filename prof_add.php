<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (!empty($_POST['nom'])) {
    $nom = trim($_POST['nom']);
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $telephone = !empty($_POST['telephone']) ? trim($_POST['telephone']) : null;

    $stmt = $pdo->prepare("INSERT INTO profs (nom, email, telephone) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $telephone]);

    $_SESSION['flash'] = "✅ Prof ajouté avec succès.";
}
header("Location: dashboard.php");
exit;
