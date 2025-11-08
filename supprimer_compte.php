<?php
session_start();
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

if (!isset($_GET['id'])) {
    header('Location: admin_comptes.php');
    exit;
}

$id = intval($_GET['id']);

// جلب بيانات المستخدم المراد حذفه
$stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}

// منع حذف أي مستخدم بدور 'admin'
if ($user['role'] === 'admin') {
    echo "Impossible de supprimer un compte administrateur.";
    exit;
}

// حذف المستخدم
$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin_page.php');
exit;
