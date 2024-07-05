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

    // Check if the product exists in the product table
    $query = "SELECT * FROM product WHERE PRODUCT_ID = '$productId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $cartItem = array(
            'main_product_id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'image' => $productImage,
            'size' => $productSize,
            'color' => $productColor,
            'quantity' => 1
        );

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        $productFound = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['main_product_id'] == $productId && $item['size'] == $productSize && $item['color'] == $productColor) {
                $item['quantity'] += 1;
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            $_SESSION['cart'][] = $cartItem;
        }

        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Product not found.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>
