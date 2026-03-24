<?php require_once 'header.php'; ?>

<?php
$message = '';

// Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id=$id AND role='user'");
        $message = "Utilisateur supprimé.";
    } else {
        $message = "Vous ne pouvez pas supprimer votre propre compte.";
    }
}

$users = $conn->query("SELECT u.*, COUNT(DISTINCT p.id) as orders, COALESCE(SUM(p.total),0) as spent 
                        FROM users u 
                        LEFT JOIN payments p ON u.id=p.user_id 
                        GROUP BY u.id 
                        ORDER BY u.created_at DESC");
?>

<h1>Gestion des utilisateurs</h1>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Commandes</th>
            <th>Total dépensé</th>
            <th>Inscrit le</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td>
                <span style="padding:3px 8px;border-radius:3px;font-size:12px;
                    background:<?php echo $user['role']=='admin' ? '#222' : '#e8f5e9'; ?>;
                    color:<?php echo $user['role']=='admin' ? '#fff' : '#2e7d32'; ?>;">
                    <?php echo ucfirst($user['role']); ?>
                </span>
            </td>
            <td><?php echo $user['orders']; ?></td>
            <td><?php echo number_format($user['spent'], 0, ',', '.'); ?> FCFA</td>
            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
            <td>
                <?php if ($user['role'] !== 'admin'): ?>
                    <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer cet utilisateur ?')">✕ Supprimer</a>
                <?php else: ?>
                    <span style="color:#999;font-size:12px;">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>
