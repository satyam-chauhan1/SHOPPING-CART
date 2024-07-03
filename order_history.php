<?php
require 'db_connect.php';
require 'navbar.php';

if (isset($_SESSION['phoneNumber'])) {
    $userLoginId = $_SESSION['phoneNumber'];
    $days = isset($_GET['days']) ? $_GET['days'] : 'all';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

    $order_clause = 'ORDER BY oh.ORDER_DATE DESC'; 

    if ($sort === 'asc') {
        $order_clause = 'ORDER BY oh.ORDER_DATE ASC';
    }

    // Construct the query
    if ($days === 'all') {
        $query = "SELECT oi.*, oh.ORDER_DATE, oh.GRAND_TOTAL, p.NAME, p.IMAGE, (oi.QUANTITY * oi.UNIT_PRICE) AS TOTAL_PRICE, 
                         SUM(oi.QUANTITY) OVER() AS TOTAL_PRODUCT_COUNT
                  FROM order_item oi 
                  JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
                  JOIN order_header oh ON oi.ORDER_ID = oh.ORDER_ID
                  WHERE oi.CHANGE_BY_USER_LOGIN_ID = '$userLoginId'
                  $order_clause";
    } else {
        $days = intval($days); //  $days is an integer

        $query = "SELECT oi.*, oh.ORDER_DATE, oh.GRAND_TOTAL, p.NAME, p.IMAGE, (oi.QUANTITY * oi.UNIT_PRICE) AS TOTAL_PRICE, 
                         SUM(oi.QUANTITY) OVER() AS TOTAL_PRODUCT_COUNT
                  FROM order_item oi 
                  JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
                  JOIN order_header oh ON oi.ORDER_ID = oh.ORDER_ID
                  WHERE oi.CHANGE_BY_USER_LOGIN_ID = '$userLoginId'
                  AND oh.ORDER_DATE >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
                  $order_clause";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        $order_history = array();
        $total_product_count = 0;

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $order_history[] = $row;
                $total_product_count = $row['TOTAL_PRODUCT_COUNT']; //total product count 
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
    <div class="container">
        <form method="get" class="mb-4 text-right">
            <select name="days" id="days" class="custom-select w-auto mr-2" onchange="this.form.submit()">
                <option>Back days history</option>
                <option value="0">1 day</option>
                <option value="7">1 week</option>
                <option value="30">1 month</option>
                <option value="365">1 year</option>
                <option value="all">All times</option>
            </select>
            <select name="sort" id="sort" class="custom-select w-auto" onchange="this.form.submit()">
                <option value="desc" <?php if ($sort === 'desc') echo 'selected'; ?>>Date Descending</option>
                <option value="asc" <?php if ($sort === 'asc') echo 'selected'; ?>>Date Ascending</option>
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
                echo '<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#productModal' . $order['PRODUCT_ID'] . '"><i class="fa fa-refresh" aria-hidden="true"></i> Buy again</button>';
                echo '</div>';
                echo '</div>';

                // Modal for each product
                echo '<div class="modal fade" id="productModal' . $order['PRODUCT_ID'] . '" tabindex="-1" role="dialog" aria-labelledby="productModalLabel' . $order['PRODUCT_ID'] . '" aria-hidden="true">';
                echo '<div class="modal-dialog modal-dialog-centered " role="document">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<div class="text-center">';
                echo '<img src="' . $order['IMAGE'] . '" alt="Product Image" class="img-fluid mb-3 mx-auto d-block" style="width:250px; height: auto;">';
                echo '</div>';
                echo '<h6 class="modal-title text-center" id="productModalLabel' . $order['PRODUCT_ID'] . '">' . $order['NAME'] . '</h6>';
                // echo '<p><strong>Quantity:</strong> ' . $order['QUANTITY'] . '</p>';/
                echo '<p><strong>Price:</strong> &#8377;' . $order['UNIT_PRICE'] . '</p>';
                // echo '<p><strong>Total Price:</strong> &#8377;' . $order['TOTAL_PRICE'] . '</p>';
                echo '</div>';
                echo '<div class="modal-footer">';
                echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                echo '<button type="button" class="btn btn-primary">Add to Cart</button>';
                echo '</div>';
                echo '</div>';
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