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
                    <div class="col-md-4 mt-3 mb-3" id="<?php echo $product['MAIN_PRODUCT_ID']; ?>">
                        <div class="border bg-white p-1">
                            <img src="<?php echo $product['MAIN_PRO_IMAGE']; ?>" alt="shoes" class="img-fluid border-bottom main-image-<?php echo $product['MAIN_PRODUCT_ID']; ?>">

                            <a class="text-decoration-none" href="#">
                                <p class="text-center text-dark my-2 ml-2 small main-name-<?php echo $product['MAIN_PRODUCT_ID']; ?>" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    <?php echo $product['MAIN_PRO_NAME']; ?>
                                </p>
                            </a>

                            <p id="main-price-<?php echo $product['MAIN_PRODUCT_ID']; ?>" class="ml-2 text-center text-success small">
                                &#8377;<span class="price-value"><?php echo $product['MAIN_PRO_PRICE']; ?></span>
                                <del>&#8377;<?php echo $product['MAIN_PRO_DEFAULT_PRICE']; ?></del>
                            </p>

                            <div class="text-center">
                                <!-- main product color  -->
                                <div class="d-inline-block mr-1">
                                    <a class="color-link" href="#" data-color="<?php echo $product['MAIN_PRO_COLOR']; ?>" data-image="<?php echo $product['MAIN_PRO_IMAGE']; ?>" data-price="<?php echo $product['MAIN_PRO_PRICE']; ?>" data-name="<?php echo $product['MAIN_PRO_NAME']; ?>" data-sizes='<?php echo json_encode($product['MAIN_PRO_SIZES']); ?>' data-product-id="<?php echo $product['MAIN_PRODUCT_ID']; ?>">
                                        <div class="rounded-circle p-2 mx-auto mb-2 border" style="width: 25px; height: 25px; background-color: <?php echo $product['MAIN_PRO_COLOR']; ?>"></div>
                                    </a>
                                </div>
                                <!-- related product color  -->
                                <?php foreach ($product['relatedProducts'] as $relatedProduct) : ?>
                                    <?php if ($relatedProduct['PRODUCT_FEATURE_TYPE'] === 'COLOR') : ?>
                                        <div class="d-inline-block mr-1">
                                            
                                            <?php 
                                            $relatedProductId = $relatedProduct['RELATED_PRODUCT_ID'];
                                            $filteredArray = array_filter($product['relatedProducts'], function($item) use ($relatedProductId) {
                                                return $item['PRODUCT_FEATURE_TYPE'] === 'SIZE' && $item['RELATED_PRODUCT_ID'] === $relatedProductId;
                                            });
                                            $outputArray = [];
                                            
                                            foreach ($filteredArray as $item) {
                                                $size = $item['SIZE'];
                                                $price = $item['PRICE'];
                                                $outputArray[$size] = $price;
                                            }
                                            
                                            ?>

                                            <a class="color-link" href="#" data-name="<?php echo $relatedProduct['PRODUCT_NAME']; ?>" data-color="<?php echo $relatedProduct['COLOR']; ?>" data-image="<?php echo $relatedProduct['PRODUCT_IMAGE']; ?>" data-sizes='<?php echo json_encode($outputArray); ?>' data-product-id="<?php echo $product['MAIN_PRODUCT_ID']; ?>">
                                                <div class="rounded-circle p-2 mx-auto mb-2 border" style="width: 25px; height: 25px; background-color: <?php echo $relatedProduct['COLOR']; ?>"></div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <!-- Size display -->
                            <div id="sizes-<?php echo $product['MAIN_PRODUCT_ID']; ?>" class="text-center mt-2">
                                <!-- Sizes will be displayed here when a color is clicked -->
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

    <script>
    $(document).ready(function() {
        $('.color-link').click(function(e) {
            e.preventDefault();
            var imageSrc = $(this).data('image');
            var newName = $(this).data('name');
            var sizes = $(this).data('sizes');
            var productId = $(this).data('product-id');
            console.log(imageSrc)
            console.log(newName)
            console.log(sizes)
            console.log(productId)

            // Update main product image
            $('.main-image-' + productId).attr('src', imageSrc);

            // Update main product name
            $('.main-name-' + productId).text(newName);

            // Update main product sizes
            var sizesDiv = $('#sizes-' + productId);
            sizesDiv.empty(); // Clear the existing sizes

            $.each(sizes, function(size, price) {
                sizesDiv.append('<div class="d-inline-block rounded-circle border ml-2 mb-2 size-link" style="width: 25px; height: 25px; cursor: pointer;" data-price="' + price + '" data-product-id="' + productId + '">' + size + '</div>');
            });

            // Update price for the main product
            $('#main-price-' + productId + ' .price-value').text($(this).data('price'));
        });

        // Handle size click events
        $(document).on('click', '.size-link', function() {
            var newPrice = $(this).data('price');
            var productId = $(this).data('product-id');

            // Update the price display
            $('#main-price-' + productId + ' .price-value').text(newPrice);

            // Remove the 'selected-size' class from all size links within the same product
            $('#sizes-' + productId + ' .size-link').removeClass('selected-size');

            // Add the 'selected-size' class to the clicked size link
            $(this).addClass('selected-size');
        });
    });
</script>



<style>
    .selected-size {
        background-color: #6c757d; /* Change this color to your preferred selection color */
        color: #fff; /* Change text color fo */
    }
</style>

</body>

</html>