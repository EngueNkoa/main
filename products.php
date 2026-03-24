<?php require_once 'includes/header.php'; ?>

<div class="container">
    <h1 style="margin-bottom:20px;">Tous les produits</h1>

    <!-- Filtres -->
    <form method="GET" class="filters">
        <select name="category" onchange="this.form.submit()">
            <option value="">Toutes les catégories</option>
            <?php
            $cats = $conn->query("SELECT * FROM categories");
            while ($cat = $cats->fetch_assoc()):
                $selected = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '';
            ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>><?php echo $cat['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="search" placeholder="Rechercher..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
        <a href="products.php" class="btn btn-secondary btn-sm">Réinitialiser</a>
    </form>

    <!-- Grille produits -->
    <div class="products-grid">
        <?php
        $where = "WHERE 1=1";
        if (!empty($_GET['category'])) {
            $cat_id = (int)$_GET['category'];
            $where .= " AND p.category_id = $cat_id";
        }
        if (!empty($_GET['search'])) {
            $search = sanitize($_GET['search']);
            $where .= " AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
        }

        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                $where ORDER BY p.created_at DESC";
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
                <p style="font-size:13px;color:#666;margin-bottom:8px;"><?php echo substr($product['description'], 0, 80); ?>...</p>
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
        <p>Aucun produit trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
