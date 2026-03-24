<?php require_once 'header.php'; ?>

<?php
// Changer le statut
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = sanitize($_GET['status']);
    $allowed = ['pending', 'completed', 'cancelled'];
    if (in_array($status, $allowed)) {
        $conn->query("UPDATE payments SET status='$status' WHERE id=$id");
    }
}

$orders = $conn->query("SELECT p.*, u.name as client, u.email FROM payments p JOIN users u ON p.user_id=u.id ORDER BY p.created_at DESC");
?>

<h1>Gestion des commandes</h1>

<table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Email</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
            <td>#<?php echo $order['id']; ?></td>
            <td><?php echo $order['client']; ?></td>
            <td><?php echo $order['email']; ?></td>
            <td><?php echo number_format($order['total'], 0, ',', '.'); ?> FCFA</td>
            <td>
                <?php
                $colors = [
                    'completed' => ['bg' => '#d4edda', 'text' => '#155724'],
                    'pending'   => ['bg' => '#fff3cd', 'text' => '#856404'],
                    'cancelled' => ['bg' => '#f8d7da', 'text' => '#721c24'],
                ];
                $c = $colors[$order['status']];
                ?>
                <span style="padding:3px 8px;border-radius:3px;font-size:12px;background:<?php echo $c['bg']; ?>;color:<?php echo $c['text']; ?>;">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </td>
            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
            <td>
                <a href="orders.php?id=<?php echo $order['id']; ?>&status=completed" class="btn btn-success btn-sm">✓</a>
                <a href="orders.php?id=<?php echo $order['id']; ?>&status=cancelled" class="btn btn-danger btn-sm" style="margin-left:4px;">✕</a>
                <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary btn-sm" style="margin-left:4px;">👁</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
