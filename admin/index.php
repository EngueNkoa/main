<?php require_once 'header.php'; ?>

<?php
$total_products = $conn->query("SELECT COUNT(*) as n FROM products")->fetch_assoc()['n'];
$total_users = $conn->query("SELECT COUNT(*) as n FROM users WHERE role='user'")->fetch_assoc()['n'];
$total_orders = $conn->query("SELECT COUNT(*) as n FROM payments")->fetch_assoc()['n'];
$total_revenue = $conn->query("SELECT SUM(total) as n FROM payments WHERE status='completed'")->fetch_assoc()['n'] ?? 0;
?>

<h1>Dashboard</h1>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Produits</h3>
        <div class="number"><?php echo $total_products; ?></div>
    </div>
    <div class="stat-card">
        <h3>Utilisateurs</h3>
        <div class="number"><?php echo $total_users; ?></div>
    </div>
    <div class="stat-card">
        <h3>Commandes</h3>
        <div class="number"><?php echo $total_orders; ?></div>
    </div>
    <div class="stat-card">
        <h3>Revenus</h3>
        <div class="number"><?php echo number_format($total_revenue, 0, ',', '.'); ?> F</div>
    </div>
</div>

<!-- Dernières commandes -->
<h2 style="margin-bottom:15px;">Dernières commandes</h2>
<table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $orders = $conn->query("SELECT p.*, u.name FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 10");
        while ($order = $orders->fetch_assoc()):
        ?>
        <tr>
            <td>#<?php echo $order['id']; ?></td>
            <td><?php echo $order['name']; ?></td>
            <td><?php echo number_format($order['total'], 0, ',', '.'); ?> FCFA</td>
            <td>
                <span style="padding:3px 8px;border-radius:3px;font-size:12px;
                    background:<?php echo $order['status']=='completed' ? '#d4edda' : '#fff3cd'; ?>;
                    color:<?php echo $order['status']=='completed' ? '#155724' : '#856404'; ?>;">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </td>
            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
