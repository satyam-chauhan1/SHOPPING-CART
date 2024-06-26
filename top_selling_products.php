<?php

require 'db_connect.php';

$query = "SELECT oi.PRODUCT_ID, p.NAME, p.IMAGE, SUM(oi.QUANTITY) as total_quantity
          FROM order_item oi
          INNER JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
          GROUP BY oi.PRODUCT_ID
          ORDER BY total_quantity DESC
          LIMIT 6"; // Adjust LIMIT as per your requirement

$result = mysqli_query($conn, $query);


if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $topSellingProducts = array();

    // Store top selling products in session
    while ($row = mysqli_fetch_assoc($result)) {
        $topSellingProducts[] = $row;
    }
    
    // Store top selling products array in session variable
    $_SESSION['topSellingProducts'] = $topSellingProducts;
} else {
    echo '<p class="text-center">No top selling products found.</p>';
}

mysqli_free_result($result);

// Close database connection
mysqli_close($conn);
?>
