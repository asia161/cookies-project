<?php
$users = [
    'asia' => 'asia123',
    'nadia' => 'nadia123',
    'sia' => 'sia123',
    'shaimae' => 'shaimae123',
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Générateur de mots de passe hashés</title>
<style>
    body { font-family: Arial, sans-serif; background: #ffe6f2; color: #660029; padding: 20px; }
    table { border-collapse: collapse; width: 100%; max-width: 600px; margin: auto; background: white; border-radius: 10px; }
    th, td { padding: 12px 15px; border-bottom: 1px solid #f8bbd0; text-align: left; }
    th { background-color: #ff66aa; color: white; }
    tr:hover { background-color: #ffe0f0; }
    h1 { text-align: center; margin-bottom: 30px; }
</style>
</head>
<body>

<h1>Hash des mots de passe</h1>

<table>
    <thead>
        <tr>
            <th>Nom utilisateur</th>
            <th>Mot de passe clair</th>
            <th>Mot de passe hashé (bcrypt)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $username => $password_plain): ?>
        <tr>
            <td><?= htmlspecialchars($username) ?></td>
            <td><?= htmlspecialchars($password_plain) ?></td>
            <td><?= password_hash($password_plain, PASSWORD_BCRYPT) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

