<?php
require_once 'includes/config.php';

if (!isLoggedIn()) redirect('/login.php');

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Vérifier si le produit existe et est en stock
    $product = $conn->query("SELECT * FROM products WHERE id = $product_id AND stock > 0")->fetch_assoc();
    
    if ($product) {
        // Vérifier si déjà dans le panier
        $existing = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id")->fetch_assoc();
        
        if ($existing) {
            $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
        } else {
            $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
        }
        $_SESSION['message'] = "Produit ajouté au panier !";
        $_SESSION['message_type'] = "success";
    }
}

redirect('/cart.php');
?>
