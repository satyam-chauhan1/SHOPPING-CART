<?php
// function fetchRelatedProducts()
// {
//     require 'db_connect.php'; // Assuming this file contains database connection setup
//     // Fetch main product data
//     $query = "SELECT pa.PRODUCT_ID_TO as RELATED_ID FROM product p
//     LEFT JOIN product_assoc pa ON p.PRODUCT_ID = pa.PRODUCT_ID
//     WHERE p.PRODUCT_ID='MN_BO_ID_0004'";

//     $result = mysqli_query($conn, $query);

//     if ($result) {  
//         $related_products = array();
//         while ($row = mysqli_fetch_assoc($result)) {
//             // Store fetched data in $related_products array
//             $related_products[] = $row['RELATED_ID'];
//         }

//         // Output the related product IDs
//         $related_product_data = array();
//         foreach ($related_products as $related_product) {
//             $relatedProductQuery = "SELECT p.PRODUCT_ID, pf.*
//         FROM product AS p
//         LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_ID = p.PRODUCT_ID
//         LEFT JOIN product_feature pf ON pf.PRODUCT_FEATURE_ID = pfa.PRODUCT_FEATURE_ID
//         WHERE pfa.PRODUCT_ID = '$related_product'";

//             $relatedResult = mysqli_query($conn, $relatedProductQuery);
//             if ($relatedResult) {
//                 while ($relatedRow = mysqli_fetch_assoc($relatedResult)) {
//                     // Store related product data
//                     $related_product_data[] = array(
//                         "RELATED_PRODUCT_ID" => $relatedRow['PRODUCT_ID'],
//                         "PRODUCT_FEATURE_ID" => $relatedRow['PRODUCT_FEATURE_ID'],
//                         "PRICE" => $relatedRow['PRODUCT_PRICE'],
//                         "PRODUCT_FEATURE_TYPE" => $relatedRow['PRODUCT_FEATURE_TYPE'],
//                     );

//                     if ($relatedRow['PRODUCT_FEATURE_TYPE'] == 'SIZE') {
//                         $related_product_data[count($related_product_data) - 1]["SIZE"] = $relatedRow['DESCRIPTION'];
//                     } elseif ($relatedRow['PRODUCT_FEATURE_TYPE'] == 'COLOR') {
//                         $related_product_data[count($related_product_data) - 1]["COLOR"] = $relatedRow['DESCRIPTION'];
//                     }
//                 }
//             } else {
//                 echo "Error fetching related product data: " . mysqli_error($conn);
//             }
//         }

//         // Encode the related product data into JSON format with pretty print
//         $json_response = json_encode(array("RELATED_PRODUCTS" => $related_product_data), JSON_PRETTY_PRINT);
//         header('Content-Type: application/json');
//         // Output the JSON response
//         echo $json_response;
//     } else {
//         echo "Error fetching data: " . mysqli_error($conn);
//     }
// }
// fetchRelatedProducts();

// require 'json.php';
// fetchProductJson("MN_BO");


?>
