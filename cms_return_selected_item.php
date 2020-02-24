<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $products = $db->products;

    //Retreive the id from the POST request
    $id = filter_input(INPUT_POST, 'selectedId', FILTER_SANITIZE_STRING);
    
    $cursor = $products->find();

    $var = 0;

    //Loop through the products and if an the item exists display it using HTML strings
    foreach($cursor as $prod){
        if($prod['id'] == $id){
        $var = 1;
        echo "<div>";
        echo "<img src=\"uploadedImages/" . $prod['img'] . "\"  alt=\"\">";
        echo "<h3>" . $prod['name'] . "</h3>";
        echo "<p>" . $prod['description'] . "</p>";
        echo "<h2>£" . $prod['cost'] . "</h2>";
        echo "<p>Available Quantity: " . $prod['stkCount'] . "</p>";
        echo "</div>";
        }
    }

    //If the item doesn't exist print appropriate feedback
    if($var == 0){
        echo "Item doesn't exist ";
        echo $id;
    }


?>