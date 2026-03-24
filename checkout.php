<?php require_once 'includes/header.php'; ?>

<?php if (!isLoggedIn()) redirect('/login.php'); ?>

<?php
$user_id = $_SESSION['user_id'];

// Récupérer le panier
$sql = "SELECT c.id, c.quantity, p.name, p.price, p.id as product_id, p.stock 
        FROM cart c JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);
$items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['price'] * $row['quantity'];
}

if (empty($items)) redirect('/cart.php');

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Créer le paiement
    $conn->query("INSERT INTO payments (user_id, total, status) VALUES ($user_id, $total, 'completed')");
    $payment_id = $conn->insert_id;
    
    // Ajouter les articles commandés
    foreach ($items as $item) {
        $conn->query("INSERT INTO order_items (payment_id, product_id, quantity, price) 
                      VALUES ($payment_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
        // Réduire le stock
        $conn->query("UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['product_id']}");
    }
    
    // Vider le panier
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");
    
    $success = "Commande passée avec succès ! Merci pour votre achat.";
}
?>

<div class="container" style="max-width:700px;">
    <h1 style="margin-bottom:20px;">Récapitulatif de commande</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?> <a href="index.php">Retour à l'accueil</a></div>
    <?php else: ?>
        <table class="cart-table" style="margin-bottom:20px;">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> FCFA</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="total">Total : <?php echo number_format($total, 0, ',', '.'); ?> FCFA</div>
            <form method="POST">
                <button type="submit" class="btn btn-success">✓ Confirmer la commande</button>
                <a href="cart.php" class="btn btn-secondary" style="margin-left:10px;">← Retour au panier</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
