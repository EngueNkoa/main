<?php require_once 'includes/header.php'; ?>

<?php
if (isLoggedIn()) redirect('/index.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed')");
            $success = "Compte créé avec succès ! Vous pouvez vous connecter.";
        }
    }
}
?>

<div class="form-container">
    <h2>Créer un compte</h2>
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?> <a href="login.php">Connexion</a></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="name" required placeholder="Jean Dupont">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="votre@email.com">
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">S'inscrire</button>
    </form>
    <p style="text-align:center;margin-top:15px;font-size:14px;">
        Déjà un compte ? <a href="login.php" style="color:#e74c3c;">Se connecter</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>
