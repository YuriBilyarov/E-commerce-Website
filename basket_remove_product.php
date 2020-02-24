

<?php
    //intialize database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $basketProducts = $db->basketProducts;

    //get values from client side
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);  

    //if the program has got here it means the id and email must relate to and item so need to check that item exists.
    $delResult = $basketProducts->deleteOne(['id' => trim($id), 'email' => trim($email)]);
    if ($delResult->getDeletedCount() == 1) {
        //just return appropriate message to user
        echo 'item deleted successfully';
    } else {
        echo 'item delete failed';
    }
?>