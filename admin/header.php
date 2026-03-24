<?php require_once '../includes/config.php';
if (!isAdmin()) redirect('/login.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - FashionShop</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <a href="/admin/index.php" class="logo">⚙ Admin</a>
        <ul>
            <li><a href="/admin/index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>📊 Dashboard</a></li>
            <li><a href="/admin/products.php" <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'class="active"' : ''; ?>>👕 Produits</a></li>
            <li><a href="/admin/categories.php" <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'class="active"' : ''; ?>>📁 Catégories</a></li>
            <li><a href="/admin/orders.php" <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'class="active"' : ''; ?>>📦 Commandes</a></li>
            <li><a href="/admin/users.php" <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'class="active"' : ''; ?>>👤 Utilisateurs</a></li>
            <li><a href="/index.php">🌐 Voir le site</a></li>
            <li><a href="/logout.php">🚪 Déconnexion</a></li>
        </ul>
    </aside>
    <main class="admin-content">
