<?php
session_start();

if (isset($_POST['cartJson'])) {
    // Decode the JSON data received from AJAX request
    $cartData = json_decode($_POST['cartJson'], true);

    // Store cartJson in PHP session
    $_SESSION['cartJson'] = $cartData;

    // Respond with a success message (optional)
    echo 'cartJson stored in session.';
} else {
    echo 'Error: cartJson data not received.';
}
?>
