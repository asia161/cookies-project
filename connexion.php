<?php
session_start();
require_once 'db_cnx.php';

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['session_admin'] = [
                'id' => $user['id'],
                'nom_utilisateur' => $user['nom_utilisateur'],
                'role' => $user['role']
            ];

            if ($user['role'] === 'admin') {
                header('Location: admin_page.php');
                exit;
            } else {
                header('Location: client_page.php');
                exit;
            }
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 400px; margin: 100px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid #ccc;
        }
        .error { color: red; margin-bottom: 10px; }
        button { width: 100%; padding: 10px; background:rgb(245, 175, 217); color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background:rgb(239, 160, 217); }
    </style>
</head>
<body>
<div class="container">
    <h2>Connexion</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required />
        <input type="password" name="password" placeholder="Mot de passe" required />
        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>
