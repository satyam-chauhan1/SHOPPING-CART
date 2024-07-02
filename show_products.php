<?php
require 'db_connect.php';
require 'json.php';
require 'navbar.php';

if (isset($_GET['for'])) {
    $for = $_GET['for'];
} else {
    $for = ''; // Default category or empty search
}

// Get the JSON data
$jsonData = fetchProductJson($for);

// Check if $jsonData is valid before encoding
if ($jsonData !== null) {
    // Encode the JSON data
    $encodedData = json_encode($jsonData);
    // Log the JSON data to the JavaScript console
    echo '<script>console.log(' . $encodedData . ');</script>';
}

// Close database connection
mysqli_close($conn);
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

    <style>
        .hover-zoom {
            overflow: hidden;
            position: relative;
        }

        .hover-zoom img {
            transition: transform 0.3s ease;
        }

        .hover-zoom:hover img {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-light">

    <div class="border-top border-bottom" style="margin-top: 8%; margin-bottom: 2%;">
        <h4 class="text-center p-2">PRODUCTS</h4>
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
                            <!-- <p class="bg-danger text-white col-lg-3 col-md-5 small"> 20% OFF</p> -->

                            <!-- product image  -->
                            <div class="hover-zoom">
                                <img src="<?php echo $product['MAIN_PRO_IMAGE']; ?>" alt="shoes" class="img-fluid border-bottom main-image-<?php echo $product['MAIN_PRODUCT_ID']; ?>">

                            </div>

                            <!-- product name  -->
                            <a class="text-decoration-none" href="#">
                                <p class="text-center text-dark my-2 ml-2 small main-name-<?php echo $product['MAIN_PRODUCT_ID']; ?>" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    <?php echo $product['MAIN_PRO_NAME']; ?>
                                </p>
                            </a>

                            <!-- product price  -->
                            <p id="main-price-<?php echo $product['MAIN_PRODUCT_ID']; ?>" class="ml-2 text-center text-success small">
                                &#8377;<span class="price-value"><?php echo $product['MAIN_PRO_PRICE']; ?></span>
                                <del>&#8377;<?php echo $product['MAIN_PRO_DEFAULT_PRICE']; ?></del>
                            </p>

                            <div class="text-center">
                                <!-- main product color  -->
                                <div class="d-inline-block mr-1">
                                    <a class="color-link main-color-link" href="#" data-color="<?php echo $product['MAIN_PRO_COLOR']; ?>" data-image="<?php echo $product['MAIN_PRO_IMAGE']; ?>" data-price="<?php echo $product['MAIN_PRO_PRICE']; ?>" data-name="<?php echo $product['MAIN_PRO_NAME']; ?>" data-sizes='<?php echo json_encode($product['MAIN_PRO_SIZES']); ?>' data-main-product-id="<?php echo $product['MAIN_PRODUCT_ID']; ?>">
                                        <div class="rounded-circle p-2 mx-auto mb-2 border" style="width: 25px; height: 25px; background-color: <?php echo $product['MAIN_PRO_COLOR']; ?>"></div>
                                    </a>
                                </div>
                                <!-- related product color  -->
                                <?php foreach ($product['relatedProducts'] as $relatedProduct) : ?>
                                    <?php if ($relatedProduct['PRODUCT_FEATURE_TYPE'] === 'COLOR') : ?>
                                        <div class="d-inline-block mr-1">

                                            <?php
                                            $relatedProductId = $relatedProduct['RELATED_PRODUCT_ID'];
                                            $filteredArray = array_filter($product['relatedProducts'], function ($item) use ($relatedProductId) {
                                                return $item['PRODUCT_FEATURE_TYPE'] === 'SIZE' && $item['RELATED_PRODUCT_ID'] === $relatedProductId;
                                            });
                                            $outputArray = [];

                                            foreach ($filteredArray as $item) {
                                                $size = $item['SIZE'];
                                                $price = $item['PRICE'];
                                                $outputArray[$size] = $price;
                                            }

                                            ?>

                                            <a class="color-link related-color-link" href="#" data-name="<?php echo $relatedProduct['PRODUCT_NAME']; ?>" data-color="<?php echo $relatedProduct['COLOR']; ?>" data-image="<?php echo $relatedProduct['PRODUCT_IMAGE']; ?>" data-sizes='<?php echo json_encode($outputArray); ?>' data-price="<?php echo $relatedProduct['PRICE']; ?>" data-main-product-id="<?php echo $product['MAIN_PRODUCT_ID']; ?>" data-related-product-id="<?php echo $relatedProduct['RELATED_PRODUCT_ID']; ?>">
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
                            <!-- Add to Cart button placeholder -->
                            <div id="add-to-cart-<?php echo $product['MAIN_PRODUCT_ID']; ?>" class="text-center mt-2 ">
                                <!-- Add to Cart button will be displayed here when a size is clicked -->
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
        var cart = []; // Global cart array
        var productContext = ''; //  main or related product is clicked

        $(document).ready(function() {
            $('.main-color-link').click(function(e) {
                e.preventDefault();
                productContext = 'main'; // Set context to main product
                var mainProductId = $(this).data('main-product-id');
                // console.log("Main Product ID: " + mainProductId);

                var imageSrc = $(this).data('image');
                var newName = $(this).data('name');
                var sizes = $(this).data('sizes');
                var color = $(this).data('color');

                $('.main-image-' + mainProductId).attr('src', imageSrc);
                $('.main-name-' + mainProductId).text(newName);

                var sizesDiv = $('#sizes-' + mainProductId);
                sizesDiv.empty();

                $.each(sizes, function(size, price) {
                    sizesDiv.append('<div class="d-inline-block rounded-circle border ml-2 mb-2 size-link" style="width: 25px; height: 25px; cursor: pointer;" data-price="' + price + '" data-main-product-id="' + mainProductId + '" data-size="' + size + '" data-color="' + color + '">' + size + '</div>');
                });

                $('#main-price-' + mainProductId + ' .price-value').text($(this).data('price'));
            });

            $('.related-color-link').click(function(e) {
                e.preventDefault();
                productContext = 'related'; // Set context to related product
                var relatedProductId = $(this).data('related-product-id');
                // console.log("Related Product ID: " + relatedProductId);

                var imageSrc = $(this).data('image');
                var newName = $(this).data('name');
                var sizes = $(this).data('sizes');
                var mainProductId = $(this).data('main-product-id');
                var color = $(this).data('color');

                $('.main-image-' + mainProductId).attr('src', imageSrc);
                $('.main-name-' + mainProductId).text(newName);

                var sizesDiv = $('#sizes-' + mainProductId);
                sizesDiv.empty();

                $.each(sizes, function(size, price) {
                    sizesDiv.append('<div class="d-inline-block rounded-circle border ml-2 mb-2 size-link" style="width: 25px; height: 25px; cursor: pointer;" data-price="' + price + '" data-main-product-id="' + mainProductId + '" data-related-product-id="' + relatedProductId + '" data-size="' + size + '" data-color="' + color + '">' + size + '</div>');
                });

                $('#main-price-' + mainProductId + ' .price-value').text($(this).data('price'));
            });

            $(document).on('click', '.size-link', function() {
                var newPrice = $(this).data('price');
                var mainProductId = $(this).data('main-product-id');
                var relatedProductId = $(this).data('related-product-id');
                var size = $(this).data('size');
                var color = $(this).data('color');

                $('#main-price-' + mainProductId + ' .price-value').text(newPrice);

                $('#sizes-' + mainProductId + ' .size-link').removeClass('selected-size');
                $(this).addClass('selected-size');

                var addToCartDiv = $('#add-to-cart-' + mainProductId);
                addToCartDiv.empty();
                addToCartDiv.append('<button class="btn rounded-pill mb-2 text-white small add-to-cart-btn" style="background-color: #6c757d;" data-main-product-id="' + mainProductId + '" data-related-product-id="' + relatedProductId + '" data-size="' + size + '" data-name="' + $('.main-name-' + mainProductId).text() + '" data-image="' + $('.main-image-' + mainProductId).attr('src') + '" data-price="' + newPrice + '" data-color="' + color + '">Add to Cart</button>');
            });

            $(document).on('click', '.add-to-cart-btn', function(e) {
                e.preventDefault();

                // Check if the user is logged in
                if (!<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>) {
                    // User is not logged in, show alert
                    alert("You need to login to add products to your cart.");
                    // Redirect to login page
                    window.location.href = "login_page.php";
                    return; // Stop further execution
                }

                var mainProductId = $(this).data('main-product-id');
                var relatedProductId = $(this).data('related-product-id');
                var name = $(this).data('name');
                var image = $(this).data('image');
                var size = $(this).data('size');
                var price = $(this).data('price');
                var color = $(this).data('color');

                var cartJson = {};
                // Check if the product already exists in the cart
                var existingProduct = cart.find(function(item) {
                    return item.main_product_id === mainProductId && item.related_product_id === relatedProductId && item.size === size && item.color === color;
                });
                // console.log("Existing product: ", existingProduct);

                if (existingProduct) {
                    cartJson = JSON.stringify(existingProduct);
                } else {
                    // Add a new entry to the cart if the product doesn't exist
                    existingProduct = {
                        name: name,
                        image: image,
                        size: size,
                        price: price,
                        color: color,
                        quantity: 1 // Initialize quantity to 1
                    };

                    // Add the appropriate product ID based on the context
                    if (productContext === 'main') {
                        existingProduct.main_product_id = mainProductId;
                    } else if (productContext === 'related') {
                        existingProduct.related_product_id = relatedProductId;
                    }

                    cartJson = JSON.stringify(existingProduct);
                    cart.push(existingProduct);
                }

                // Log the cart JSON string to the console
                console.log(cartJson);

                // Send the updated cart to the server
                $.ajax({
                    url: 'update_cart.php',
                    method: 'POST',
                    data: {
                        cart: cartJson
                    },
                    success: function(response) {
                        // console.log(response);
                    }
                });

                // Update cart count
                updateCartCount();
            });

            function updateCartCount() {
                var cartCount = 0;
                // Calculate total quantity in the cart
                cart.forEach(function(item) {
                    cartCount += item.quantity;
                });
                $('#cart-count').text(cartCount);
            }

        });
    </script>


    <style>
        .selected-size {
            background-color: #6c757d;
            color: #fff;
        }
    </style>

</body>

</html>