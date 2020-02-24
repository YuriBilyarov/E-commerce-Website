
<?php
    //starting up database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;

    //if we didnt trim we got null characters at the end of the input :/ not sure why that happened
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));

    //get all lineproducts that were related to the order that was given
    $lineProducts = $db->lineProducts->find(['orderId' => $id]);
    //get all products into an array such that we can go through them multiple times, instead
    //of using a cursor which could only be parsed once.
    $products = $db->products->find();

    $productArr = [];
    foreach ($products as $p) {
        array_push($productArr, $p);
    }

    //creating strings to return such that they could be parsed in the client side
    $quantityStr = "";
    $nameStr = "";
    $descStr = "";
    $costStr = "";

    //go through all lineproducts, and finding the the product that matched its id
    //when found values were added to respective strings.
    foreach ($lineProducts as $l) {
        $quantityStr = $quantityStr . $l['quantity'] . ',';
        for ($i = 0; $i < count($productArr); $i++) {
            if ($productArr[$i]['id'] === $l['itemId']) {
                $costStr = $costStr . ($l['quantity'] * $productArr[$i]['cost']) . ',';
                $descStr = $descStr . $productArr[$i]['description'] . '//';
                $nameStr = $nameStr . $productArr[$i]['name']. ',';
            }
        }
    }

    //return all full strings as well as ----- in order to splice them apart 
    echo $nameStr;
    echo '-----';
    echo $descStr;
    echo '-----';
    echo $quantityStr;
    echo '-----';
    echo $costStr;
    echo '-----';
?>