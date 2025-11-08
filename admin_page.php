
<?php
session_start();
if (!isset($_SESSION['session_admin'])) {
    header('Location: connexion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard</title>
<!-- ربط Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
    body {
        background: #ffe6f2;
        color: #660029;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0; padding: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    nav {
        background-color: #ff66aa;
        padding: 15px 30px;
        box-shadow: 0 3px 8px rgba(255,102,170,0.5);
        display: flex;
        align-items: center;
        gap: 25px;
    }
    nav a {
        color: white;
        font-weight: 700;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 25px;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1em;
    }
    nav a:hover {
        background-color: #cc0052;
    }
    main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2em;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: capitalize;
    }
</style>
</head>
<body>

<nav>
    <a href="gerer_comptes.php"><i class="fa-solid fa-users"></i> Gérer Comptes</a>
    <a href="gerer_commandes.php"><i class="fa-solid fa-box"></i> Commandes</a>
    <a href="produits.php"><i class="fa-solid fa-shopping-cart"></i> Produits</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
    <a href="dashbord.php"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a>
</nav>

<main>
    Bonjour, admin <?= htmlspecialchars($_SESSION['session_admin']['nom_utilisateur'] ?? 'Utilisateur') ?>!
</main>

</body>
</html>
