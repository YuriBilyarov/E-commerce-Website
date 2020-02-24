<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    
    //Retreive the name and email from the POST request
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));

    //Replace the underscore in the name string with a space
    $name = str_replace("_"," ",$name);

    //Create arrays to be used
    $orderIdArray = [];
    $displayArray = [];
    $itemsQuantityArray = [];
    $productArray = [];

    //Loop through the the orders and if a given email matches with the one in the order
    //push that order ID and the order date to the $orderIdArray
    $orderCursor = $db->orders->find(['email'=>$email]);
    foreach($orderCursor as $ord){
        array_push($orderIdArray, ["orderId"=>$ord['id'], "orderDate"=>$ord['date']]);
    }

    //Loop through the line products and if an orderId matches the one in the
    //line product push the orderId, itemId and quantity to the $itemsQuantityArray
    //Also push the orderId and date in the $displayArray that will be used in the
    //end to display the past order data
    for($i = 0; $i < sizeof($orderIdArray); $i++){
        $lpCursor = $db->lineProducts->find(['orderId'=>$orderIdArray[$i]['orderId']]);
        $quantity = 0;
        foreach($lpCursor as $lp){
            $quantity += $lp['quantity'];
            array_push($itemsQuantityArray, ["orderId"=>$orderIdArray[$i]['orderId'], "itemId"=>$lp['itemId'], "quantity"=>$lp['quantity']]);
        }
        array_push($displayArray, ["id"=>$orderIdArray[$i]["orderId"], "date"=>$orderIdArray[$i]["orderDate"], "qty"=>$quantity]);   
    }
    

    //Loop through the products using the itemIds from the $itemsQuantityArray
    //If the product matches the itemId then add all the corresponding data from
    //the $itemsQuantityArray and the cost * quantity in the new $productArray
    for($i = 0; $i < sizeof($itemsQuantityArray); $i++){
        $prCursor = $db->products->find(['id'=>$itemsQuantityArray[$i]['itemId']]);
        foreach($prCursor as $pr){
            array_push($productArray,['orderId'=>$itemsQuantityArray[$i]['orderId'],'itemId'=>$itemsQuantityArray[$i]['itemId'], 'quantity'=>$itemsQuantityArray[$i]['quantity'], 'cost'=>$pr['cost'] * $itemsQuantityArray[$i]['quantity']]);
        }
    }

    //Loop through each order, if products exist in that order add up the 
    //total cost of all of them and add it to the display array under
    //the same index as the orderId
    for($i = 0;$i < sizeof($orderIdArray); $i++){
        $cost = 0;
        foreach($productArray as $pr){
            if($orderIdArray[$i]['orderId'] == $pr['orderId']){
                $cost += $pr['cost'];
            }
        }
        array_push($displayArray[$i], ["cost"=>$cost]);
    }
    
    //Loop through the $displayArray and output all the required product
    //data in HTML strings to be displayed on the page
        echo "<h2>" . $name . "</h2>";
    foreach ($displayArray as $display){
        echo "<div class=\"div3\">";
        echo "<h3>" . $display["date"] . " - " . $display["qty"] ." items - £" . $display[0]["cost"] . "</h3>";
        echo "<button class=\"b1\" onclick=setSelectedOrder(\"" . $display['id'] . "\")>Details</button>";
        echo "<button class=\"b2\" onclick=deleteOrder(\"" . $display['id'] ."\")>X</button>";
        echo "</div>";
        
    }
    
?>