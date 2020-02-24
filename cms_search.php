
<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $allProducts = $db->products;

    //Retreive the words input from the search bar
    $words = trim(filter_input(INPUT_POST, 'searchInput', FILTER_SANITIZE_STRING));

    //Create arrays and variables for later use
    $arrUnsorted = [];
    $alreadyFound = [];
    $maxValue = 10;
    $currAmount = 0;
    $allall = $db->products->find();

    //If the search is empty display all products
    if($words == ""){
        foreach($allall as $prod){
            echo "<div>";
            echo "<img src=\"uploadedImages/" . $prod['img'] . "\"  alt=\"\">";
            echo "<h3>" . $prod['name'] . "</h3>";
            echo "<p>" . $prod['description'] . "</p>";
            echo "<h2>£" . $prod['cost'] . "</h2>";
            echo "<p>Available Quantity: " . $prod['stkCount'] . "</p>";
            echo "<button onclick=setSelectedItem(\"" . $prod['id'] . "\");>Modify</button>";
            echo "</div>";
        }
    }else{
        //Otherwise go through all products and check them agains the search criteria of the words
        $wordList = [$words];
        foreach ($allall as $m) {
            for ($i = 0; $i < sizeof($wordList); $i++) {
                $exists = 0;
                for ($k = 0; $k < count($alreadyFound); $k++) {
                    if ($m['id'] === $alreadyFound[$k]) {
                        $exists = 1;
                    }
                }
                if ($exists == 0) {
                    if (strpos($m['name'], $wordList[$i]) !== false) {
                        array_push($arrUnsorted, $m);
                        array_push($alreadyFound, $m['id']);
                        $currAmount++;
                    } else if (strpos($m['description'], $wordList[$i]) !== false) {
                        array_push($arrUnsorted, $m);
                        array_push($alreadyFound, $m['id']);
                        $currAmount++;
                    }
                }
            } 
        }
        //Return HTML strings of the products that match the search criteria
        for ($i = 0; $i < sizeof($arrUnsorted); $i++) {
                echo "<div>";
                echo "<img src=\"uploadedImages/" . $arrUnsorted[$i]['img'] . "\"  alt=\"\">";
                echo "<h3>" . $arrUnsorted[$i]['name'] . "</h3>";
                echo "<p>" . $arrUnsorted[$i]['description'] . "</p>";
                echo "<h2>£" . $arrUnsorted[$i]['cost'] . "</h2>";
                echo "<p>Available Quantity: " . $arrUnsorted[$i]['stkCount'] . "</p>";
                echo "<button onclick=setSelectedItem(\"" . $arrUnsorted[$i]['id'] . "\");>Modify</button>";
                echo "</div>";
        }
    }
?>