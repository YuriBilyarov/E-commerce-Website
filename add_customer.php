<?php
    //initialize db
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $customers = $db->customers;

    //get values from client side
    $fName = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $emailc = filter_input(INPUT_POST, 'emailc', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    $passc = filter_input(INPUT_POST, 'passc', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
    $ph = filter_input(INPUT_POST, 'ph', FILTER_SANITIZE_STRING);

    //some basic checks for input
    if ($pass != $passc) {
        echo 'Passwords do not match!';
    } else if ($email != $emailc) {
        echo 'Emails do not match!';
    } else {
        //checking if email already is already in use
        $fc = ['email' => $email,];
        $cursor = $customers->find($fc);
        $val = 0;
        foreach ($cursor as $cust) {
            $val = 1;
        }
        //if not in use then can add normally
        if ($val == 0) {
            $dArray = [
                "fName" => $fName, "lName" => $lName, "email" => $email,
                "pass" => $pass, "address" => $address, "postcode" => $postcode, "ph" => $ph,
            ];
            $insertResult = $customers->insertOne($dArray);
            if ($insertResult->getInsertedCount() == 1) {
                echo 'Account created successfully!';
            } else {
                echo 'Error adding account';
            }
        } else {
            echo 'Email already in use, Sign In instead?';
        }
    }
?>