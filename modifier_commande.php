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

$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$id]);
$commande = $stmt->fetch();

if (!$commande) {
    die("Commande introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_statut = $_POST['statut'] ?? '';
    
    $update = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
    if ($update->execute([$nouveau_statut, $id])) {
        header("Location: gerer_commandes.php?msg=commande_modifiee");
        exit;
    } else {
        $erreur = "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Commande</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #ffe6f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 600px;
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
        label {
            display: block;
            margin-bottom: 10px;
            color: #660029;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        button {
            background-color: #ff66aa;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
        }
        button:hover {
            background-color: #cc0052;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #cc0052;
            font-weight: 600;
            text-decoration: none;
        }
        .back-link i {
            margin-right: 5px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-pen-to-square"></i> Modifier Commande #<?= htmlspecialchars($commande['id']) ?></h1>

    <?php if (!empty($erreur)): ?>
        <p class="error"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="statut"><i class="fas fa-truck"></i> Statut de la commande :</label>
        <select name="statut" id="statut" required>
            <option value="en attente" <?= $commande['statut'] === 'en attente' ? 'selected' : '' ?>>🕓 En attente</option>
            <option value="confirmée" <?= $commande['statut'] === 'confirmée' ? 'selected' : '' ?>>✅ Confirmée</option>
            <option value="annulée" <?= $commande['statut'] === 'annulée' ? 'selected' : '' ?>>❌ Annulée</option>
            <option value="livrée" <?= $commande['statut'] === 'livrée' ? 'selected' : '' ?>>📦 Livrée</option>
        </select>

        <button type="submit"><i class="fas fa-save"></i> Enregistrer</button>
    </form>

    <a href="gerer_commandes.php" class="back-link"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

</body>
</html>
