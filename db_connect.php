
<?php
    // connecting to the database
    $servername ="localhost";
    $username = "root";
    $password = "";
    $database="shopping_card";

    // create a connection 
    $conn = mysqli_connect($servername,$username,$password,$database);
    if(!$conn){
        die("Sorry we failed to connect". mysqli_connect_error());
    }
    // create a db 
    // $sql = "CREATE DATABASE shopping_card";
    // mysqli_query($conn, $sql);
    

    ?>
    