<?php
$firstNameRequired = false;
$lastNameRequired = false;

$phoneNumberRequired = false;
$phoneNumberNumeric = false;
$phoneNumberLength = false;

$passwordRequired = false;
$address1Required = false;
$landMarkRequired = false;

$houseNoRequired = false;
$districtRequired = false;

$pinCodeRequired = false;
$pinCodeNumeric = false;
$pinCodeLength = false;

$stateRequired = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['firstName'])) {
        $firstNameRequired = true;
    }

    if (empty($_POST['lastName'])) {
        $lastNameRequired = true;
    }


    if (empty($_POST['phoneNumber'])) {
        $phoneNumberRequired = true;
    } else {
        $phone = $_POST['phoneNumber'];
        if (!ctype_digit($phone)) {
            $phoneNumberNumeric = true;
        } elseif (strlen($phone) !== 10) {
            $phoneNumberLength = true;
        }
    }

    // password required
    if (empty($_POST['password'])) {
        $passwordRequired = true;
    }
    // address1 required
    if (empty($_POST['address1'])) {
        $address1Required = true;
    }

    // landMark required
    if (empty($_POST['landMark'])) {
        $landMarkRequired = true;
    }
    // house NO. required
    if (empty($_POST['houseNo'])) {
        $houseNoRequired = true;
    }

    // district required
    if (empty($_POST['district'])) {
        $districtRequired = true;
    }

    // pinCode required
    if (empty($_POST['pinCode'])) {
        $pinCodeRequired = true;
    } else {
        $phone = $_POST['pinCode'];
        if (!ctype_digit($pin)) {
            $pinCodeNumeric = true;
        } elseif (strlen($pin) !== 6) {
            $pinCodeLength = true;
        }
    }

    // state required
    if (empty($_POST['state'])) {
        $stateRequired = true;
    }
}
