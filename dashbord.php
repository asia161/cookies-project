<?php
include 'db_cnx.php';

// البيانات من قاعدة البيانات
$stmt = $pdo->query("SELECT COUNT(*) AS total_products FROM produits");
$product_count = $stmt->fetch()['total_products'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM commandes");
$order_count = $stmt->fetch()['total_orders'];

$stmt = $pdo->query("SELECT SUM(total_prix) AS total_revenue FROM commandes");
$total_revenue = $stmt->fetch()['total_revenue'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) AS total_clients FROM utilisateurs");
$client_count = $stmt->fetch()['total_clients'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fff0f5, #ffe4f0);
            padding: 20px;
            margin: 0;
        }
        h1 {
            color: #c71585;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            background: #ffe4f0;
            border-radius: 15px;
            padding: 30px 25px;
            width: 200px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(199, 21, 133, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(199, 21, 133, 0.3);
        }
        .count {
            font-size: 3em;
            color: #c71585;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .label {
            font-size: 1.2em;
            color: #8b0000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .currency {
            color:rgba(217, 61, 134, 0.53);
        }
    </style>
</head>
<body>
    <h1>Tableau de Bord - Admin</h1>
    <div class="dashboard">
        <div class="card">
            <div class="count"><?= $product_count ?></div>
            <div class="label">Produits</div>
        </div>

        <div class="card">
            <div class="count"><?= $order_count ?></div>
            <div class="label">Commandes</div>
        </div>

        <div class="card">
            <div class="count"><?= $client_count ?></div>
            <div class="label">Clients</div>
        </div>

        <div class="card">
            <div class="count currency"><?= number_format($total_revenue, 2, ',', ' ') ?> MAD</div>
            <div class="label">Revenu total</div>
        </div>
    </div>
</body>
</html>
