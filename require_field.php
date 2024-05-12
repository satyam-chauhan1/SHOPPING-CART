<?php
$firstNameRequired = false;
$lastNameRequired = false;

$phoneNumberRequired = false;
$phoneNumberNumeric = false;
$phoneNumberLength = false;

$passwordRequired = false;
$addressRequired = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty($_POST['firstName'])){
        $firstNameRequired = true;
    }

    if(empty($_POST['lastName'])){
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
    // address required
    if (empty($_POST['address'])) {
        $addressRequired = true;
    }
}
