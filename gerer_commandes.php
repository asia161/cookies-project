<?php
session_start();
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

$stmt = $pdo->query("
    SELECT commandes.id, commandes.statut, utilisateurs.email,
           IFNULL(SUM(dc.quantite * p.prix), 0) AS total
    FROM commandes
    LEFT JOIN utilisateurs ON commandes.id_utilisateur = utilisateurs.id
    LEFT JOIN details_commande dc ON commandes.id = dc.id_commande
    LEFT JOIN produits p ON dc.id_produit = p.id
    GROUP BY commandes.id, utilisateurs.email, commandes.statut
    ORDER BY commandes.id DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Gérer Commandes</title>
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
    max-width: 1000px;
    margin: 30px auto;
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 15px rgba(255,102,170,0.3);
}
h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #cc0052;
    letter-spacing: 1.5px;
}
a.add-btn {
    display: inline-block;
    background-color: #ff66aa;
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 700;
    text-decoration: none;
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
}
a.add-btn:hover {
    background-color: #cc0052;
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
    font-size: 1em;
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
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
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
    <a href="gerer_comptes.php"><i class="fa-solid fa-users"></i> Gérer Comptes</a>
    <a href="gerer_commandes.php"><i class="fa-solid fa-box"></i> Commandes</a>
    <a href="produits.php"><i class="fa-solid fa-cookie-bite"></i> Produits</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
</nav>

<div class="container">
    <h1><i class="fa-solid fa-truck"></i> Gérer les Commandes</h1>

    <a href="produits.php" class="add-btn">
        <i class="fa-solid fa-plus"></i> Ajouter
    </a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email Client</th>
                <th>Statut</th>
                <th>Total (MAD)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($orders)): ?>
            <tr><td colspan="5" style="text-align:center;">Aucune commande pour le moment.</td></tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['email'] ?? '—') ?></td>
                <td><?= htmlspecialchars($order['statut']) ?></td>
                <td><?= number_format($order['total'], 2, ',', ' ') ?></td>
                <td>
                    <a href="voir_commande.php?id=<?= $order['id'] ?>" class="button-link">
                        <button><i class="fa-solid fa-eye"></i></button>
                    </a>
                    <a href="modifier_commande.php?id=<?= $order['id'] ?>" class="button-link">
                        <button><i class="fa-solid fa-pen"></i></button>
                    </a>
                    <a href="supprimer_commande.php?id=<?= $order['id'] ?>" class="button-link" onclick="return confirm('Confirmer la suppression ?');">
                        <button><i class="fa-solid fa-trash"></i></button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
