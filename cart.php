<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
?>

<script type="module">

    //create event handlers for dom objects
    document.getElementById("hidden").onclick = removeFromBasket;
    document.getElementById("body1").onload = getCurrentBasket;
    document.getElementById("hidden2").onclick = addToPurchases2;

    //initialize arrays
    let pArrIds = [];
    let pArrNames = [];
    let pArrCosts = [];
    let pArrQuantities = [];


    function getCurrentBasket() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                //get response into sections
                let respArr = resp.split('-----');
                //add first section to cartItems
                document.getElementById("cartItems").innerHTML = respArr[0];
                //splitting sections into other arrays
                pArrIds = respArr[1].split(",");
                pArrNames = respArr[2].split(",");
                pArrCosts = respArr[3].split(",");
                pArrQuantities = respArr[4].split(",");
                //creating htmlstr to make itemlists innerhtml equal to later
                let HTMLstr = "<h1>Item list:</h1>";
                let totalCost = 0;
                for (let i = 0; i < pArrNames.length - 1; i++) {
                    totalCost = totalCost + pArrCosts[i] * pArrQuantities[i];
                    HTMLstr = HTMLstr + "<p>" + pArrNames[i] + " - " + pArrCosts[i] + " X " + pArrQuantities[i] + "</p>";
                }
                HTMLstr = HTMLstr + "<br><h2>Total: $" + totalCost + "</h2>";
                HTMLstr = HTMLstr + "<button onclick=\"addToPurchases()\">Buy Now</button>";
                document.getElementById("itemList").innerHTML = HTMLstr;
            } else {
                alert("error communicating with server");
            }
        }
        //sending request to server
        request.open("POST", "get_basket.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("email=" + <?php echo '"' . $_SESSION['currUser'] . '"'; ?>);
    }

    function addToPurchases2() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                alert(resp);
                //once request sent need to reload page to show client that the current basket is gone
                location.reload();
            } else {
                alert("error communicating with server");
            }
        }
        //send a request to server that will add all items in the current basket to a past order
        request.open("POST", "add_to_purchases.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("email=" + <?php echo '"' . $_SESSION['currUser'] . '"'; ?>);
    }

    function removeFromBasket() {
        let id = pArrIds[document.getElementById("hidden").value];
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
            } else {
                alert("error communicating with server");
            }
        }
        //send request to remove item with given itemid from current basket
        request.open("POST", "basket_remove_product.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("email=" + <?php echo '"' . $_SESSION['currUser'] . '"'; ?> + "&id=" + id);
        //need to reload page to show item has gone, and prices updated and what not
        location.reload();
    }


</script>

<script>
    function removeFromCart(button) {
        //get the id of the button object that is passed in
        let buttonId = button.id;
        //remove the first character from the string to get the correct id of the item
        buttonId = buttonId.slice(1, buttonId.length);
        //assign the hidden button the the itemid
        hidden.value = buttonId;
        //create an event that will trigger removeFromBasket
        hidden.dispatchEvent(new Event("click"));
    }

    function addToPurchases() {
        //create an event that will trigger addTopurchases2 
        hidden2.dispatchEvent(new Event("click"));
    }
</script>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Cart Page</title>

</head>

<body id="body1">

    <!-- Header -->
    <?php loadHeaderCustomer(); ?>
    <!-- Main Part -->
    <div class="cart">

        <br>
        <h1>Your Cart</h1>

        <div class="cartItems" id="cartItems"> 
        </div>
        <!-- Cart Items List -->
        <div class="list" id="itemList">
        </div>



    </div>
    <button id="hidden" hidden><button>
    <button id="hidden2" hidden><button>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>
</body>

</html>