<?php
require 'db_connect.php';

if (isset($_POST['add_to_cart']) && $_POST['add_to_cart'] == true) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $image = $_POST['image'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $color = $_POST['color'];

    // Process adding the item to the cart here
    // For example, you might insert the item into a cart database table

    // Simulate a successful addition to the cart
    $response = array(
        'status' => 'success',
        'message' => 'Added to cart: ' . $name . ' (' . $size . ')',
        'product_id' => $product_id
    );

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request.'
    );

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
