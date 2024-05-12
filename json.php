<?php
function fetchProductJson($category) {
    require 'db_connect.php';

    function fetchProducts($conn,$category)
    {
        // Fetch data from tables
        $query = "SELECT p.PRODUCT_ID, p.NAME, p.IMAGE,p.PRICE, p.DEFAULT_PRICE, p.DISABLE, pf.PRODUCT_FEATURE_TYPE, pf.DESCRIPTION, pf.PRODUCT_PRICE
              FROM product p
              LEFT JOIN product_feature_appl pfa ON p.PRODUCT_ID = pfa.PRODUCT_ID
              LEFT JOIN product_feature pf ON pfa.PRODUCT_FEATURE_ID = pf.PRODUCT_FEATURE_ID 
              WHERE p.DISABLE='N'
              AND p.PRODUCT_CATEGORY_ID='$category'
              AND NOT EXISTS (
                  SELECT 1 FROM product_assoc pa 
                  WHERE pa.PRODUCT_ID_TO = p.PRODUCT_ID
              )";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $products = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $productId = $row['PRODUCT_ID'];
                $relatedProducts = fetchRelatedProducts($conn, $productId); // Fetch related products array

                // Check if product already exists in the array
                $existingProductKey = array_search($productId, array_column($products, 'MAIN_PRODUCT_ID'));

                // Product doesn't exist, create a new entry
                if ($existingProductKey === false) {
                    $product = array(
                        "MAIN_PRODUCT_ID" => $row['PRODUCT_ID'],
                        "MAIN_PRO_NAME" => $row['NAME'],
                        "MAIN_PRO_IMAGE" => ($row['IMAGE']),
                        "MAIN_PRO_PRICE" => $row['PRICE'],
                        "MAIN_PRO_DEFAULT_PRICE" => $row['DEFAULT_PRICE'],
                        "MAIN_PRO_COLOR" => $row['PRODUCT_FEATURE_TYPE'] == 'COLOR' ? $row['DESCRIPTION'] : null,
                        "MAIN_PRO_SIZES" => array(),
                        "relatedProducts" => $relatedProducts // Store related products array
                    );

                    $products[] = $product;
                }
                // Add sizes
                if ($row['PRODUCT_FEATURE_TYPE'] == 'SIZE' && $existingProductKey !== false) {
                    $products[$existingProductKey]['MAIN_PRO_SIZES'][$row['DESCRIPTION']] = $row['PRODUCT_PRICE'];
                }
            }


        // Convert array to JSON
        // $json_data = json_encode($products, JSON_PRETTY_PRINT);
        // echo $json_data;

            // Convert array to JSON
            return $products;
        } else {
            echo "Error fetching data: " . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    function fetchRelatedProducts($conn, $mainID)
    {
        // Fetch main product data
        $query = "SELECT pa.PRODUCT_ID_TO as RELATED_ID FROM product p
        LEFT JOIN product_assoc pa ON p.PRODUCT_ID = pa.PRODUCT_ID
        WHERE p.PRODUCT_ID='$mainID'";

        $result = mysqli_query($conn, $query);

        if ($result) {  
            $related_products = array();
            while ($row = mysqli_fetch_assoc($result)) {
                // Store fetched data in $related_products array
                $related_products[] = $row['RELATED_ID'];
            }

            // Output the related product IDs
            $related_product_data = array();
            foreach ($related_products as $related_product) {
                $relatedProductQuery = "SELECT p.PRODUCT_ID,p.IMAGE, pf.*
            FROM product AS p
            LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_ID = p.PRODUCT_ID
            LEFT JOIN product_feature pf ON pf.PRODUCT_FEATURE_ID = pfa.PRODUCT_FEATURE_ID
            WHERE pfa.PRODUCT_ID = '$related_product'";

                $relatedResult = mysqli_query($conn, $relatedProductQuery);
                if ($relatedResult) {
                    while ($relatedRow = mysqli_fetch_assoc($relatedResult)) {
                        // Store related product data
                        $related_product_data[] = array(
                            "RELATED_PRODUCT_ID" => $relatedRow['PRODUCT_ID'],
                            "PRODUCT_FEATURE_ID" => $relatedRow['PRODUCT_FEATURE_ID'],
                            "PRICE" => $relatedRow['PRODUCT_PRICE'],
                            "PRODUCT_FEATURE_TYPE" => $relatedRow['PRODUCT_FEATURE_TYPE'],
                        );
                
                        if ($relatedRow['PRODUCT_FEATURE_TYPE'] == 'COLOR') {
                            $related_product_data[count($related_product_data) - 1]["PRODUCT_IMAGE"] = $relatedRow['IMAGE'];
                            $related_product_data[count($related_product_data) - 1]["COLOR"] = $relatedRow['DESCRIPTION'];
                        } elseif ($relatedRow['PRODUCT_FEATURE_TYPE'] == 'SIZE') {
                            $related_product_data[count($related_product_data) - 1]["SIZE"] = $relatedRow['DESCRIPTION'];
                        }
                    }
                } else {
                    echo "Error fetching related product data: " . mysqli_error($conn);
                }
                
            }

            return $related_product_data; // Return the related product data array
        } else {
            echo "Error fetching data: " . mysqli_error($conn);
        }
    }


    return fetchProducts($conn,$category);
}
//usage
// fetchProductJson("category name");
?>
