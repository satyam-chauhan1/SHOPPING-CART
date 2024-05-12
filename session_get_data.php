<?php
// Start the session
session_start();

//session variables
if(isset($_SESSION['username'])&& isset($_SESSION['user_id'])){

    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id'];
    echo "Welcome, $username. Your user ID is $userId";
}else{
    error_log("there are some problem in accessing session variabls",0);
    echo "No data found";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session</title>
</head>

<body>
    <?php
    // Unset all session variables
    // $_SESSION = array();
    session_unset();

    // Destroy the session
    session_destroy();
    ?>

</body>

</html>