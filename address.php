<?php
require 'db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['phoneNumber'])) {
    // Handle the case where the user is not logged in
    die('User not logged in.');
}

// Fetch addresses of the logged-in user based on phone number
$userPhone = $_SESSION['phoneNumber'];
$addressQuery = "SELECT ADDRESS_1, LANDMARK, HOUSE_NO, DISTRICT, PIN_CODE, STATE FROM register_form WHERE PHONE = '$userPhone'";
$addressResult = mysqli_query($conn, $addressQuery);

// Array to store addresses of the logged-in user
$addresses = array();

// Check if query was successful
if ($addressResult) {
    // Fetch each row and store the address in the $addresses array
    while ($row = mysqli_fetch_assoc($addressResult)) {
        // Combine the address components into a single string
        $address = $row['LANDMARK'] . ", " . $row['ADDRESS_1'] . ", " . $row['HOUSE_NO'] . ", " . $row['DISTRICT'] . ", " . $row['PIN_CODE'] . ", " . $row['STATE'];
        // Add the combined address to the addresses array
        $addresses[] = $address;
    }
}

// Store addresses in session variable
$_SESSION['addresses'] = $addresses;

$addressesJSON = json_encode($addresses);

// Print addresses for testing
// print_r($_SESSION['addresses']);
?>
