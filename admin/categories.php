<?php require_once 'header.php'; ?>

<?php
$message = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id=$id");
    $message = "Catégorie supprimée.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    
    if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])) {
        $id = (int)$_POST['cat_id'];
        $conn->query("UPDATE categories SET name='$name', description='$description' WHERE id=$id");
        $message = "Catégorie modifiée.";
    } else {
        $conn->query("INSERT INTO categories (name, description) VALUES ('$name', '$description')");
        $message = "Catégorie ajoutée.";
    }
}

$edit_cat = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_cat = $conn->query("SELECT * FROM categories WHERE id=$id")->fetch_assoc();
}

$categories = $conn->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id=p.category_id GROUP BY c.id");
?>

<h1>Gestion des catégories</h1>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div style="background:#fff;padding:25px;border-radius:6px;margin-bottom:30px;box-shadow:0 2px 8px rgba(0,0,0,0.08);max-width:500px;">
    <h3 style="margin-bottom:20px;"><?php echo $edit_cat ? 'Modifier' : 'Ajouter une catégorie'; ?></h3>
    <form method="POST">
        <?php if ($edit_cat): ?>
            <input type="hidden" name="cat_id" value="<?php echo $edit_cat['id']; ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="name" required value="<?php echo $edit_cat['name'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?php echo $edit_cat['description'] ?? ''; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $edit_cat ? 'Modifier' : 'Ajouter'; ?></button>
        <?php if ($edit_cat): ?>
            <a href="categories.php" class="btn btn-secondary" style="margin-left:10px;">Annuler</a>
        <?php endif; ?>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr><th>#</th><th>Nom</th><th>Description</th><th>Produits</th><th>Actions</th></tr>
    </thead>
    <tbody>
        <?php while ($cat = $categories->fetch_assoc()): ?>
        <tr>
            <td><?php echo $cat['id']; ?></td>
            <td><?php echo $cat['name']; ?></td>
            <td><?php echo $cat['description']; ?></td>
            <td><?php echo $cat['product_count']; ?></td>
            <td>
                <a href="categories.php?edit=<?php echo $cat['id']; ?>" class="btn btn-secondary btn-sm">✏ Modifier</a>
                <a href="categories.php?delete=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Supprimer cette catégorie ?')" style="margin-left:5px;">✕</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
