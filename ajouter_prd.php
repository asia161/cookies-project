<?php
require_once 'db_cnx.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];

    // يمكن هنا رفع صورة وإدخالها أيضاً

    $stmt = $pdo->prepare("INSERT INTO produits (nom_produit, description, prix, quantite_stock, date_ajout) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$nom, $description, $prix, $quantite]);

    header('Location: produits.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Ajouter Produit</title>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #fff0f5;
    padding: 30px;
    max-width: 500px;
    margin: 0 auto;
    color: #4a004a;
}

h1 {
    text-align: center;
    color: #c71585;
    margin-bottom: 25px;
}

form label {
    font-weight: bold;
    margin-top: 15px;
    display: block;
    color: #900050;
}

input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 8px 10px;
    margin-top: 5px;
    border: 2px solid #ffb6c1;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 16px;
    color: #4a004a;
    background-color: #fff0f5;
    transition: border-color 0.3s ease;
    resize: vertical;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
    border-color: #c71585;
    outline: none;
    background-color: #ffe4f0;
}

button {
    margin-top: 25px;
    width: 100%;
    background: linear-gradient(45deg, #ff69b4, #ff1493);
    border: none;
    color: white;
    padding: 12px 0;
    font-size: 18px;
    font-weight: bold;
    border-radius: 25px;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: linear-gradient(45deg, #ff1493, #c71585);
}

</style>
</head>
<body>
<h1>Ajouter un nouveau produit</h1>
<form method="post" enctype="multipart/form-data">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br>

    <label>Description :</label><br>
    <textarea name="description" required></textarea><br>

    <label>Prix :</label><br>
    <input type="number" step="0.01" name="prix" required><br>

    <label>Quantité :</label><br>
    <input type="number" name="quantite" required><br>

    <button type="submit">Ajouter</button>
</form>
</body>
</html>
