<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Cart</title>
    <style>
        .cart-table img {
            width: 70px;
            height: auto;
        }

        .quantity-input input {
            width: 60px;
            border: none;
            outline: none;
        }
    </style>
</head>

<body class="bg-light">
    <?php require 'navbar.php'; ?>

    <div class="container mt-3">
        <div class="" style="margin-top: 9%; margin-bottom: 2%;">
            <h2 class="text-center mb-3">MY Cart</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table cart-table">
                    <thead class="">
                        <tr>
                            <th class="align-middle text-center">Image</th>
                            <th class="align-middle text-center">Product Name</th>
                            <!-- <th class="align-middle text-center">Size</th> -->
                            <th class="align-middle text-center">Colour</th>
                            <th class="align-middle text-center">Price</th>
                            <th class="align-middle text-center">Quantity</th>
                            <th class="align-middle text-center">Total Price</th>
                            <th class="align-middle text-center">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalPrice = 0;
                        $totalQuantity = 0;
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $index => $item) {
                                $totalPrice += $item['price'] * $item['quantity'];
                                $totalQuantity += $item['quantity'];
                        ?>
                                <tr id="cart-item-<?php echo $index; ?>">
                                    <!-- image  -->
                                    <td class="align-middle text-center">
                                        <img src="<?php echo $item['image']; ?>" alt="Product Image">
                                    </td>

                                    <!-- name  -->
                                    <td class="align-middle">
                                        <div><?php echo $item['name']; ?></div>
                                        <div class="mt-2"> <strong class="font-italic">Size:</strong> <?php echo $item['size']; ?></div>
                                    </td>


                                    <!-- size  -->
                                    <!-- <td class="align-middle text-center">
                                        <?php echo $item['size']; ?>
                                    </td> -->

                                    <!-- colour  -->
                                    <td class="align-middle text-center">
                                        <div class="rounded-circle  mx-auto " style="width: 20px; height: 20px; background-color: <?php echo $item['color']; ?>;"></div>
                                    </td>

                                    <!--per unit price  -->
                                    <td class="align-middle text-center">&#8377; <?php echo number_format($item['price'], 2); ?></td>

                                    <!-- quantity -->
                                    <td class="align-middle text-center">
                                        <div class="quantity-input d-flex align-items-center justify-content-center">
                                            <button class="btn update-quantity" data-index="<?php echo $index; ?>" data-action="decrease">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="text" value="<?php echo $item['quantity']; ?>" readonly class="form-control text-center">
                                            <button class="btn update-quantity" data-index="<?php echo $index; ?>" data-action="increase">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>


                                    <!--total price  -->
                                    <td class="align-middle text-center">&#8377; <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>

                                    <!-- remove from cart -->
                                    <td class="align-middle text-center text-danger remove-from-cart" style="font-size: 1.2em;" data-index="<?php echo $index; ?>">
                                        <i class="fa fa-trash"></i>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <!--grand total -->
                            <tr>
                                <td colspan="5" class="text-right font-weight-bold font-italic">Grand Total(item <?php echo $totalQuantity; ?>) :</td>
                                <td colspan="4" class=" font-weight-bold">&#8377; <?php echo number_format($totalPrice, 2); ?></td>
                            </tr>

                        <?php
                        } else {
                            echo '<tr><td colspan="6" class="text-center"><h4>Your cart is empty.<h4></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <?php if (!empty($_SESSION['cart'])) { ?>
                    <div class="row ">
                        <div class="col-6">
                            <a href="show_products.php?for=" class="text-decoration-none text-white p-2 small rounded bg-success"><i class="fa fa-long-arrow-left"></i> CONTINUE SHOPPING</a>
                        </div>

                        <div class="col-6 text-right mb-3">
                            <a href="#" class="text-decoration-none text-white p-2 small rounded bg-danger" data-toggle="modal" data-target="#checkoutModal">PROCEED TO BUY <i class="fa fa-long-arrow-right"></i></a>
                        </div>


                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong class="font-italic">Total Quantity:</strong> <?php echo $totalQuantity; ?></p>
                    <p><strong class="font-italic">Total Price:</strong> &#8377; <?php echo number_format($totalPrice, 2); ?></p>
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method</label>
                        <select class="form-control" id="paymentMethod">
                            <option>Credit Card</option>
                            <option>Debit Card</option>
                            <option>Net Banking</option>
                            <option>UPI</option>
                            <option>Cash on Delivery</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Confirm Purchase</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.update-quantity').click(function() {
                var index = $(this).data('index');
                var action = $(this).data('action');

                $.ajax({
                    url: 'update_quantity.php',
                    method: 'POST',
                    data: {
                        index: index,
                        action: action
                    },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    }
                });
            });

            $('.remove-from-cart').click(function() {
                var index = $(this).data('index');

                $.ajax({
                    url: 'update_cart.php',
                    method: 'POST',
                    data: {
                        action: 'remove',
                        index: index
                    },
                    success: function(response) {
                        console.log(response);
                        $('#cart-item-' + index).remove();
                        location.reload();
                    }
                });
            });

            function updateCartCount() {
                var cartCount = <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>;
                $('#cart-count').text(cartCount);
            }
        });
    </script>
</body>

</html>