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

    //Loop through the products and if the item exists display the selected data in a form with HTML strings
    foreach($cursor as $prod){
        if($prod['id'] == $id){
        $var = 1;
        echo "<input type=\"hidden\" id=\"productId\" value=\"" . $prod['id'] . "\" required>";
        echo "<input type=\"hidden\" id=\"productImage\" value=\"" . $prod['img'] . "\" required>";
        echo "<input type=\"file\" id=\"productImageUpload\" required>";
        echo "<input type=\"text\" id=\"productName\" value=\"" . $prod['name'] . "\" required>";
        echo "<input type=\"text\" id=\"productDescription\" value=\"" . $prod['description'] . "\" required>";
        echo "<input type=\"text\" id=\"productCost\" value=\"" . $prod['cost'] . "\" required>";
        echo "<input type=\"text\" id=\"stockCount\" value=\"" . $prod['stkCount'] . "\" required>";
        echo "<br>";
        echo "<br>";
        }
        
    }

    //If the item doesn't exist print appropriate feedback
    if($var == 0){
        echo "Item doesn't exist ";
        echo $id;
    }


?>