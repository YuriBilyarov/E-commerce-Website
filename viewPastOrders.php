<!DOCTYPE html>
<html>

<?php 
    //get common so we can display search bar 
    include 'common.php';
    //checking if session is set, so that we know whether user is logged in or not
    $session_value=(isset($_SESSION['currUser'])) ? $_SESSION['currUser'] : '';
?>


<script type="module">

    //whenever page loads we just update/get all past orders for the current user.
    document.getElementById("body").onload = getPastOrders;

    function getPastOrders() {
        //get the orderId we are looking for from the page before
        let order = JSON.parse(localStorage['orderId']);
        //displaying current order and date that the order was made
        document.getElementById("orderId").innerHTML = "Past Order ID:" + order;
        document.getElementById("date").innerHTML = "Date:" + JSON.parse(localStorage['orderDate']);
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                //regular up till here
                //get all arrays from server, splice them so that we can loop through and display them.
                let HTMLstr = "<br>";
                let respArr = resp.split("-----");
                let names = respArr[0].split(",");
                let descriptions = respArr[1].split("//");
                let quantities = respArr[2].split(",");
                let costs = respArr[3].split(",");
                //loop through each item and print necessary values
                for (let i = 0; i < names.length - 1; i++) {
                    HTMLstr += "<div>";
                    HTMLstr += "<img src=\"img/levi's.jpg\" alt=\"\">";
                    HTMLstr += "<h3>" + names[i] + " X " + quantities[i]  + "</h3>";
                    HTMLstr += "<p>" + descriptions[i] + "</p>";
                    HTMLstr += "<h2>$" + costs[i] + "</h2>";
                    HTMLstr += "</div>";
                }
                //set DOM object to string we created above
                document.getElementById("orderList").innerHTML = HTMLstr;
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "get_selected_order.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("id=" + order);
    }

</script>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Account Page</title>

</head>

<body id="body">


    <?php loadHeaderCustomer(); ?>

    <!-- Past Orders Window-->
    <div class="pastOrders">

        <img src="img/eCom.png" alt="">
        <h3 id="orderId"></h3>
        <h3 id="date"></h3>
        <!-- List of Past Orders -->
        <div class="orderList" id="orderList">
        </div>     
    
        <button onclick="window.location.href = 'accountMenu.php'">Back</button>

    </div>
    
    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>