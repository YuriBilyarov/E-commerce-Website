<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;

    //Retreive the words input from the search bar
    $words = trim(filter_input(INPUT_POST, 'searchInput', FILTER_SANITIZE_STRING));

    //Create arrays and values for later use
    $arrUnsorted = [];
    $alreadyFound = [];
    $maxValue = 10;
    $currAmount = 0;
    $cursor = $db->customers->find();

    //If the search is empty display all customers
    if($words == ""){
        foreach($cursor as $cust){
            echo "<button class=\"b1\" onclick=getPastOrders(\"" . $cust['fName'] . "_" . $cust['lName'] . "\",\"" . $cust['email'] . "\");><h3>" . $cust['fName'] . " " . $cust['lName'] . "</h3></button>";
        }
    }else{
        //Otherwise go through all customers and check them agains the search criteria of the words
        $wordList = [$words];
        foreach ($cursor as $m) {
            for ($i = 0; $i < sizeof($wordList); $i++) {
                $exists = 0;
                for ($k = 0; $k < count($alreadyFound); $k++) {
                    if ($m['email'] === $alreadyFound[$k]) {
                        $exists = 1;
                    }
                }
                if ($exists == 0) {
                    if (strpos($m['fName'], $wordList[$i]) !== false) {
                        array_push($arrUnsorted, $m);
                        array_push($alreadyFound, $m['email']);
                        $currAmount++;
                    } else if (strpos($m['lName'], $wordList[$i]) !== false) {
                        array_push($arrUnsorted, $m);
                        array_push($alreadyFound, $m['email']);
                        $currAmount++;
                    }
                }
            } 
        }
        //Return HTML strings of the customers that match the search criteria
        for ($i = 0; $i < sizeof($arrUnsorted); $i++) {

                echo "<button class=\"b1\" onclick=getPastOrders(\"" . $arrUnsorted[$i]['fName'] . "_" . $arrUnsorted[$i]['lName'] . "\",\"" . $arrUnsorted[$i]['email'] . "\");><h3>" . $arrUnsorted[$i]['fName'] . " " . $arrUnsorted[$i]['lName'] . "</h3></button>";
        }
    }
?>