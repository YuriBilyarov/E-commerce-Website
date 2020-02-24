
<?php
    //intialize db
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;

    //get values from client side
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));

    //get all basket associated with current user
    $basketProducts = $db->basketProducts->find(['email' => $email]);
    //convert these products to an array
    $bProducts = [];
    foreach ($basketProducts as $p) {
        array_push($bProducts, $p);
    }
    
    //check whether basket is empty, is so return err message
    if (count($bProducts) == 0) {
        echo 'Cart is empty!';
    } else {

        $orders = $db->orders;
        $id = uniqid();
        //create id to make new order unique
        $result = $orders->insertOne(['id' => $id, 'date' => date("Y/m/d"), 'email' => $email]);
        if ($result->getInsertedCount() != 1) {
            echo 'failed to generate new order';
            return;
        }


        $lineProducts = $db->lineProducts;
        for ($i = 0; $i < count($bProducts); $i++) {
            //insert each of the basket items to lineProducts with a the quantity given
            $lineProducts->insertOne(['orderId' => $id, 'quantity' => $bProducts[$i]['quantity'], 'itemId' => $bProducts[$i]['id']]);
            if ($result->getInsertedCount() != 1) {
                echo 'failed to add item to order';
                return;
            }
        }

        //remove all items from the basket 
        $db->basketProducts->drop(['email' => $email]);
        echo 'Order successfully created!';
    }
    
?>