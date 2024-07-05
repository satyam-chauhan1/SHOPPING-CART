<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['main_product_id'];
    $productName = $_POST['name'];
    $productPrice = $_POST['price'];
    $productImage = $_POST['image'];
    $productSize = $_POST['size'];
    $productColor = $_POST['color'];
    $quantity = $_POST['quantity'];

    // Assuming you have a cart session variable
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['main_product_id'] == $productId && $item['size'] == $productSize && $item['color'] == $productColor) {
            $item['quantity'] += $quantity;
            $productExists = true;
            break;
        }
    }

    if (!$productExists) {
        $_SESSION['cart'][] = [
            'main_product_id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'image' => $productImage,
            'size' => $productSize,
            'color' => $productColor,
            'quantity' => $quantity
        ];
    }

    echo 'Product added to cart successfully';
}
?>
