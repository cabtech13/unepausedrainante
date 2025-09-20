<?php
include "config.php";

// ⚡️ CHANGE ICI
$username = "admin";
$password = "admin123";
$role = "admin";

// Générer le hash sécurisé
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hash, $role]);

echo "✅ Utilisateur créé : $username / $password<br>";
echo "Hash enregistré : $hash";
