<?php
session_start();
require 'db_connect.php';
require 'generateUniqueId.php';
$userLoginId = $_SESSION['phoneNumber'];
$cartJson = $_SESSION['cartJson'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'confirm_purchase') {
        $totalPrice = $_POST['totalPrice'];
        $paymentMethod = $_POST['paymentMethod'];
        // Order details
        $orderDate = date('Y-m-d H:i:s');
        $statusId = 'ORDER COMPLETED';
        $currency = 'INR';
        $createdBy = $userLoginId;
        // Custom function to generate order ID
        $orderID = generateUniqueId($conn, "ORD_ID", "order_header", "ORDER_ID");

        if ($orderID) {
            // Sanitize the order ID to prevent SQL injection
            $orderID = $conn->real_escape_string($orderID);

            // Construct the SQL query for order_header
            $order_header_sql = "INSERT INTO order_header (ORDER_ID, ORDER_DATE, STATUS_ID, CREATED_BY, PAYMENT_METHOD, CURRENCY, REMAINING_SUB_TOTAL, GRAND_TOTAL) 
                  VALUES ('$orderID', '$orderDate', '$statusId', '$createdBy', '$paymentMethod', '$currency', '$totalPrice', '$totalPrice')";
            // Execute the query
            if ($conn->query($order_header_sql) === TRUE) {
                // echo "Order ID $orderID inserted successfully.";

                // Generate Order Adjustment ID
                $orderAdjustmentId = generateUniqueId($conn, "ORD_AD", "order_adjustment", "ORDER_ADJUSTMENT_ID");

                if ($orderAdjustmentId) {
                    // Sanitize the order adjustment ID to prevent SQL injection
                    $orderAdjustmentId = $conn->real_escape_string($orderAdjustmentId);

                    $discountAmount = $_POST['discountAmount'];
                    $discountPercentage = $_POST['discountPercentage'];
                    $finalPrice = $_POST['finalPrice'];

                    // Construct the SQL query for order_adjustment
                    $order_adjustment_sql = "INSERT INTO order_adjustment (ORDER_ADJUSTMENT_ID, ORDER_ID, AMOUNT, DESCRIPTION) 
                                             VALUES ('$orderAdjustmentId', '$orderID','$discountAmount', 'Discount of $discountPercentage% on ₹$totalPrice is ₹$discountAmount')";

                    // Execute the query
                    if ($conn->query($order_adjustment_sql) === TRUE) {
                        // echo "Order Adjustment ID $orderAdjustmentId inserted successfully.";

                        // Insert each item in the cart into the order_item table
                        $items = $cartJson['items'];
                        foreach ($items as $item) {
                            $productId = $conn->real_escape_string($item['product_id']);

                            $quantity = $conn->real_escape_string($item['quantity']);
                            $price = $conn->real_escape_string($item['price']);
                            $seqId = generateUniqueId($conn, "SEQ_ID", "order_item", "SEQUENCE_ID"); // Generate sequence ID


                            $order_item_sql = "INSERT INTO order_item (PRODUCT_ID,SEQUENCE_ID,QUANTITY,UNIT_PRICE,IS_PROMO,CHANGE_BY_USER_LOGIN_ID) 
                                               VALUES ('$productId','$seqId','$quantity','$price','Y','$userLoginId')";

                            if ($conn->query($order_item_sql) === TRUE) {
                                echo "Order Item $productId inserted successfully with SEQ_ID $seqId.";
                            } else {
                                echo "Error inserting order item $productId: " . $conn->error;
                            }
                        }

                        // Clear the cart
                        unset($_SESSION['cart']);
                    } else {
                        // echo "Error inserting order adjustment ID: " . $conn->error;
                    }
                } else {
                    // echo "Error generating order adjustment ID.";
                }
            } else {
                // echo "Error inserting order ID: " . $conn->error;
            }
        } else {
            echo "Error generating order ID.";
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
