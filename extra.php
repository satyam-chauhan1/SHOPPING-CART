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
    <title>products</title>

</head>

<body class="bg-light">

    <?php
    require 'db_connect.php';
    require 'navbar.php';
    ?>

    <div class="border-top border-bottom" style="margin-top: 8%; margin-bottom: 2%;">
        <h6 class="text-center p-2 text-success">PRODUCTS</h6>
    </div>

    <div class="container">
        <!--start query -->
        <?php
        if (isset($_GET['for'])) {
            $for = $_GET['for'];
            $queryCategory = "SELECT * FROM product WHERE PRODUCT_CATEGORY_ID LIKE '%" . $for . "%'";

            //  for name search 
            if (isset($_GET['for'])) {
                $name = $_GET['for'];
                $queryName = "SELECT * FROM product WHERE NAME LIKE '%" . $name . "%'";

                // Combine both queries
                $query = "(" . $queryCategory . ") UNION (" . $queryName . ")";
            } else {
                $query = $queryCategory;
            }
        } else {
            echo error_log("Error querying database : " . mysqli_error($conn), 0);
            echo "Parameters are not set.";
        }

        $result = mysqli_query($conn, $query);
        $num = mysqli_num_rows($result);


        if ($num > 0) {
        ?>

            <!-- show products in card  -->
            <div class="row">
                <?php while ($product = mysqli_fetch_array($result)) { ?>

                    <div class="col-md-4 mt-3 mb-3">
                        <div class="border bg-white p-1">
                            <p class="bg-danger text-white col-lg-3 col-md-5 small"><?php echo $product['DISCOUNT']; ?>%OFF</p>

                            <img src="<?php echo $product['IMAGE']; ?>" alt="shoes" class="img-fluid border-bottom">

                            <a class="text-decoration-none" href="#">
                                <p class="text-center text-dark my-2 ml-2 small" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    <?php echo $product['NAME']; ?>
                                </p>
                            </a>

                            <p class="ml-2 text-center text-success small">&#8377;<?php echo $product['PRICE']; ?>
                                <del>&#8377;<?php echo $product['DEFAULT_PRICE']; ?></del>
                            </p>

                            <!-- <a class="" href="#">
                                <div class="rounded-circle p-2 mx-auto mb-2 border " style="width: 25px; height: 25px; background-color: <?php //echo $product['COLOR']; ?>"></div>
                            </a> -->

                            <!-- <a class="text-decoration-none" href="#">
                                <h5 class="text-center rounded-pill p-2  bg-dark text-white small">
                                    <?php //echo $product['ADD_CART']; ?>
                                </h5>
                            </a> -->

                        </div>

                    </div>
                <?php } ?>
            </div>

        <?php
        } else {
            echo '<h6 class="text-center">No products found.Please try again with a different products</h6>';
        }
        ?>


    </div>

</body>

</html>