<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Bootstrap and jQuery scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>show product</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color:#6c757d;">
        <a class="navbar-brand p-3" href="index.php">
            <h3 class="text-white">EasyShop</h3>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link text-white" href="show_products.php?for=">All PRODUCTS</a>

                    <a class="text-decoration-none text-white ml-2 " href="order_history.php">MY ORDERS</a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link text-white" href="product_categories.php?id=MN">MEN</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="product_categories.php?id=W">WOMEN</a>
                </li>

                <!-- Search bar  -->
                <li class="nav-item ml-5">
                    <form action="show_products.php" method="GET" class="input-group" style="width: 150%;">
                        <input type="text" name="for" class="form-control" placeholder="Search products...">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text bg-warning text-decoration-none"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </li>

            </ul>


            <!-- Login/Logout -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <?php
                    $loggedin = isset($_SESSION['loggedin']) ? $_SESSION['loggedin'] : 0;
                    if (!$loggedin) {
                        echo '<a class="nav-link text-white" href="login_page.php"><i class="fa fa-user"></i> Login</a>';
                    } else {
                        $firstName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : 'User';
                        echo '<a class="nav-link text-white bg-success rounded mr-2" href="logout.php"><i class="fa fa-user"></i> ' . htmlspecialchars($firstName) . ' || <i class="fa fa-sign-out"></i> Logout</a>';
                    }
                    ?>
                </li>
            </ul>


            <!-- Add to cart  -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-light" href="cart.php">
                        <i class="fa fa-cart-plus"></i> Cart
                        <span id="cart-count" class="badge badge-pill badge-light">0 </span>
                        <!-- <?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?> -->


                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="mt-5"></div>

</body>

</html>