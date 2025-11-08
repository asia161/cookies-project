<?php
session_start();
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'db_cnx.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID de commande invalide.");
}

try {
    // حذف تفاصيل الطلب (details_commande)
    $stmt_delete_details = $pdo->prepare("DELETE FROM details_commande WHERE id_commande= ?");
    $stmt_delete_details->execute([$id]);

    // حذف الطلب نفسه (commandes)
    $stmt_delete_commande = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
    $stmt_delete_commande->execute([$id]);

    // إعادة التوجيه مع رسالة نجاح
    header('Location: gerer_commandes.php?message=Commande supprimée avec succès');
    exit;
} catch (Exception $e) {
    echo "Erreur lors de la suppression : " . $e->getMessage();
}
?>
