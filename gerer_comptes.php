<?php
session_start();

if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// طلب SQL مع البحث
if ($search !== '') {
    $stmt_users = $pdo->prepare("SELECT id, email, role FROM utilisateurs 
                                 WHERE email LIKE :search OR role LIKE :search
                                 ORDER BY id DESC");
    $stmt_users->execute(['search' => "%$search%"]);
} else {
    $stmt_users = $pdo->query("SELECT id, email, role FROM utilisateurs ORDER BY id DESC");
}
$users = $stmt_users->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Gérer Comptes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            background: #ffe6f2;
            color: #660029;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 20px;
        }
        nav {
            background-color: #ff66aa;
            padding: 15px 30px;
            display: flex;
            gap: 20px;
            box-shadow: 0 3px 8px rgba(255,102,170,0.5);
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #cc0052;
        }
        .container {
            max-width: 950px;
            margin: 30px auto;
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(255,102,170,0.3);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #cc0052;
        }
        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            width: 50%;
            padding: 10px;
            border-radius: 25px;
            border: 1px solid #ccc;
        }
        .search-bar button {
            background-color: #ff66aa;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 25px;
            margin-left: 10px;
            cursor: pointer;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(255,102,170,0.1);
        }
        thead {
            background-color: #ff66aa;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f8bbd0;
        }
        tbody tr:hover {
            background-color: #ffe0f0;
        }
        button {
            background-color: #ff66aa;
            border: none;
            color: white;
            padding: 7px 14px;
            border-radius: 25px;
            cursor: pointer;
            margin-right: 5px;
        }
        button:hover {
            background-color: #cc0052;
        }
        a.button-link {
            all: unset;
            display: inline-block;
        }
    </style>
</head>
<body>

<nav>
    <a href="dashbord.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="gerer_comptes.php"><i class="fa-solid fa-users"></i> Comptes</a>
    <a href="gerer_commandes.php"><i class="fa-solid fa-box"></i> Commandes</a>
    <a href="produits.php"><i class="fa-solid fa-cookie-bite"></i> Produits</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
</nav>

<div class="container">
    <h1><i class="fa-solid fa-users-gear"></i> Gérer Comptes Utilisateurs</h1>

    <div class="search-bar">
        <form method="get" action="">
            <input type="text" name="search" placeholder="Rechercher par email ou rôle..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Chercher</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th><i class="fa-solid fa-envelope"></i> Email</th>
                <th><i class="fa-solid fa-user-tag"></i> Rôle</th>
                <th><i class="fa-solid fa-gears"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) === 0): ?>
                <tr><td colspan="3" style="text-align:center;">Aucun utilisateur trouvé.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="modifier_compte.php?id=<?= $user['id'] ?>" class="button-link"><button><i class="fa-solid fa-pen"></i></button></a>
                            <a href="supprimer_compte.php?id=<?= $user['id'] ?>" class="button-link" onclick="return confirm('Confirmer la suppression ?');"><button><i class="fa-solid fa-trash"></i></button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
