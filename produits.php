<?php
session_start();
require_once 'db_cnx.php';

// التحقق من الجلسة
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

$role = $_SESSION['session_admin']['role'] ?? 'client';
$isAdmin = ($role === 'admin');

// جلب المنتجات
try {
    $stmt = $pdo->query("SELECT * FROM produits ORDER BY date_ajout DESC");
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des produits : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff0f5;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #c71585;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .produit {
            background: #ffe4f0;
            border-radius: 15px;
            padding: 15px;
            width: 230px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .produit img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .produit h3 {
            color: #c71585;
        }
        .prix {
            font-weight: bold;
            color: #8b0000;
            margin: 8px 0;
        }
        .boutons a, .boutons button {
            margin: 5px;
            padding: 8px 12px;
            border: none;
            background-color: #ff69b4;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .boutons a:hover, .boutons button:hover {
            background-color: #ff1493;
        }
        .ajouter {
            display: inline-block;
            margin: 15px auto;
            text-align: center;
        }
        form {
            display: inline;
        }
    </style>
</head>
<body>

<h1>Nos Délicieux Produits</h1>

<?php if ($isAdmin): ?>
    <div class="ajouter">
        <a href="ajouter_prd.php" class="boutons">+ Ajouter un produit</a>
    </div>
<?php endif; ?>

<div class="container">
    <?php if (empty($produits)): ?>
        <p>Aucun produit disponible pour le moment.</p>
    <?php else: ?>
        <?php foreach ($produits as $produit): ?>
            <div class="produit">
                <?php
                $imagePath = 'uploads/' . ($produit['image'] ?? '');
                if (!file_exists($imagePath) || empty($produit['image'])) {
                    $imagePath = 'uploads/placeholder.jpg';
                }
                ?>
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($produit['nom_produit']) ?>" onerror="this.src='uploads/placeholder.jpg'">
                <h3><?= htmlspecialchars($produit['nom_produit']) ?></h3>
                <p class="prix"><?= number_format($produit['prix'], 2, ',', ' ') ?> DH</p>
                <p><?= nl2br(htmlspecialchars($produit['description'])) ?></p>

                <div class="boutons">
                    <?php if ($isAdmin): ?>
                        <a href="modifier_prd.php?id=<?= $produit['id'] ?>">Modifier</a>
                        <a href="supprimer_prd.php?id=<?= $produit['id'] ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                    <?php else: ?>
                        <form action="ajouter_commandes_client.php" method="POST">
                            <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">
                            <input type="hidden" name="prix" value="<?= $produit['prix'] ?>">
                            <input type="hidden" name="nom_produit" value="<?= htmlspecialchars($produit['nom_produit']) ?>">
                            <button type="submit">Ajouter au panier</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
