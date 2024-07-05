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
        date_default_timezone_set('Asia/Kolkata');
        $orderDateTime = date('Y-m-d H:i:s');
        $orderDate = date('Y-m-d');
        $statusId = 'ORDER COMPLETED';
        $currency = 'INR';
        $createdBy = $userLoginId;
        // Custom function to generate order ID
        $orderID = generateUniqueId($conn, "ORD_ID", "order_header", "ORDER_ID");

        if ($orderID) {
            // Sanitize the order ID to prevent SQL injection
            $orderID = $conn->real_escape_string($orderID);

            // Construct the SQL query for order_header
            $order_header_sql = "INSERT INTO order_header (ORDER_ID, ORDER_DATE, STATUS_ID, CREATED_BY, DATE, PAYMENT_METHOD, CURRENCY, REMAINING_SUB_TOTAL, GRAND_TOTAL) 
                  VALUES ('$orderID', '$orderDateTime', '$statusId', '$createdBy', '$orderDate', '$paymentMethod', '$currency', '$totalPrice', '$totalPrice')";
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

                    // Construct the SQL query for order_adjustment
                    $order_adjustment_sql = "INSERT INTO order_adjustment (ORDER_ADJUSTMENT_ID, ORDER_ID, AMOUNT, DESCRIPTION) 
                                             VALUES ('$orderAdjustmentId', '$orderID', '$discountAmount', 'Discount of $discountPercentage% on ₹$totalPrice is ₹$discountAmount')";

                    // Execute the query
                    if ($conn->query($order_adjustment_sql) === TRUE) {

                        // Insert each item in the cart into the order_item table
                        $items = $cartJson['items'];

                        // Define the starting sequence ID
                        $seqId = 1;
                        foreach ($items as $item) {
                            $mainProductId = isset($item['main_product_id']) ? $conn->real_escape_string($item['main_product_id']) : null;
                            $relatedProductId = isset($item['related_product_id']) ? $conn->real_escape_string($item['related_product_id']) : null;
                            $productId = !empty($mainProductId) ? $mainProductId : $relatedProductId;

                            $quantity = $conn->real_escape_string($item['quantity']);
                            $price = $conn->real_escape_string($item['price']);
                            $size = isset($item['size']) ? $conn->real_escape_string($item['size']) : 'N/A';
                            $color = isset($item['color']) ? $conn->real_escape_string($item['color']) : 'N/A';
                        

                            $sequenceId = "SEQ_ID00" . $seqId;

                            $order_item_sql = "INSERT INTO order_item (ORDER_ID, PRODUCT_ID, SEQUENCE_ID, QUANTITY, UNIT_PRICE, IS_PROMO, CHANGE_BY_USER_LOGIN_ID, ITEM_ORDER_DATETIME,SIZE,COLOUR) 
                                               VALUES ('$orderID', '$productId', '$sequenceId', '$quantity', '$price', 'Y', '$userLoginId', '$orderDateTime', '$size','$color')";

                            if ($conn->query($order_item_sql) === TRUE) {
                                echo "Order Item $orderID inserted successfully with SEQ_ID $sequenceId.";
                            } else {
                                echo "Error inserting order item $orderID: " . $conn->error;
                            }
                            // Increment seqId for the next item
                            $seqId++;
                        }

                        // Clear the cart
                        unset($_SESSION['cart']);
                        unset($_SESSION['cartJson']);
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
?>
