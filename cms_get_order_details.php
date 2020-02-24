<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    
    //Retreive the order ID from the POST request
    $orderId = trim(filter_input(INPUT_POST, 'orderId', FILTER_SANITIZE_STRING));

    //Create an array to store product details
    $productsArray = [];

    //Loop through the line items in the database and add products + quantity in the $productsArray
    $lpCursor = $db->lineProducts->find(['orderId'=>$orderId]);
    foreach($lpCursor as $lp){
        array_push($productsArray, ["itemId"=>$lp['itemId'], "quantity"=>$lp['quantity']]);
    }

    //Loop through each products in the current order and return the HTML to display it
    for($i = 0; $i < sizeof($productsArray); $i++){
        $prCursor = $db->products->find(['id'=>$productsArray[$i]['itemId']]);
        foreach($prCursor as $pr){
            echo "<div>";
            echo "<img src=\"uploadedImages/" . $pr['img'] . "\"  alt=\"\">";
            echo "<h3>" . $pr['name'] . " X " . $productsArray[$i]['quantity'] . "</h3>";
            echo "<p>" . $pr['description'] . "</p>";
            echo "<h2>£" . $pr['cost'] . "</h2>";
            echo "</div>";
        }
    }
    
?>