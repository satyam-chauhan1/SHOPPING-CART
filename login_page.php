<div class="border-top border-bottom" style="margin-top: 5%; margin-bottom: 2%;"></div>

<?php
require 'db_connect.php';
require 'require_field.php';
require 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phoneNumber = $_POST['phoneNumber'];
    $password = trim($_POST['password']);


    $sql = "Select * from register_form where PHONE = '$phoneNumber'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $hash_password = null;
        if (isset($row['PASSWORD'])) {
            $hash_password = $row['PASSWORD'];
        }


        if (password_verify($password, $hash_password)) {
            echo '<div class="alert alert-warning">
       <strong>Success!</strong> login successfully.
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>';
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['phoneNumber'] = $phoneNumber;
            $_SESSION['firstName'] = $row['FIRST_NAME'];
            header("location:product_categories.php?id=");
        } else {
            echo '<div class="alert alert-danger">
       <strong>Error!</strong>Please enter a valid username and password.
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>';
        }
    } else {
        echo error_log("Error querying database : " . mysqli_error($conn), 0);

        echo '<div class="alert alert-warning">
       <strong>Error!</strong> There are some problem at this time.
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
     </div>';
    }
}

mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>Login page</title>
</head>


<body>
    <div class="d-flex justify-content-center align-items-center">
        <form action="login_page.php" method="post" class="col-lg-5 border p-4 m-4">
            <div class="form-group">
                <h3 class="text-center">LOGIN</h3>
                <p class="text-center mb-4">Enter your username and password to login</p>

                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone Number">
                <?php
                // Check if phoneNumberRequired variable is set and true
                if (isset($phoneNumberRequired) && $phoneNumberRequired == true) {
                    echo '<span style="color:red;">Phone number is required*</span>';
                }
                ?>
            </div>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <div class="input-group-prepend" onclick="togglePasswordVisibility()">
                    <span class="input-group-text"><i id="toggleIcon" class="fa fa-eye-slash"></i></span>
                </div>
            </div>
            <?php
            // Check if passwordRequired variable is set and true
            if (isset($passwordRequired) && $passwordRequired == true) {
                echo '<span style="color:red;">Password is required*</span>';
            }
            ?>

            <button type="submit" class="btn btn-dark btn-block text-center rounded-pill text-white mt-4">
                <h5>Login</h5>
            </button>
            <p class="text-center mt-4 text-secondary">Don't have an account? <a href="Register_form.php">Sign up</a></p>
        </form>
    </div>

    <!-- for show password  -->
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>


</html>