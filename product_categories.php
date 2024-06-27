<!-- <div class="border-top border-bottom" style="margin-top: 5%; margin-bottom: 2%;"></div>
 -->

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
    <title>categories</title>
</head>

<body class="bg-light">

    <div class="border-top border-bottom" style="margin-top: 8%; ">
        <h4 class="text-center p-2 ">PRODUCT CATEGORIES</h4>
    </div>

    <?php require 'navbar.php'; ?>
    <div class="container">

        <?php

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $query = "SELECT * FROM product_category WHERE PRODUCT_CATEGORY_ID LIKE '" . $id . "%'";
        } else {
            echo "Parameters are not set.";
        }

        $result = mysqli_query($conn, $query);
        $num = mysqli_num_rows($result);

        if ($num > 0) {
        ?>

            <div class="row">
                <?php while ($product = mysqli_fetch_array($result)) { ?>

                    <div class="col-md-4 mt-3">
                        <div class="border bg-white">

                            <img src="<?php echo $product['IMAGE']; ?>" alt="shoes" class="img-fluid">

                            <a class="text-decoration-none" href="<?php echo 'show_products.php?for=' . $product['PRODUCT_CATEGORY_ID']; ?>">
                                <h5 class="text-center bg-dark text-white p-3 mt-2">
                                    <?php echo $product['NAME']; ?>
                                </h5>
                            </a>
                        </div>
                    </div>


                <?php } ?>
            </div>

        <?php
        }
        ?>


    </div>

</body>

</html>