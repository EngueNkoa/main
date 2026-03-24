<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionShop - Vêtements</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav>
    <a href="/index.php" class="logo">Fashion<span>Shop</span></a>
    <ul>
        <li><a href="/index.php">Accueil</a></li>
        <li><a href="/products.php">Produits</a></li>
        <?php if (isLoggedIn()): ?>
            <?php if (isAdmin()): ?>
                <li><a href="/admin/index.php">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="/cart.php" class="cart-icon">🛒 Panier</a></li>
            <li><a href="/logout.php">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="/login.php">Connexion</a></li>
            <li><a href="/register.php">Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>
