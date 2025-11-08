<?php
session_start();
require_once 'db_cnx.php';

// التأكد من الصلاحيات
if (!isset($_SESSION['session_admin']) || $_SESSION['session_admin']['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID produit manquant");
}

// جلب بيانات المنتج
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit non trouvé");
}

// تعديل المنتج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom_produit'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite_stock'];

    $stmt = $pdo->prepare("UPDATE produits SET nom_produit = ?, description = ?, prix = ?, quantite_stock = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $prix, $quantite, $id]);

    header('Location: produits.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Modifier Produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff0f5;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: #ffe4f0;
            padding: 20px;
            border-radius: 15px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #c71585;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        button:hover {
            background-color: #ff1493;
        }
    </style>
</head>
<body>

<h1 style="text-align:center;">Modifier le produit</h1>

<form method="POST">
    <label>Nom :</label>
    <input type="text" name="nom_produit" value="<?= htmlspecialchars($produit['nom_produit']) ?>" required>

    <label>Description :</label>
    <textarea name="description" required><?= htmlspecialchars($produit['description']) ?></textarea>

    <label>Prix :</label>
    <input type="number" step="0.01" name="prix" value="<?= htmlspecialchars($produit['prix']) ?>" required>

    <label>Quantité en stock :</label>
    <input type="number" name="quantite_stock" value="<?= htmlspecialchars($produit['quantite_stock']) ?>" required>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>
