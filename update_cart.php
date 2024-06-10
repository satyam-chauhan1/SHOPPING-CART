<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cart'])) {
        // Retrieve the JSON string from the POST request
        $cartJson = $_POST['cart'];

        // Decode the JSON string to ensure it's valid
        $cartJson = json_decode($cartJson, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            // Check if cart session exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $itemFound = false;
            // Process each item in the new cart array
                foreach ($_SESSION['cart'] as &$existingItem) {
                    if (
                        $existingItem['product_id'] === $cartJson['product_id'] &&
                        $existingItem['size'] === $cartJson['size'] &&
                        $existingItem['color'] === $cartJson['color']

                    ) {
                        // Item already in cart, increment quantity and update price
                        $existingItem['quantity'] = $existingItem['quantity']+ 1;
                        $itemFound = true;
                        break;
                    }
                    }
            if (!$itemFound) {
                $cartJson['quantity'] = 1;
                $_SESSION['cart'][] = $cartJson;
            }


            // echo "\nCart updated successfully";
        } else {
            echo "Invalid JSON data";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['index'])) {
        $index = $_POST['index'];

        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            // Reindex array to maintain proper indices
            $_SESSION['cart'] = array_values($_SESSION['cart']);

            echo "Item removed successfully";
        } else {
            echo "Item not found in cart";
        }
    } else {
        echo "No cart data received";
    }
} else {
    echo "Invalid request method";
}
?>