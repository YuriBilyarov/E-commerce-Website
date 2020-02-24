


<?php
    //initialize database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $basketProducts = $db->basketProducts;
    
    //get email for basket identification
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

    //initializing strings to add to later
    $stringNames = "";
    $stringCosts = "";
    $stringQuantities = "";
    $stringIds = "";
    $currNum = 0;
    $maxNum = 10;

    //adding all basket products associated with the given email to arr1
    $arr1 = [];
    $basket = $basketProducts->find(['email' => $email,]);
    foreach ($basket as $b) {
        array_push($arr1, $b);
    }

    //adding all products to arr2, so we can go through them multiple times
    $arr2 = [];
    $products = $db->products->find();
    foreach ($products as $p) {
        array_push($arr2, $p);
    }

    //go through both arrays and check that the max num of displayed items is not reached, as well as whether the ids match
    for ($i = 0; $i < count($arr1); $i++) {
        for ($k = 0; $k < count($arr2); $k++) {
            if ($currNum < $maxNum && !strcmp(trim($arr1[$i]['id']), trim($arr2[$k]['id']))) {
                //need to add values to appropropriate string as well as adding div objects to list 
                $stringIds = $stringIds . $arr2[$k]['id'] . ",";
                $stringNames = $stringNames . $arr2[$k]['name'] . ",";
                $stringCosts = $stringCosts . $arr2[$k]['cost'] . ",";
                $stringQuantities = $stringQuantities . $arr1[$i]['quantity'] . ",";
                echo '<div>';
                echo "<img src=\"uploadedImages/" . $arr2[$k]['img'] . "\"  alt=\"\">";
                echo '<h3>' . $arr2[$k]['name'] . '</h3>';
                echo '<p>' . $arr2[$k]['description'] . '</p>';
                echo '<p>Quantity: ' . $arr1[$i]['quantity'] . '</p>';
                echo '<h2>$' . $arr2[$k]['cost'] . '</h2>';
                echo '<button id="b' . $currNum . '" onclick="removeFromCart(b' . $currNum . ')">Remove</button>';
                echo '</div>';
                $currNum++;
            }
        }
    }

    //return all strings delimeted by -----
    echo '-----';
    echo $stringIds;
    echo '-----';
    echo $stringNames;
    echo '-----';
    echo $stringCosts;
    echo '-----';
    echo $stringQuantities;
?>