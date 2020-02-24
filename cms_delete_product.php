<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $products = $db->products;
    
    //Reterive the product ID from the POST request
    $productId = (filter_input(INPUT_POST, 'productId', FILTER_SANITIZE_STRING));
    
    //Delete the product with the specified product ID from the database
    $delResult = $products->deleteOne(['id' => ($productId)]);
    
    //Display appropriate feedback
    if ($delResult->getDeletedCount() == 1) {
        echo 'Product deleted successfully';
    } else {
        echo 'Product delete failed';
    }

?>