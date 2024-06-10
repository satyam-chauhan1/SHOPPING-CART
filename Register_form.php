<?php
require 'db_connect.php';
require 'require_field.php';
require 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $phone_number = $_POST['phoneNumber'];
    // $password = $_POST['password'];
    $password = trim($_POST['password']);
    $address1 = $_POST['address1'];
    $landMark = $_POST['landMark'];
    $house_no = $_POST['houseNo'];
    $district = $_POST['district'];
    $pin_code = $_POST['pinCode'];
    $state = $_POST['state'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
    // echo "Hash Password : " . $hashed_password ."<br>";

    // Insert data into the register_form table
    $sql = "INSERT INTO register_form(FIRST_NAME,LAST_NAME,PHONE,PASSWORD,ADDRESS_1,LANDMARK,HOUSE_NO,DISTRICT,PIN_CODE,STATE) 
            VALUES ('$first_name', '$last_name', '$phone_number', '$hashed_password', '$address1', '$landMark','$house_no', '$district' ,'$pin_code', '$state')";

    if (mysqli_query($conn, $sql)) {
        echo '<div class="alert alert-success">
        <strong>Success!</strong>login successfully.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>';
        header("location:index.php");
    } else {
        echo error_log("Error querying database : " . mysqli_error($conn), 0);
        echo '<div class="alert alert-danger">
        <strong>Invalid!</strong>Invalid details.
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
    <title>Register form</title>
</head>


<body>
    <div class="d-flex justify-content-center align-items-center">
        <form action="register_form.php" method="post" class="col-lg-5 m-5 p-4 border ">

            <h3 class="text-center">SIGN UP</h3>
            <p class="text-center">Please fill in the information below</p>
            <div class="form-group">
                <input type="text" class="form-control p-4" id="firstName" name="firstName" placeholder="First name*">
                <?php
                if ($firstNameRequired == true) {
                    echo '<span style="color:red;">First name is required*</span>';
                }
                ?>
            </div>

            <div class="form-group">
                <input type="text" class="form-control p-4" id="lastName" name="lastName" placeholder="Last name*">
                <?php
                if ($lastNameRequired == true) {
                    echo '<span style="color:red;">Last name is required*</span>';
                }
                ?>
            </div>

            <div class="form-group">
                <input type="text" class="form-control p-4" id="phoneNumber" name="phoneNumber" placeholder="Phone*">
                <?php
                if ($phoneNumberRequired == true) {
                    echo '<span style="color:red;">phone number is required*</span>';
                } else if ($phoneNumberLength == true) {
                    echo '<span style="color:red;">Enter a valid 10-digit phone number*</span>';
                } else if ($phoneNumberNumeric == true) {
                    echo '<span style="color:red;">Enter only numeric characters*</span>';
                }
                ?>
            </div>

            <div class="input-group">
                <input type="password" class="form-control p-4" id="password" name="password" placeholder="Password*">
                <div class="input-group-prepend" onclick="togglePasswordVisibility()">
                    <span class="input-group-text"><i id="toggleIcon" class="fa fa-eye-slash"></i></span>
                </div>
            </div>
            <?php
            if ($passwordRequired == true) {
                echo '<span style="color:red;">password is required*</span>';
            }
            ?>


            <div class="form-group mt-3">
                <input type="text" class="form-control p-4" id="address1" name="address1" placeholder="Address*">
                <?php
                if ($address1Required == true) {
                    echo '<span style="color:red;">Address is required*</span>';
                }
                ?>
            </div>

            <div class="form-group mt-3">
                <input type="text" class="form-control p-4" id="landMark" name="landMark" placeholder="Landmark*">
                <?php
                if ($landMarkRequired == true) {
                    echo '<span style="color:red;">Lamdmark field is required*</span>';
                }
                ?>
            </div>
            <div class="row">
                <div class=" col form-group mt-3">
                    <input type="text" class="form-control p-4" id="houseNo" name="houseNo" placeholder="House/Flat No.*">
                    <?php
                    if ($houseNoRequired == true) {
                        echo '<span style="color:red;">House/Flat No. is required*</span>';
                    }
                    ?>
                </div>
                <div class=" col form-group mt-3">
                    <input type="text" class="form-control p-4" id="district" name="district" placeholder="District*">
                    <?php
                    if ($districtRequired == true) {
                        echo '<span style="color:red;">District Name is required*</span>';
                    }
                    ?>
                </div>

                <div class="row ml-2">
                    <div class=" col form-group mt-3">
                        <input type="text" class="form-control p-4" id="pinCode" name="pinCode" placeholder="Pin Code*">
                        <?php
                        if ($pinCodeRequired == true) {
                            echo '<span style="color:red;">Pin Code is required*</span>';
                        } else if ($pinCodeLength == true) {
                            echo '<span style="color:red;">Enter a valid 6-digit phone number*</span>';
                        } else if ($pinCodeNumeric == true) {
                            echo '<span style="color:red;">Enter only numeric characters*</span>';
                        }
                        ?>
                    </div>

                    <div class=" col form-group mt-3">
                        <input type="text" class="form-control p-4" id="state" name="state" placeholder="State*">
                        <?php
                        if ($stateRequired == true) {
                            echo '<span style="color:red;">State Name is required*</span>';
                        }
                        ?>
                    </div>


                </div>


                <button type="submit" class="bg-dark btn-block text-center rounded-pill text-white mt-4">
                    <h5>CREATE ACCOUNT</h5>
                </button>
                <p class="text-center mt-2 text-secondary">Already have an account?
                    <a href="login_page.php">Login</a>
                </p>

        </form>
    </div>

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