<?php
$host = "db5018593330.hosting-data.io";
$dbname = "dbs14747385";
$user = "dbu791386";
$pass = "Olly20182024@";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

// Email masseuse
define("EMAIL_MASSEUSE", "masseuse@unepause.fr");
