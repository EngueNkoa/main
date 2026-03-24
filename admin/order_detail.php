<?php require_once 'header.php'; ?>

<?php
if (!isset($_GET['id'])) redirect('/admin/orders.php');

$id = (int)$_GET['id'];
$order = $conn->query("SELECT p.*, u.name as client, u.email FROM payments p JOIN users u ON p.user_id=u.id WHERE p.id=$id")->fetch_assoc();

if (!$order) redirect('/admin/orders.php');

$items = $conn->query("SELECT oi.*, pr.name, pr.image FROM order_items oi JOIN products pr ON oi.product_id=pr.id WHERE oi.payment_id=$id");
?>

<div class="page-header">
    <h1>Détail commande #<?php echo $id; ?></h1>
    <a href="orders.php" class="btn btn-secondary btn-sm">← Retour</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:25px;">
    <div style="background:#fff;padding:20px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="margin-bottom:15px;">Informations client</h3>
        <p><strong>Nom :</strong> <?php echo $order['client']; ?></p>
        <p><strong>Email :</strong> <?php echo $order['email']; ?></p>
        <p><strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
        <p><strong>Statut :</strong> <?php echo ucfirst($order['status']); ?></p>
    </div>
    <div style="background:#fff;padding:20px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="margin-bottom:15px;">Résumé</h3>
        <p style="font-size:28px;font-weight:bold;color:#e74c3c;"><?php echo number_format($order['total'], 0, ',', '.'); ?> FCFA</p>
    </div>
</div>

<h2 style="margin-bottom:15px;">Articles commandés</h2>
<table class="admin-table">
    <thead>
        <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
    </thead>
    <tbody>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($item['image'] && file_exists('../uploads/' . $item['image'])): ?>
                    <img src="/uploads/<?php echo $item['image']; ?>" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-right:8px;vertical-align:middle;">
                <?php endif; ?>
                <?php echo $item['name']; ?>
            </td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($item['price'], 0, ',', '.'); ?> FCFA</td>
            <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> FCFA</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
