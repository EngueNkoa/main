<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>Bienvenue sur FashionShop</h1>
    <p>Découvrez notre collection de vêtements pour toute la famille</p>
    <a href="products.php" class="btn btn-primary">Voir les produits</a>
</div>

<div class="container">
    <h2 style="margin-bottom:20px;">Nouveaux Arrivages</h2>
    <div class="products-grid">
        <?php
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC LIMIT 8";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0):
            while ($product = $result->fetch_assoc()):
        ?>
        <div class="product-card">
            <?php if ($product['image'] && file_exists('uploads/' . $product['image'])): ?>
                <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <?php else: ?>
                <div class="no-image">👕</div>
            <?php endif; ?>
            <div class="card-body">
                <div class="category"><?php echo $product['category_name'] ?? 'Non catégorisé'; ?></div>
                <h3><?php echo $product['name']; ?></h3>
                <div class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> FCFA</div>
                <?php if ($product['stock'] > 0): ?>
                    <div class="stock">En stock (<?php echo $product['stock']; ?>)</div>
                <?php else: ?>
                    <div class="stock out">Rupture de stock</div>
                <?php endif; ?>
                <?php if (isLoggedIn() && $product['stock'] > 0): ?>
                    <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Ajouter au panier</a>
                <?php elseif (!isLoggedIn()): ?>
                    <a href="login.php" class="btn btn-secondary btn-sm">Connectez-vous</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; else: ?>
        <p>Aucun produit disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
