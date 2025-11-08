<?php
session_start();
if (!isset($_SESSION['session_admin']) || $_SESSION['session_admin']['role'] !== 'client') {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

$user_id = $_SESSION['session_admin']['id'];

$stmt = $pdo->prepare("
    SELECT c.id, c.statut, c.date_commande, 
           IFNULL(SUM(dc.quantite * p.prix), 0) AS total
    FROM commandes c
    LEFT JOIN details_commande dc ON c.id = dc.id_commande
    LEFT JOIN produits p ON dc.id_produit = p.id
    WHERE c.id_utilisateur = ?
    GROUP BY c.id, c.statut, c.date_commande
    ORDER BY c.date_commande DESC
");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Mes Commandes</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
body {
    background: #ffe6f2;
    font-family: Arial, sans-serif;
    color: #660029;
    margin: 0; padding: 20px;
}
.container {
    max-width: 900px;
    margin: 30px auto;
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 15px rgba(255,102,170,0.3);
}
h1 {
    color: #cc0052;
    text-align: center;
    margin-bottom: 25px;
}
.btn-ajouter {
    display: inline-block;
    background-color: #cc0052;
    color: white;
    padding: 10px 18px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 700;
    transition: background-color 0.3s ease;
    margin-bottom: 20px;
}
.btn-ajouter:hover {
    background-color: #ff66aa;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background-color: #ff66aa;
    color: white;
    font-weight: 700;
}
</style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-box"></i> Mes Commandes</h1>

    <a href="produits.php" class="btn-ajouter">
        <i class="fa-solid fa-cart-plus"></i> Ajouter une commande
    </a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Total (MAD)</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($commandes): ?>
                <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['id']) ?></td>
                    <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                    <td><?= htmlspecialchars($commande['statut']) ?></td>
                    <td><?= number_format($commande['total'], 2, ',', ' ') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Aucune commande trouvée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
