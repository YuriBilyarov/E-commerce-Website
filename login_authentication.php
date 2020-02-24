

<?php
    //connect to mongodb
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $customers = $db->customers;

    //start session for the loggin in user with session variable
    session_start();

    //get email and pass for client side
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING); 

    //narrow down customers by searching for specific email that was given
    $cursor = $customers->find(['email' => $email,]);
    $val = 0;
    foreach ($cursor as $cust) {
        $val = 1;
        //check if password and password for that email match, if they dont give error msg
        if ($cust['pass'] != $pass) {
            echo 'Incorrect password';
            return;
        }
    }
    //error or success messages based on whether email was found, and if password was successfully inputted
    if ($val == 0) {
        echo 'Email not registered';
    } else {
        echo 'You are now logged in!';
        //set session variable to curr email
        $_SESSION['currUser'] = $email;
    }

?>