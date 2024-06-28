<?php

require 'db_connect.php';
require 'navbar.php';

if (isset($_SESSION['phoneNumber'])) {
    $userLoginId = $_SESSION['phoneNumber'];
    $days = isset($_GET['days']) ? intval($_GET['days']) : 1; // Default to 1 day if not set

    // Modified query to include the total product count
    $query = "SELECT oi.*, oh.ORDER_DATE, oh.GRAND_TOTAL, p.NAME, p.IMAGE, (oi.QUANTITY * oi.UNIT_PRICE) AS TOTAL_PRICE, 
                     SUM(oi.QUANTITY) OVER() AS TOTAL_PRODUCT_COUNT
              FROM order_item oi 
              JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
              JOIN order_header oh ON oi.ORDER_ID = oh.ORDER_ID
              WHERE oi.CHANGE_BY_USER_LOGIN_ID = '$userLoginId'
              AND oh.ORDER_DATE >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
              ORDER BY oi.ORDER_ID DESC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $order_history = array();
        $total_product_count = 0;

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $order_history[] = $row;
                $total_product_count = $row['TOTAL_PRODUCT_COUNT']; // Getting total product count from the query result
            }
        } else {
            echo '<p>No orders found.</p>';
        }
    } else {
        // Execution error
        echo '<p>Error executing query: ' . mysqli_error($conn) . '</p>';
    }
} else {
    // Session is not set
    echo '<p>User phone number not found in session.</p>';
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
    <title>Order History</title>
</head>

<body>
    <div class="border-top border-bottom" style="margin-top: 8%; margin-bottom: 2%;">
        <h4 class="text-center p-2">Order History</h4>
    </div>
    <!-- Order History Section -->
    <div class="container ">
        <form method="get" class="mb-4 text-right">
            <select name="days" id="days" class="custom-select w-auto" onchange="this.form.submit()">
                <option>Back days history</option>
                <option value="0">1 day</option>
                <option value="7">1 week</option>
                <option value="30">1 month</option>
                <option value="365">1 year</option>
            </select>
        </form>

        <?php
        if (!empty($order_history)) {
            echo '<div class="text-right mb-4"><strong>Total Products Ordered: ' . $total_product_count . '</strong></div>';
            foreach ($order_history as $order) {
                echo '<div class="border p-3 mb-3 rounded">';
                echo '<div class="d-flex justify-content-between mb-3">';
                echo '<div class="small">ORDER DATE: ' . $order['ORDER_DATE'] . '</div>';
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
