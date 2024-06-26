<!-- for margin -->
<div class="border-top border-bottom" style="margin-top: 3%; margin-bottom: 2%;"></div>

<?php
require 'db_connect.php';
?>

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
    <title>shopping_cart</title>

</head>

<body class="bg-light">

    <!-- carousel  -->
    <div id="carouselExampleIndicators" class="carousel slide bg-dark" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        </ol>
        <div class="carousel-inner">
            <div class="row ">
                <div class="carousel-item active">
                    <img class="d-block w-75 mx-auto d-block " src="images\CAROUSEL_IMAGES\CAROUSEL IMAGE.jpg" alt="First slide">
                </div>

                <div class="carousel-item">
                    <img class="d-block w-75 mx-auto d-block" src="images\CAROUSEL_IMAGES\MAN FORMAL SHOES.jpg" alt="Second slide">
                </div>

                <div class="carousel-item">
                    <img class="d-block w-75 mx-auto d-block" src="images\CAROUSEL_IMAGES\MEN BOTS.jpg" alt="Third slide">
                </div>

                <div class="carousel-item">
                    <img class="d-block w-75 mx-auto d-block" src="images\CAROUSEL_IMAGES\MEN FASHION-LEATHER-SANDALS.jpg" alt="Third slide">
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Top selling products  -->
    <div class="col p-5">
        <h2 class="text-center border-top border-bottom p-2">OUR TOP SELLING PRODUCTS</h2>
    </div>

    <?php
    require 'navbar.php'; 
    require 'top_selling_products.php';

    // Display top selling products from session
    if (isset($_SESSION['topSellingProducts']) && !empty($_SESSION['topSellingProducts'])) {
        $topSellingProducts = $_SESSION['topSellingProducts'];
    ?>
        <div class="col-md-12">
            <div class="row">
                <?php
                // Loop through top selling products to display
                foreach ($topSellingProducts as $product) {
                ?>
                    <div class="col-md-4 mb-3">
                        <div class="product border bg-white p-1">
                            <img class="img-fluid" src="<?php echo $product['IMAGE']; ?>" alt="Product Image">
                            <hr>
                            <p class="text-center text-dark my-2 ml-2 small" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><?php echo $product['NAME']; ?></p>
                            <!-- <p>Color: <?php echo $product['COLOR']; ?></p> -->
                            <!-- <p class="text-center">Total Quantity Sold: <?php echo $product['total_quantity']; ?></p> -->
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    <?php
    } else {
        echo '<div class="col-md-12"><p class="text-center">No top selling products available.</p></div>';
    }
    ?>




</body>

</html>