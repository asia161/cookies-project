<?php
session_start();
require_once 'db_cnx.php';

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

// التأكد من وجود بيانات مرسلة
$id_utilisateur = $_SESSION['session_admin']['id'];
$id_produit = $_POST['id_produit'] ?? null;
$quantite = 1;

if (!$id_produit) {
    die("Produit non trouvé.");
}

try {
    // إنشاء الطلب
    $stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur) VALUES (:a)");
    $stmt->execute([":a"=> $id_utilisateur]);
    $id_commande = $pdo->lastInsertId();

    // إضافة تفاصيل الطلب
    $stmt2 = $pdo->prepare("INSERT INTO details_commande (id_commande, id_produit, quantite) VALUES (?, ?, ?)");
    $stmt2->execute([$id_commande, $id_produit, $quantite]);

    header('Location:gerer_commandes.php?success=1');
    exit;
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>