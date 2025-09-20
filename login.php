<?php
session_start();
include "config.php";

// Si déjà connecté → on redirige vers le dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Admin</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      background: #FAF1E6;
      font-family: 'Tenor Sans', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-box {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 100%;
    }
    .login-box h2 {
      text-align: center;
      margin-bottom: 1rem;
      color: #58624B;
    }
    .login-box input {
      width: 100%;
      padding: 0.8rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .login-box button {
      width: 100%;
      padding: 0.8rem;
      background: #58624B;
      border: none;
      border-radius: 5px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    .login-box button:hover {
      background: #9A9A92;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Connexion Admin</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Nom d'utilisateur" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>
  </div>
</body>
</html>
