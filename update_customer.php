
<?php
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $customers = $db->customers;

    //filter all input for customer updating
    $fName = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $emailc = filter_input(INPUT_POST, 'emailc', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    $passc = filter_input(INPUT_POST, 'passc', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);
    $ph = filter_input(INPUT_POST, 'ph', FILTER_SANITIZE_STRING);

    //basic checking for password matching and email matching
    if ($pass != $passc) {
        echo 'Passwords do not match!';
    } else if ($email != $emailc) {
        echo 'Emails do not match!';
    } else {
        //we know that email already exists so we dont need to check whether or not its a duplicate
        $filter = ['email' => $email,];
        $dArray = [
            "fName" => $fName, "lName" => $lName, "email" => $email,
            "pass" => $pass, "address" => $address, "postcode" => $postcode, "ph" => $ph,
        ];
        //just update current values in the db
        $insertResult = $customers->updateOne($filter, ['$set' => $dArray]);
        if ($insertResult->getModifiedCount() == 1) {
            echo 'Account updated successfully!';
        } else {
            echo 'Error updating account';
        }
    }
?>