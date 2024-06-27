<?php
session_start();

require 'db_connect.php';
require 'navbar.php';

if (isset($_SESSION['phoneNumber'])) {
    $userLoginId = $_SESSION['phoneNumber'];
    $query = "SELECT oi.*, oh.ORDER_DATE, oh.GRAND_TOTAL, p.NAME, p.IMAGE, (oi.QUANTITY * oi.UNIT_PRICE) AS TOTAL_PRICE
              FROM order_item oi 
              JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
              JOIN order_header oh ON oi.ORDER_ID = oh.ORDER_ID
              WHERE oi.CHANGE_BY_USER_LOGIN_ID = '$userLoginId' 
              ORDER BY oi.ORDER_ID DESC
              LIMIT 6";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $order_history = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $order_history[] = $row;
            }
        } else {
            echo '<p>No orders found.</p>';
        }
    } else {
        // execution error
        echo '<p>Error executing query: ' . mysqli_error($conn) . '</p>';
    }
} else {
    // session is not set
    // echo '<p>User phone number not found in session.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>order history</title>

</head>

<body>

    <div class="border-top border-bottom" style="margin-top: 8%; margin-bottom: 2%;">
        <h4 class="text-center p-2">Order History</h4>
    </div>
    <!-- Order History Section -->
    <div class="container mt-5">
        <?php
        if (!empty($order_history)) {
            foreach ($order_history as $order) {
                echo '<div class="border p-3 mb-3 rounded">';
                echo '<div class="d-flex justify-content-between mb-3">';
                echo '<div class="small">ORDER PLACED: ' . $order['ORDER_DATE'] . '</div>';
                echo '<div>Order # ' . $order['ORDER_ID'] . '</div>';
                echo '</div>';
                echo '<div class="d-flex align-items-center">';
                echo '<img src="' . $order['IMAGE'] . '" alt="Product Image" class="img-thumbnail mr-3" style="width: 125px;">';
                echo '<div>';
                echo '<div><strong>' . $order['NAME'] . '</strong></div>';
                echo '<div>Quantity: ' . $order['QUANTITY'] . '</div>';
                echo '<div>Unit Price: &#8377;' . $order['UNIT_PRICE'] . '</div>';
                echo '<div>Total Price: &#8377;' . $order['TOTAL_PRICE'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="mt-3">';
                echo '<a href="show_products.php?product_id=' . $order['PRODUCT_ID'] . '" class="btn btn-warning btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i> Buy again</a>';
                echo '<a href="bill.php" class="btn btn-warning btn-sm ml-3"> Get invoice</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<h4>No orders found.</h4>';
        }
        ?>
    </div>
</body>

</html>