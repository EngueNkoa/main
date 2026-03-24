<?php require_once 'header.php'; ?>

<?php
$message = '';
$edit_product = null;

// Supprimer
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $product = $conn->query("SELECT image FROM products WHERE id=$id")->fetch_assoc();
    if ($product['image'] && file_exists('../uploads/' . $product['image'])) {
        unlink('../uploads/' . $product['image']);
    }
    $conn->query("DELETE FROM products WHERE id=$id");
    $message = "Produit supprimé.";
}

// Éditer
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}

// Ajouter / Modifier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $image_name = '';

    // Upload image
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $image_name = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image_name);
        }
    }

    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        // Modifier
        $id = (int)$_POST['product_id'];
        $img_sql = $image_name ? ", image='$image_name'" : "";
        $conn->query("UPDATE products SET name='$name', description='$description', price=$price, stock=$stock, category_id=$category_id $img_sql WHERE id=$id");
        $message = "Produit modifié avec succès.";
    } else {
        // Ajouter
        $conn->query("INSERT INTO products (name, description, price, stock, category_id, image) VALUES ('$name', '$description', $price, $stock, $category_id, '$image_name')");
        $message = "Produit ajouté avec succès.";
    }
    $edit_product = null;
}

$categories = $conn->query("SELECT * FROM categories");
$products = $conn->query("SELECT p.*, c.name as cat FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC");
?>

<div class="page-header">
    <h1><?php echo $edit_product ? 'Modifier le produit' : 'Gestion des produits'; ?></h1>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<!-- Formulaire -->
<div style="background:#fff;padding:25px;border-radius:6px;margin-bottom:30px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h3 style="margin-bottom:20px;"><?php echo $edit_product ? 'Modifier' : 'Ajouter un produit'; ?></h3>
    <form method="POST" enctype="multipart/form-data">
        <?php if ($edit_product): ?>
            <input type="hidden" name="product_id" value="<?php echo $edit_product['id']; ?>">
        <?php endif; ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <div class="form-group">
                <label>Nom du produit</label>
                <input type="text" name="name" required value="<?php echo $edit_product['name'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <select name="category_id" required>
                    <option value="">Choisir...</option>
                    <?php 
                    $categories->data_seek(0);
                    while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($edit_product['category_id']) && $edit_product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Prix (FCFA)</label>
                <input type="number" name="price" required min="0" step="0.01" value="<?php echo $edit_product['price'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" required min="0" value="<?php echo $edit_product['stock'] ?? '0'; ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo $edit_product['description'] ?? ''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Image du produit</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_product ? 'Modifier' : 'Ajouter'; ?></button>
        <?php if ($edit_product): ?>
            <a href="products.php" class="btn btn-secondary" style="margin-left:10px;">Annuler</a>
        <?php endif; ?>
    </form>
</div>

<!-- Liste des produits -->
<h2 style="margin-bottom:15px;">Liste des produits</h2>
<table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($p = $products->fetch_assoc()): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td>
                <?php if ($p['image'] && file_exists('../uploads/' . $p['image'])): ?>
                    <img src="/uploads/<?php echo $p['image']; ?>" style="width:45px;height:45px;object-fit:cover;border-radius:4px;">
                <?php else: ?>
                    <span style="font-size:24px;">👕</span>
                <?php endif; ?>
            </td>
            <td><?php echo $p['name']; ?></td>
            <td><?php echo $p['cat'] ?? '-'; ?></td>
            <td><?php echo number_format($p['price'], 0, ',', '.'); ?> FCFA</td>
            <td>
                <span style="color:<?php echo $p['stock'] > 0 ? '#27ae60' : '#e74c3c'; ?>">
                    <?php echo $p['stock']; ?>
                </span>
            </td>
            <td>
                <a href="products.php?edit=<?php echo $p['id']; ?>" class="btn btn-secondary btn-sm">✏ Modifier</a>
                <a href="products.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" 
                   onclick="return confirm('Supprimer ce produit ?')" style="margin-left:5px;">✕</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
