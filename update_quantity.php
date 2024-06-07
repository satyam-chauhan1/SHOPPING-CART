<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['index']) && isset($_POST['action'])) {
        $index = $_POST['index'];
        $action = $_POST['action'];

        if (isset($_SESSION['cart'][$index])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$index]['quantity'] += 1;
                // $_SESSION['cart'][$index]['price'] += $_SESSION['cart'][$index]['price'] / ($_SESSION['cart'][$index]['quantity'] - 1);
            } elseif ($action === 'decrease' && $_SESSION['cart'][$index]['quantity'] > 1) {
                $_SESSION['cart'][$index]['quantity'] -= 1;
                // $_SESSION['cart'][$index]['price'] -= $_SESSION['cart'][$index]['price'] / ($_SESSION['cart'][$index]['quantity'] + 1);
            }
            echo "Quantity updated successfully";
        } else {
            echo "Item not found in cart";
        }
    } else {
        echo "Invalid data received";
    }
} else {
    echo "Invalid request method";
}
?>
