<?php
require 'db_connect.php';
function fetchProducts($conn)
{
    // Fetch data from tables
    $query = "SELECT p.PRODUCT_ID, p.NAME, p.IMAGE, p.DEFAULT_PRICE,p.DISABLE, pf.PRODUCT_FEATURE_TYPE, pf.DESCRIPTION, pf.PRODUCT_PRICE
          FROM product p
          LEFT JOIN product_feature_appl pfa ON p.PRODUCT_ID = pfa.PRODUCT_ID
          LEFT JOIN product_feature pf ON pfa.PRODUCT_FEATURE_ID = pf.PRODUCT_FEATURE_ID 
          WHERE p.DISABLE = 'N' and p.PRODUCT_ID = 'MN_BO_ID_0001' AND p.PRODUCT_CATEGORY_ID='MN_BO'
          ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $products = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $productId = $row['PRODUCT_ID'];

            // Check if product already exists in the array
            $existingProductKey = array_search($productId, array_column($products, 'MAIN_PRODUCT_ID'));

            // Product doesn't exist, create a new entry
            $product = array(
                "MAIN_PRODUCT_ID" => $row['PRODUCT_ID'],
                "MAIN_PRO_NAME" => $row['NAME'],
                "MAIN_PRO_IMAGE" => basename($row['IMAGE']),
                "MAIN_RPO_DEFAULT_PRICE" => $row['DEFAULT_PRICE'],
            );

            // Add color or create a new sizes object depending on feature type
            if ($row['PRODUCT_FEATURE_TYPE'] == 'COLOR') {
                $product['MAIN_PRO_COLOR'] = $row['DESCRIPTION'];
            } elseif ($row['PRODUCT_FEATURE_TYPE'] == 'SIZE') {
                $product['MAIN_PRO_SIZES'] = array($row['DESCRIPTION'] => $row['PRODUCT_PRICE']);
            }

            if ($existingProductKey !== false) {
                if ($row['PRODUCT_FEATURE_TYPE'] == 'SIZE') {
                    $products[$existingProductKey]['MAIN_PRO_SIZES'][$row['DESCRIPTION']] = $row['PRODUCT_PRICE'];
                }
            } else {
                // Add the product to the products array
                $products[] = $product;
            }
        }


        // Convert array to JSON
        $json_data = json_encode($products, JSON_PRETTY_PRINT);
        header('Content-Type: application/json');
        echo $json_data;
    } else {
        echo "Error fetching data: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}

fetchProducts($conn)
?>