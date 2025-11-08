<?php
session_start();
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

if (!isset($_GET['id'])) {
    header('Location: admin_page.php');
    exit;
}

$id = intval($_GET['id']);

// جلب بيانات المستخدم
$stmt = $pdo->prepare("SELECT id, email, role FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';

    // تحديث البيانات
    $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ?, role = ? WHERE id = ?");
    $stmt->execute([$email, $role, $id]);

    header('Location: gerer_comptes.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Modifier Compte</title>
<style>
    body {
        background: #ffe6f2;
        color: #660029;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
    }
    form {
        background: white;
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 8px 15px rgba(255,102,170,0.3);
    }
    label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 2px solid #ff66aa;
        border-radius: 10px;
        font-size: 1em;
        color: #660029;
    }
    button {
        background-color: #ff66aa;
        border: none;
        color: white;
        padding: 12px;
        width: 100%;
        font-weight: 700;
        font-size: 1em;
        border-radius: 25px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #cc0052;
    }
    a {
        display: block;
        margin-top: 15px;
        text-align: center;
        color: #cc0052;
        text-decoration: none;
    }
</style>
</head>
<body>

<form method="post">
    <h2>Modifier Compte</h2>
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>" />

    <label for="role">Rôle :</label>
    <select id="role" name="role" required>
        <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <button type="submit">Enregistrer</button>
    <a href="admin_page.php">Annuler</a>
</form>

</body>
</html>
