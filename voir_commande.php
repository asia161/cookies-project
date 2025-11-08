<?php
session_start();
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

$id_commande = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_commande <= 0) {
    die("ID commande invalide.");
}

// جلب تفاصيل الطلب
$stmt_commande = $pdo->prepare("
    SELECT c.id, c.date_commande, c.statut, u.email
    FROM commandes c
    LEFT JOIN utilisateurs u ON c.id_utilisateur = u.id
    WHERE c.id = ?
");
$stmt_commande->execute([$id_commande]);
$commande = $stmt_commande->fetch();

if (!$commande) {
    die("Commande non trouvée.");
}

// جلب تفاصيل المنتجات داخل الطلب
$stmt_details = $pdo->prepare("
    SELECT p.nom_produit, p.prix, dc.quantite
    FROM details_commande dc
    LEFT JOIN produits p ON dc.id_produit = p.id
    WHERE dc.id_commande = ?
");
$stmt_details->execute([$id_commande]);
$produits = $stmt_details->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir Commande</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #ffe6f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 30px;
            color: #660029;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(255, 102, 170, 0.2);
        }
        h1 {
            text-align: center;
            color: #cc0052;
            margin-bottom: 30px;
        }
        .info, .produits {
            margin-bottom: 30px;
        }
        .info i, .produits i {
            color: #cc0052;
            margin-right: 8px;
        }
        .ligne {
            margin: 10px 0;
            font-size: 1.1em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(255,102,170,0.1);
        }
        thead {
            background-color: #ff66aa;
            color: white;
        }
        th, td {
            padding: 10px 15px;
            text-align: left;
            border-bottom: 1px solid #f8bbd0;
        }
        tbody tr:hover {
            background-color: #ffe0f0;
        }
        .total {
            text-align: right;
            font-weight: bold;
            color: #cc0052;
            margin-top: 10px;
        }
        a.back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #cc0052;
            font-weight: 600;
        }
        a.back i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-eye"></i> Détails de la Commande #<?= htmlspecialchars($commande['id']) ?></h1>

    <div class="info">
        <div class="ligne"><i class="fas fa-user"></i> <strong>Email client :</strong> <?= htmlspecialchars($commande['email'] ?? 'Non disponible') ?></div>
        <div class="ligne"><i class="fas fa-calendar"></i> <strong>Date :</strong> <?= htmlspecialchars($commande['date_commande']) ?></div>
        <div class="ligne"><i class="fas fa-info-circle"></i> <strong>Statut :</strong> <?= htmlspecialchars($commande['statut']) ?></div>
    </div>

    <div class="produits">
        <h3><i class="fas fa-box"></i> Produits dans la commande :</h3>
        <table>
            <thead>
                <tr>
                    <th>Nom Produit</th>
                    <th>Prix (MAD)</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($produits as $p): 
                    $ligne_total = $p['prix'] * $p['quantite'];
                    $total += $ligne_total;
                ?>
                <tr>
                    <td><?= htmlspecialchars($p['nom_produit']) ?></td>
                    <td><?= number_format($p['prix'], 2, ',', ' ') ?></td>
                    <td><?= $p['quantite'] ?></td>
                    <td><?= number_format($ligne_total, 2, ',', ' ') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">Total de la commande : <?= number_format($total, 2, ',', ' ') ?> MAD</div>
    </div>

    <a href="gerer_commandes.php" class="back"><i class="fas fa-arrow-left"></i> Retour aux commandes</a>
</div>

</body>
</html>
