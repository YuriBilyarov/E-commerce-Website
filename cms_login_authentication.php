<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $staff = $db->staff;

    //Start the session
    session_start();

    //Retreive the email and pass from the POST request
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING); 
    
    //Check if the email entered exists
    $cursor_1 = $staff->find(['email' => $email,]);
    $val_1 = 0;
    foreach ($cursor_1 as $st) {
        $val_1 = 1;
        //If entered email exists check if the password entered is correct
        if ($st['pass'] != $pass) {
            echo 'Incorrect password';
            return;
        }
    }
    //If email doesn't exist display appropriate feedback otherwise login the user
    if ($val_1 == 0) {
        echo 'Email not registered';
    } else {
        echo 'You are now logged in!';
        $_SESSION['adminUser'] = $email;
    }
    
?>