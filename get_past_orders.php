

<?php
//need to add id to this file as well as name
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;

    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));

    $orders = $db->orders->find(['email' => $email]);
    $lineProducts = $db->lineProducts->find();
    $products = $db->products->find();

    $arr1 = []; //use for orders
    $arr2 = []; //use for lineProducts
    $arr3 = []; //use for products

    $dates = []; //return string for all dates/ also need to sort these by date
    $quantities = []; //return quantites
    $costs = []; //return costs - need to convert the above to string first and parse on client side
    $orderIds = [];

    foreach ($orders as $o) {
        array_push($arr1, $o); //adding all orders associated with email
    }
    foreach ($lineProducts as $l) {
        array_push($arr2, $l); //adding all lineitems
    }
    foreach ($products as $p) {
        array_push($arr3, $p); //adding all products
    }

    //getting all data for item amount, total cost, and order date for customer display
    for ($i = 0; $i < count($arr1); $i++) {
        //date and id will be same no matter what ids are in the order
        array_push($dates, $arr1[$i]['date']);
        array_push($orderIds, $arr1[$i]['id']);
        $totalItems = 0;
        $totalCost = 0;
        //adding up items and costs for each order 
        for ($k = 0; $k < count($arr2); $k++) {
            if ($arr1[$i]['id'] == $arr2[$k]['orderId']) {
                $totalItems += $arr2[$k]['quantity'];
                for ($l = 0; $l < count($arr3); $l++) {
                    if ($arr3[$l]['id'] === $arr2[$k]['itemId']) {
                        $totalCost += $arr2[$k]['quantity'] * $arr3[$l]['cost'];
                    }
                }
            }
        }
        //adding costs and item amount to respective arrays
        array_push($quantities, $totalItems);
        array_push($costs, $totalCost);
    }
    
    // need to sort the array of dates now, while adjusting the other 2 so they are all associated with the same order
    // we use insertion sort as it is very easy to implement

    for ($i = 0; $i < count($dates); $i++) {
        $minimum = $i;
        for ($k = $i + 1; $k < sizeof($dates); $k++) {
            if ($dates[$k] < $dates[$minimum]) {
                $minimum = $k;
            }
        }
        //now need to swap all 3 arrays
        //arr1
        $temp = $dates[$i];
        $dates[$i] = $dates[$minimum];
        $dates[$minimum] = $temp;
        //arr2 
        $temp2 = $quantities[$i];
        $quantities[$i] = $quantities[$minimum];
        $quantities[$minimum] = $temp2;
        //arr3
        $temp3 = $costs[$i];
        $costs[$i] = $costs[$minimum];
        $costs[$minimum] = $temp3;
        //orderIds
        $temp4 = $orderIds[$i];
        $orderIds[$i] = $orderIds[$minimum];
        $orderIds[$minimum] = $temp4;
    }

    //now need to convert to string so that we can return and parse it in js
    $datesStr = "";
    $quantityStr = "";
    $costStr = "";
    $orderIdsString = "";
    for ($i = 0; $i < count($arr1); $i++) {
        //add all necessary items to appropriate strings with , as delimeter
        $orderIdsString = $orderIdsString . $orderIds[$i] . ',';
        $datesStr = $datesStr . $dates[$i] . ',';
        $quantityStr = $quantityStr . $quantities[$i] . ',';
        $costStr = $costStr . $costs[$i] . ',';
    }
    echo $orderIdsString;
    echo '-----';
    echo $datesStr;
    echo '-----';
    echo $quantityStr;
    echo '-----';
    echo $costStr;

?>