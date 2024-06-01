<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart'])) {
    // Retrieve the JSON string from the POST request
    $cartJson = $_POST['cart'];

    // Decode the JSON string to ensure it's valid
    $cartArray = json_decode($cartJson, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Store the JSON string in the PHP session
        $_SESSION['cart'] = $cartArray;

        echo "Cart updated successfully";
    } else {
        echo "Invalid JSON data";
    }
} else {
    echo "No cart data received";
}
?>
