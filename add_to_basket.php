

<?php
    //initialize db
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $basketProducts = $db->basketProducts;

    //get values from client side
    $id = filter_input(INPUT_POST, 'productid', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);  

    $matchingProducts = $basketProducts->find(['email' => trim($email),]);

    $exists = 0;

    //go through products from associated 
    foreach ($matchingProducts as $product) {
        //if product id is matching
        if ($product['id'] == trim($id)) {
            $exists = 1;
            //product exists so just need to add to the quantity shown
            $updatedQuantity = $product['quantity'] + $quantity;
            $dArray = [
                'id' => trim($id), 'email' => trim($email), 'quantity' => $updatedQuantity,
            ];
            $result = $db->basketProducts->updateOne(['email' => trim($email), 'id' => trim($id)], ['$set' => $dArray]);
            //return appropriate message to user
            if ($result->getModifiedCount() == 1) {
                echo 'item updated successfully in basket';
            } else {
                echo 'item update failed';
            }
        }
    }

    //need to add instead of update if product is not already in the basket
    if ($exists === 0) {
        $dArray = [
            'id' => trim($id), 'email' => trim($email), 'quantity' => $quantity,
        ];
        $result = $db->basketProducts->insertOne($dArray);
        if ($result->getInsertedCount() == 1) {
            echo 'item added successfully to basket';
        } else {
            echo 'item addition failed';
        }
    }

?>