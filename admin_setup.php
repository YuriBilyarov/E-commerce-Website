<?php
    //Connecting to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $staff = $db->staff;

    //Define admin login details
    $adminEmail = "admin@email.com";
    $adminPass = 1234;

    //Search if admin already exists
    $cursor_0 = $staff->find(['email' => "admin@email.com"]);
    $val_0 = 0;

    foreach($cursor_0 as $admin){
        $val_0 = 1;
    }

    //If admin doesn't exist add new admin to the database
    if ($val_0 == 0) {
            
        $dArray = [
            "email" => $adminEmail, "pass" => $adminPass,
        ];

        $insertResult = $staff->insertOne($dArray);
    }
    ?>