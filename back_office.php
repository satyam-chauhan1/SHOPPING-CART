<?php
session_start();
require 'db_connect.php';

// Fetch all users for the dropdown
$user_query = "SELECT PHONE, FIRST_NAME FROM register_form";
$user_result = mysqli_query($conn, $user_query);

if (isset($_GET['userPhoneNumber'])) {
    $userPhoneNumber = $_GET['userPhoneNumber'];
    $_SESSION['phoneNumber'] = $userPhoneNumber;

    // Fetch the selected user's first name
    $selected_user_query = "SELECT FIRST_NAME FROM register_form WHERE PHONE = '$userPhoneNumber'";
    $selected_user_result = mysqli_query($conn, $selected_user_query);
    if ($selected_user_result && mysqli_num_rows($selected_user_result) > 0) {
        $selected_user_row = mysqli_fetch_assoc($selected_user_result);
        $_SESSION['firstName'] = $selected_user_row['FIRST_NAME'];
    }
} elseif (isset($_SESSION['phoneNumber'])) {
    $userPhoneNumber = $_SESSION['phoneNumber'];
} else {
    $userPhoneNumber = '';
}

// Fetch order history based on filters
$days = isset($_GET['days']) ? $_GET['days'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

$order_clause = 'ORDER BY oh.ORDER_DATE DESC';
if ($sort === 'asc') {
    $order_clause = 'ORDER BY oh.ORDER_DATE ASC';
}

// Construct the query
if ($days === 'all') {
    $query = "SELECT oi.*, oh.ORDER_DATE, oh.GRAND_TOTAL, oh.CREATED_BY, p.NAME, p.PRODUCT_CATEGORY_ID, p.IMAGE, 
                     (oi.QUANTITY * oi.UNIT_PRICE) AS TOTAL_PRICE, 
                     SUM(oi.QUANTITY) OVER() AS TOTAL_PRODUCT_COUNT
              FROM order_item oi 
              JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
              JOIN order_header oh ON oi.ORDER_ID = oh.ORDER_ID
              WHERE oi.CHANGE_BY_USER_LOGIN_ID = '$userPhoneNumber'
              $order_clause";
} else {
    $days = intval($days); // Convert days to integer

    $query = "SELECT oi.*, oh.ORDER_DATE, oh.GRAND_TOTAL, oh.CREATED_BY, p.NAME, p.PRODUCT_CATEGORY_ID, p.IMAGE, 
                     (oi.QUANTITY * oi.UNIT_PRICE) AS TOTAL_PRICE, 
                     SUM(oi.QUANTITY) OVER() AS TOTAL_PRODUCT_COUNT
              FROM order_item oi 
              JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
              JOIN order_header oh ON oi.ORDER_ID = oh.ORDER_ID
              WHERE oi.CHANGE_BY_USER_LOGIN_ID = '$userPhoneNumber'
              AND oh.ORDER_DATE >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
              $order_clause";
}

$result = mysqli_query($conn, $query);

// Check query execution and process results
if ($result) {
    $order_history = array();
    $total_product_count = 0;

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $order_history[] = $row;
            $total_product_count = $row['TOTAL_PRODUCT_COUNT']; // Total product count 
        }
    } else {
        $order_history = array(); // No orders found
    }
} else {
    echo '<p>Error executing query: ' . mysqli_error($conn) . '</p>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Back Office - Order History and Product Management</title>
</head>

<body>
    <div class="container mt-5">
        <!-- Order History Section -->
        <div class="border-bottom mb-4">
            <h2 class="text-center">Order History</h2>
        </div>

        <!-- select user  -->
        <form method="get">
            <div class="form-group mb-2">
                <label for="userPhoneNumber" class="font-weight-bold">Select User:</label>
                <select name="userPhoneNumber" id="userPhoneNumber" class="custom-select w-auto mr-2" onchange="this.form.submit();">
                    <option value="">Select User</option>
                    <?php
                    if ($user_result && mysqli_num_rows($user_result) > 0) {
                        while ($user_row = mysqli_fetch_assoc($user_result)) {
                            $selected = $userPhoneNumber == $user_row['PHONE'] ? 'selected' : '';
                            echo "<option value='{$user_row['PHONE']}' $selected>{$user_row['FIRST_NAME']} ({$user_row['PHONE']})</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </form>

        <!-- welcome message  -->
        <div>
            <?php if (isset($_SESSION['firstName'])) : ?>
                <strong class="font-italic"> Welcome -> <?php echo $_SESSION['firstName']; ?> (<?php echo $userPhoneNumber; ?>)</strong>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <form method="get" class="mb-4 text-right">
                    <select name="days" id="days" class="custom-select w-auto mr-2">
                        <option value="all">Back days history</option>
                        <option value="1" <?php if ($days == 1) echo 'selected'; ?>>1 day</option>
                        <option value="7" <?php if ($days == 7) echo 'selected'; ?>>1 week</option>
                        <option value="30" <?php if ($days == 30) echo 'selected'; ?>>1 month</option>
                        <option value="365" <?php if ($days == 365) echo 'selected'; ?>>1 year</option>
                        <option value="all" <?php if ($days == 'all') echo 'selected'; ?>>All times</option>
                    </select>
                    <select name="sort" id="sort" class="custom-select w-auto">
                        <option value="desc" <?php if ($sort === 'desc') echo 'selected'; ?>>Date Descending</option>
                        <option value="asc" <?php if ($sort === 'asc') echo 'selected'; ?>>Date Ascending</option>
                    </select>
                    <button type="submit" class="btn btn-warning ml-2">Apply Filters</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($order_history)) : ?>
                    <p>Total Products Ordered: <?php echo $total_product_count; ?></p>
                    <?php foreach ($order_history as $order) : ?>
                        <div class="card mb-3 row">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h6 class="card-title">Order Date: <?php echo $order['ORDER_DATE']; ?></h6>
                                        <img src="<?php echo $order['IMAGE']; ?>" alt="Product Image" class="img-thumbnail mr-3" style="width: 125px;">
                                        <p class="card-text"><strong>Product name: </strong><span class="font-italic"><?php echo $order['NAME']; ?></span></p>
                                        <div class="d-flex">
                                            <p class="card-text mr-3"><strong>Size: </strong><?php echo $order['SIZE']; ?></p>
                                            <p class="card-text"><strong>Quantity: </strong><?php echo $order['QUANTITY']; ?></p>
                                        </div>
                                        
                                        <p class="card-text"><strong>Per Unit Price: </strong><?php echo $order['UNIT_PRICE']; ?></p>
                                        <p class="card-text"><strong>Total Price: </strong><?php echo $order['TOTAL_PRICE']; ?></p>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="card-body">
                                        <p class="card-title "><strong>Order ID: #</strong><span class="font-italic"><?php echo $order['ORDER_ID']; ?></span></p>
                                        <p class="card-title"><strong>Product ID: #</strong><span class="font-italic"><?php echo $order['PRODUCT_ID']; ?></span></p>
                                        <p class="card-title "><strong>Product Category ID: #</strong><span class="font-italic"><?php echo $order['PRODUCT_CATEGORY_ID']; ?></span></p>
                                        <p class="card-title "><strong>Use login ID: #</strong><span class="font-italic"><?php echo $order['CREATED_BY']; ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>

<?php
// Close database connection
mysqli_close($conn);
?>