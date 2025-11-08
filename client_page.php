<?php
session_start();
if (!isset($_SESSION['session_admin']) || $_SESSION['session_admin']['role'] !== 'client') {
    header('Location: connexion.php');
    exit;
}

$user = $_SESSION['session_admin'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Page Client</title>
<!-- ربط Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
body {
    background: #ffe6f2;
    font-family: Arial, sans-serif;
    color: #660029;
    margin: 0; padding: 0;
}
header {
    background-color: #ff66aa;
    color: white;
    padding: 20px 30px;
    text-align: center;
    font-weight: 700;
    font-size: 1.5em;
}
nav {
    background-color: #cc0052;
    display: flex;
    justify-content: center;
    gap: 40px;
    padding: 15px 0;
}
nav a {
    color: white;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1em;
    padding: 8px 20px;
    border-radius: 30px;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
nav a:hover {
    background-color: #ff66aa;
}
.container {
    max-width: 900px;
    margin: 30px auto;
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 15px rgba(255,102,170,0.3);
    text-align: center;
}
</style>
</head>
<body>

<header>
    Bonjour, <?= htmlspecialchars($user['nom_utilisateur']) ?> !
</header>

<nav>
    <a href="client_commande.php"><i class="fa-solid fa-box"></i> Mes Commandes</a>
    <a href="client_compte.php"><i class="fa-solid fa-user"></i> Mon Compte</a>
    <a href="produits.php"><i class="fa-solid fa-shopping-cart"></i> Produits</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
</nav>

<div class="container">
    <h2>Bienvenue sur votre espace client</h2>
    <p>Utilisez la navigation ci-dessus pour gérer vos commandes, votre compte et voir les produits disponibles.</p>
</div>

</body>
</html>
