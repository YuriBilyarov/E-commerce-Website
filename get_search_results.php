

<?php
    //initialise database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $allProducts = $db->products;

    //get values from input send from client 
    $words = filter_input(INPUT_POST, 'words', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

    //create arrays to keep values and be able to sort them later
    $arrUnsorted = [];
    $alreadyFound = [];
    //only display top 10 found items.
    $maxValue = 50;
    $currAmount = 0;

    //need to have 2 cursors if not ide complains about multiple iterations on 1 cursor
    $allall = $db->products->find();
    $allall2 = $db->products->find();

    //if nothing is sent from search or there are no recommended items, then just get first 10 items found
    if ($words == ',,') {
        foreach ($allall as $m) {
            if ($currAmount < $maxValue) {
                array_push($arrUnsorted, $m);
            }
            $currAmount++;
        }
    } else {
        //otherwise get either recommended or searched words, both are stored in $words here
        $wordList = explode(",", $words);
        foreach ($allall2 as $m) {
            //for each word go through the wordlist and see if either the name or the description contains the searched word
            for ($i = 0; $i < sizeof($wordList); $i++) {
                //the recommended can pass in null words, so we need to check that this word is not null
                if ($wordList[$i] != '') {
                    //check if word has already been added the found list, otherwise add it to unsorted and alreadyfound
                    $exists = 0;
                    for ($k = 0; $k < count($alreadyFound); $k++) {
                        if ($m['id'] === $alreadyFound[$k]) {
                            $exists = 1;
                        }
                    }
                    if ($exists == 0) {
                        //the 2 ifs check for inclusion in the desc and name
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
            
        } 
    }
    
    //type 2 sorts from low to hight
    if ($type == 2) {
        for ($i = 0; $i < sizeof($arrUnsorted); $i++) {
            $minimum = $i;
            for ($k = $i + 1; $k < sizeof($arrUnsorted); $k++) {
                if ($arrUnsorted[$k]['cost'] < $arrUnsorted[$minimum]['cost']) {
                    $minimum = $k;
                }
            }
            $temp = $arrUnsorted[$i];
            $arrUnsorted[$i] = $arrUnsorted[$minimum];
            $arrUnsorted[$minimum] = $temp;
        }
    //type 3 sorts from high to low
    } else if ($type == 3) {
        for ($i = 0; $i < sizeof($arrUnsorted); $i++) {
            $minimum = $i;
            for ($k = $i + 1; $k < sizeof($arrUnsorted); $k++) {
                if ($arrUnsorted[$k]['cost'] > $arrUnsorted[$minimum]['cost']) {
                    $minimum = $k;
                }
            }
            $temp = $arrUnsorted[$i];
            $arrUnsorted[$i] = $arrUnsorted[$minimum];
            $arrUnsorted[$minimum] = $temp;
        }
    }

    //add all ids to string to be sent back and parsed on client side.
    for ($i = 0; $i < sizeof($arrUnsorted); $i++) {
        echo $arrUnsorted[$i]['id'] . ',';
    }

    //need to know when to split the string for doing displaying, putting in list

    echo '-------';

    for ($i = 0; $i < sizeof($arrUnsorted); $i++) {
        //creating a div with all info and button to add to cart
        echo '<div>';
        echo "<img src=\"uploadedImages/" . $arrUnsorted[$i]['img'] . "\"  alt=\"\">";
        echo '<h3>' . $arrUnsorted[$i]['name'] . '</h3>';
        echo '<p>' . $arrUnsorted[$i]['description'] . '</p>';
        echo '<h2>$' . $arrUnsorted[$i]['cost'] . '</h2>';
        echo '<p>Available Quantity: ' . $arrUnsorted[$i]['stkCount'] . '</p>';
        echo '<button onclick="addToCart(q' . $i . ')">Add to cart</button>';
        echo '<label for="quantity">Qty</label>
            <select id="q' . $i . '">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>';
        echo '</div>';
    }
?>