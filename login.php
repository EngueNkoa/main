<?php require_once 'includes/header.php'; ?>

<?php
if (isLoggedIn()) redirect('/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            redirect($user['role'] === 'admin' ? '/admin/index.php' : '/index.php');
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Email introuvable.";
    }
}
?>

<div class="form-container">
    <h2>Connexion</h2>
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="votre@email.com">
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Se connecter</button>
    </form>
    <p style="text-align:center;margin-top:15px;font-size:14px;">
        Pas de compte ? <a href="register.php" style="color:#e74c3c;">S'inscrire</a>
    </p>
    <p style="text-align:center;margin-top:10px;font-size:12px;color:#999;">
        Admin: admin@shop.com / password
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>
