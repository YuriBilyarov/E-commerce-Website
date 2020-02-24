<!DOCTYPE html>
<html>

<?php 
    //display navbar and get session email
    include 'common.php';
    $session_value=(isset($_SESSION['currUser'])) ? $_SESSION['currUser'] : '';
?>

<head>
    <link rel="stylesheet" href="style.css">
    <title>Home Page</title>
</head>

<script type="module">

    //arr contains items' ids to be later used for the buttons
    let arr = [];

    //get recommender from customer_tracking, in order to get keywords for searching
    import {Recommender} from './customer_tracking.js';
    let recommender = new Recommender();

    //setting functions for various objects on the page
    document.getElementById("searchButton").onclick = search;
    document.getElementById("sortList").onchange = keepOptions;
    document.getElementById("body1").onload = getOptions;
    document.getElementById("hidden").onclick = addToBasket;


    function search() {
        //load the recommender in order to get most used words if necessary
        recommender.load();
        //get values from searchbar and sortlist in order to get the correct response from server
        let textUnparsed = document.getElementById("searchBar").value;
        let textParsed = textUnparsed.split(" ");
        let dropdown = document.getElementById("sortList").value;
        if (textUnparsed == "") {
            //need to know the type of search this is, to know whether to sort and if so, how to sort
            if (dropdown == 1) {
                //recommended need to make recommeded words into a string
                sendSearch(1, recommender.getTop3Keywords());
            } else if (dropdown == 2) {
                sendSearch(2, ',,');
            } else if (dropdown == 3) {
                sendSearch(3, ',,');
            } else {
                sendSearch(4, ',,');
            }
        } else {
            if (dropdown == 1) {
                //recommended need to make recommeded words into a string
                sendSearch(1, recommender.getTop3Keywords());
            } else if (dropdown == 2) {
                //low to high
                sendSearch(2, textParsed);
            } else if (dropdown == 3) {
                //high to low
                sendSearch(3, textParsed);
            } else {
                //category
                sendSearch(4, textParsed);
            }
            //once to search has been created, need to add the typed words to the recommender class
            for (let i = 0; i < textParsed.length; i++) {
                recommender.addKeyword(textParsed[i]);
            }
        }
        recommender.save();
    }

    function sendSearch(type, wordList) {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                //split the response data, where arr now holds the ids of all the objects being displayed
                let respArr = resp.split('-------');
                arr = respArr[0].split(",");
                loadProducts(respArr[1]);
            } else {
                alert("error communicating with server");
            }
        }
        //send off request to server
        request.open("POST", "get_search_results.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("words=" + wordList + "&type=" + type);
    }

    function loadProducts(products) {
        //display all items on page when loaded
        document.getElementById("itemList").innerHTML = products;
    }

    function getOptions() {
        //display previously existing searchbar and sortlist, so that if the page is reloaded by the sortlist
        //the values persist to the new page.
        if (localStorage['searchbar'] !== undefined) {
            document.getElementById("searchBar").value = localStorage['searchbar'];
        }
        if (localStorage['option'] !== undefined) {
            document.getElementById("sortList").value = localStorage['option'];
        }
        //on initial page load need to search for items to display top ones
        search();

    }

    function keepOptions() {
        //place pre-existing search and dropdown with options from before reload
        localStorage['searchbar'] = document.getElementById("searchBar").value
        localStorage['option'] = document.getElementById("sortList").value;
        location.reload();
    }

    function addToBasket() {
        let f = "f";
        let t = "t";
        //check if user exists by created variables to match the output value, then checking if they are equal or not
        if (<?php if (array_key_exists('currUser', $_SESSION)) {echo "t";} else {echo "f";} ?> == "t") {
            let request = new XMLHttpRequest(); 
            request.onload = () => {
                if (request.status == 200) {
                    let resp = request.responseText;
                    alert(resp);
                } else {
                    alert("error communicating with server");
                }
            }
            //send request with email as session value
            request.open("POST", "add_to_basket.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("productid=" + arr[document.getElementById("hidden").value] + 
            "&quantity=" + document.getElementById("q" + document.getElementById("hidden").value).value + 
            "&email=" + '<?php echo $session_value;?>');
        } else {
            alert("You must be logged in to add items to basket!");
        }
    }
</script>

<script>
    //changing button value to be the id of the item
    function addToCart(quantityLink) {
        //get the id of the passed in button
        let quantityid = quantityLink.id;
        //slice the id to be just the id, without passed in letter
        quantityid = quantityid.slice(1, quantityid.length);
        //set hidden value
        hidden.value = quantityid;
        //click the hidden button activating addToBasket function
        hidden.dispatchEvent(new Event("click"));
    }
</script>

<body id="body1">


    <!-- Header -->
    <?php loadHeaderCustomer(); ?>
    
    <!-- Search Bar -->
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search Items" required>
        <!--For searching for indivial items or groups of items with similar words-->
        <button id="searchButton">Search</button>
        <label>Sort by</label>
        <!--For selecting how the items are sorted-->
        <select id="sortList">
            <option value="1">Recommended</option>
            <option value="2">Price(low to high)</option>
            <option value="3">Price(high to low)</option>
            <option value="4">Category</option>
        </select>
    </div>
    <!-- Item List -->
    <div id="itemList" class="items">
    </div>

    <button id="hidden" hidden></button>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>
</html>