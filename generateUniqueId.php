<?php
// Function to generate a unique, incrementing order ID
function generateUniqueId($conn, $prefix, $tableName, $ID) {
    // Query to get the last order ID
    $query = "SELECT $ID FROM $tableName ORDER BY $ID DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastOrderID = $row[$ID];
        $lastOrderNumber = intval(substr($lastOrderID, strlen($prefix))); // Extract the numeric part
        
        $newOrderNumber = $lastOrderNumber + 1;
        $suffix = str_pad($newOrderNumber, 3, '0', STR_PAD_LEFT); // Ensure it is 3 digits
    } else {
        // If no previous order ID exists, start from 001
        $suffix = '001';
    }
    
    $orderID = $prefix . $suffix;
    return $orderID;
}
?>
