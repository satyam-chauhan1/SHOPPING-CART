<?php
require 'db_connect.php';
require 'json.php';
require 'navbar.php';
if (isset($_GET['for'])) {
    $for = $_GET['for'];

    // Get the JSON data
    $jsonData = fetchProductJson($for);

    // Check if $jsonData is valid before encoding
    if ($jsonData !== null) {
        // Encode the JSON data
        $encodedData = json_encode($jsonData);
        // Log the JSON data to the JavaScript console
        echo '<script>console.log(' . $encodedData . ');</script>';
    }
}
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
    <title>Show products</title>
</head>

<body class="bg-light">

    <div class="border-top border-bottom" style="margin-top: 8%; margin-bottom: 2%;">
        <h6 class="text-center p-2 text-success">PRODUCTS</h6>
    </div>
    <div class="container mt-3">
        <div class="row">
            <?php
            // Assuming $jsonData contains the decoded JSON data
            if (!empty($jsonData)) {
                foreach ($jsonData as $product) {
            ?>
                    <div class="col-md-4 mt-3 mb-3" id="">
                        <div class="border bg-white p-1">
                            <!-- <p class="bg-danger text-white col-lg-3 col-md-5 small"><?php echo $product['DISCOUNT']; ?>% OFF</p> -->

                            <img src="<?php echo $product['MAIN_PRO_IMAGE']; ?>" alt="shoes" class="img-fluid border-bottom">

                            <a class="text-decoration-none" href="#">
                                <p class="text-center text-dark my-2 ml-2 small" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    <?php echo $product['MAIN_PRO_NAME']; ?>
                                </p>
                            </a>

                            <p class="ml-2 text-center text-success small">&#8377;<?php echo $product['MAIN_RPO_PRICE']; ?>
                                <del>&#8377;<?php echo $product['MAIN_RPO_DEFAULT_PRICE']; ?></del>
                            </p>

                            <div class="text-center">
                                <!-- main product color  -->
                                <div class="d-inline-block mr-1">
                                    <a class="" href="#">
                                        <div class="rounded-circle p-2 mx-auto mb-2 border" style="width: 25px; height: 25px; background-color: <?php echo $product['MAIN_PRO_COLOR']; ?>"></div>
                                    </a>
                                </div>

                                <!-- related product color  -->
                                <?php foreach ($product['relatedProducts'] as $relatedProduct) : ?>
                                    <?php if ($relatedProduct['PRODUCT_FEATURE_TYPE'] === 'COLOR') : ?>
                                        <div class="d-inline-block mr-1">
                                            <a class="" href="#">
                                                <div class="rounded-circle p-2 mx-auto mb-2 border" style="width: 25px; height: 25px; background-color: <?php echo $relatedProduct['COLOR']; ?>"></div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <!-- <a class="text-decoration-none" href="#">
                            <h5 class="text-center rounded-pill p-2 bg-dark text-white small">
                                <?php echo $product['ADD_CART']; ?>
                            </h5>
                        </a> -->

                            <div class=" text-center rounded-pill mb-2  bg-dark">
                                <button type="button" class=" btn text-white">Add to cart</button>
                            </div>



                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<h6 class="text-center">No products found. Please try again with different products.</h6>';
            }
            ?>
        </div>
    </div>


</body>

</html>