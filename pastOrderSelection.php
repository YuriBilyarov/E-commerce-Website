<!DOCTYPE html>
<html>

<?php 
    //display navbar and check whether user is logged in
    include 'common.php';
    $session_value=(isset($_SESSION['currUser'])) ? $_SESSION['currUser'] : '';
?>
    
<script type="module">

    //list to store all ids, so we know which order we have requested on other pages
    let orderIds = [];
    //get order when page opens
    document.getElementById("body").onload = getPastOrders;

    function getPastOrders() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                //get all arrays, as well as values in the hidden buttons
                let respArr = resp.split("-----");
                document.getElementById("hidden").value = respArr[0];
                document.getElementById("hidden2").value = respArr[1];
                let dates = respArr[1].split(",");
                let quantities = respArr[2].split(",");
                let costs = respArr[3].split(",");
                //creating our string to later add to a dom object
                let HTMLstr = "";
                for (let i = 0; i < dates.length - 1; i++) {
                    HTMLstr += "<div class='div2'>";
                    HTMLstr += "<h3>" + dates[i] + "</h3>";
                    HTMLstr += "<h3>" + quantities[i] + " items</h3>";
                    HTMLstr += "<h2>$" + costs[i] + "</h2>";
                    HTMLstr += "<button onclick=\"showDetails(" + i + ")\">Details</button>";
                    HTMLstr += "</div>";
                }
                //add string to dom object
                document.getElementById("pastOrders").innerHTML = HTMLstr;
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "get_past_orders.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //send request with current logged in email
        request.send("email=" + '<?php echo $session_value;?>');
    }
</script>

<script>
    function showDetails(orderId) {
        //get all order ids by going through values in hidden button
        let orderIds = document.getElementById("hidden").value.split(",");
        //get specific id that is request by looking into the correct position, passed by the showDetails func
        let currOrder = orderIds[orderId];
        //stringify localstorage objects to be current order
        localStorage['orderId'] = JSON.stringify(currOrder);
        localStorage['orderDate'] = JSON.stringify(document.getElementById("hidden2").value.split(",")[orderId]);
        //go to past order page, so that we can view the details of the order
        location.replace("viewPastOrders.php");
    }
</script>

<!-- Header -->
<head>
    <link rel="stylesheet" href="style.css">
    <title>Account Page</title>

</head>

<body id="body">



    <?php loadHeaderCustomer(); ?>

    <div class="pastOrders">
        <!-- Listing the past orders, as well as data about them-->
        <img src="img/eCom.png" alt="">

        <h5>View your past orders here</h5>
        <h3>Past Orders:</h3>

        <!-- list that we have added our past orders to onload-->
        <div class="orderList2" id="pastOrders">
        </div>

        <button onclick="window.location.href = 'accountMenu.php'">Back</button>

    </div>

    <button id="hidden2" hidden></button>
    <button id="hidden" hidden></button>

    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>