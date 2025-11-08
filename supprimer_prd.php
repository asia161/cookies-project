
<?php
session_start();
require_once 'db_cnx.php';

// فقط المسؤول هو اللي يقدر يحذف
if (!isset($_SESSION['session_admin']) || $_SESSION['session_admin']['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

// التأكد من وجود id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID du produit manquant.";
    exit;
}

$id = intval($_GET['id']); // تأمين id

try {
    // حذف المنتج
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$id]);

    // بعد الحذف نرجعو للصفحة الرئيسية للمنتجات
    header("Location: produits.php");
    exit;

} catch (PDOException $e) {
    echo "Erreur lors de la suppression : " . $e->getMessage();
}
