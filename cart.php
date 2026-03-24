<?php require_once 'includes/header.php'; ?>

<?php if (!isLoggedIn()) redirect('/login.php'); ?>

<?php
// Supprimer un article
if (isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    $user_id = $_SESSION['user_id'];
    $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    redirect('/cart.php');
}

// Mettre à jour la quantité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $cart_id => $qty) {
        $cart_id = (int)$cart_id;
        $qty = (int)$qty;
        $user_id = $_SESSION['user_id'];
        if ($qty > 0) {
            $conn->query("UPDATE cart SET quantity = $qty WHERE id = $cart_id AND user_id = $user_id");
        } else {
            $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
        }
    }
    redirect('/cart.php');
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id, c.quantity, p.name, p.price, p.image, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);
$items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['price'] * $row['quantity'];
}
?>

<div class="container">
    <h1 style="margin-bottom:20px;">Mon Panier</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?>"><?php echo $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="alert alert-info">Votre panier est vide. <a href="products.php">Continuer les achats</a></div>
    <?php else: ?>
        <form method="POST">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['image'] && file_exists('uploads/' . $item['image'])): ?>
                                <img src="uploads/<?php echo $item['image']; ?>" style="width:50px;height:50px;object-fit:cover;margin-right:10px;vertical-align:middle;">
                            <?php endif; ?>
                            <?php echo $item['name']; ?>
                        </td>
                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?> FCFA</td>
                        <td>
                            <input type="number" name="quantity[<?php echo $item['id']; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   min="0" max="<?php echo $item['stock']; ?>" 
                                   style="width:70px;padding:5px;border:1px solid #ddd;border-radius:4px;">
                        </td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> FCFA</td>
                        <td>
                            <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Supprimer cet article ?')">✕</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top:15px;">
                <button type="submit" name="update" class="btn btn-secondary">Mettre à jour</button>
            </div>
        </form>

        <div class="cart-summary">
            <div class="total">Total : <?php echo number_format($total, 0, ',', '.'); ?> FCFA</div>
            <a href="checkout.php" class="btn btn-success">Passer la commande →</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
