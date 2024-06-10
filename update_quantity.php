<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['index']) && isset($_POST['action'])) {
        $index = $_POST['index'];
        $action = $_POST['action'];
        $cartItem = $_SESSION['cart'][$index];
        if ($cartItem != null) {
            if ($action === 'increase') {
                $cartItem['quantity'] += 1;
                // $_SESSION['cart'][$index]['price'] += $_SESSION['cart'][$index]['price'] / ($_SESSION['cart'][$index]['quantity'] - 1);
            } elseif ($action === 'decrease' && $cartItem['quantity'] > 1) {
                $cartItem['quantity'] -= 1;
                // $_SESSION['cart'][$index]['price'] -= $_SESSION['cart'][$index]['price'] / ($_SESSION['cart'][$index]['quantity'] + 1);
            }
            echo "Quantity updated successfully";
        } else {
            echo "Item not found in cart";
        }
        $_SESSION['cart'][$index] = $cartItem;
    } else {
        echo "Invalid data received";
    }
} else {
    echo "Invalid request method";
}
?>
