<?php
session_start(); // Start the session

// Check if there are any session variables
if (!empty($_SESSION)) {
    echo '<pre>'; // Use <pre> tags for better readability
    print_r($_SESSION); // Print all session variables
    echo '</pre>';
} else {
    echo 'No session variables set.';
}
?>
