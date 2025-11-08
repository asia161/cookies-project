<?php
session_start();
if (!isset($_SESSION['session_admin']) || $_SESSION['session_admin']['role'] !== 'client') {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

$user_id = $_SESSION['session_admin']['id'];

$stmt = $pdo->prepare("SELECT nom_utilisateur, email, date_inscription FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user_info = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Mon Compte</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
body {
    background: #ffe6f2;
    font-family: Arial, sans-serif;
    color: #660029;
    margin: 0; padding: 20px;
}
.container {
    max-width: 500px;
    margin: 40px auto;
    background: white;
    padding: 30px 35px;
    border-radius: 15px;
    box-shadow: 0 8px 15px rgba(255,102,170,0.3);
}
h1 {
    text-align: center;
    color: #cc0052;
    margin-bottom: 25px;
    font-weight: 700;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
}
p {
    font-size: 1.1em;
    margin-bottom: 18px;
}
label {
    font-weight: 700;
}
</style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-user"></i> Mon Compte</h1>

    <p><label>Nom d'utilisateur : </label> <?= htmlspecialchars($user_info['nom_utilisateur']) ?></p>
    <p><label>Email : </label> <?= htmlspecialchars($user_info['email']) ?></p>
    <p><label>Date d'inscription : </label> <?= htmlspecialchars(date('d/m/Y', strtotime($user_info['date_inscription']))) ?></p>
</div>

</body>
</html>
