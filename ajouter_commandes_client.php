<?php
session_start();
require_once 'db_cnx.php';

if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['session_admin']['id'];
$id_produit = $_POST['id_produit'] ?? null;
$quantite = 1; // ثابت مؤقتا

if (!$id_produit) {
    die("Produit non spécifié.");
}

// 1. جلب معلومات المنتوج
$stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = ?");
$stmt->execute([$id_produit]);
$produit = $stmt->fetch();

if (!$produit) {
    die("Produit introuvable.");
}

$prix_unitaire = $produit['prix'];

// 2. إنشاء commande جديدة
$stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur, date_commande, statut, total_prix) VALUES (?, NOW(), 'en_attente', 0)");
$stmt->execute([$id_utilisateur]);
$id_commande = $pdo->lastInsertId();

// 3. إدخال détail_commande
$stmt = $pdo->prepare("INSERT INTO details_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
$stmt->execute([$id_commande, $id_produit, $quantite, $prix_unitaire]);

// 4. التوجيه
header("Location: client_commande.php");
exit;
