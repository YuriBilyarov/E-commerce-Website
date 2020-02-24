<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $orders = $db->orders;
    
    //Reterive the order ID from the POST request
    $orderId = (filter_input(INPUT_POST, 'orderId', FILTER_SANITIZE_STRING));
    
    //Delete the order with the specified order ID from the database
    $delResult = $orders->deleteOne(['id' => ($orderId)]);
    
    //Display appropriate feedback
    if ($delResult->getDeletedCount() == 1) {
        echo 'Item deleted successfully';
    } else {
        echo 'Item delete failed';
    }

?>