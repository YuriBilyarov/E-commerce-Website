<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
    //Checks if a user is logged in
    checkLoginCMS();
?>

<script>
    //AJAX POST request that sends an orderId and expects HTML strings of the products in that order
    function getOrderDetails(){
        let orderId = localStorage.getItem("selectedOrder");
        let request = new XMLHttpRequest();
            request.onload = () => {
                if (request.status == 200) {
                    let resp = request.responseText;
                    document.getElementById("orderList").innerHTML = resp;
                } else {
                    alert("error communicating with server");
                }
            }
            request.open("POST", "cms_get_order_details.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("orderId=" + orderId);
    }

</script>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Selected Order</title>

</head>

<body onload=getOrderDetails()>

<!-- Header -->
<?php loadHeaderCMS(); ?>

    <!-- Past Orders Window-->
    <div class="pastOrders">

        <img src="img/eCom.png" alt="">

        <h5>View your order items here</h5>
        <h3>Selected Order:</h3>
        <!-- List of Past Orders -->
        <div class="orderList" id="orderList">
            <br>

        </div>    

        <button onclick="window.location.href = 'cmsCustomerOrders.php'">Back</button>

    </div>
    
    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>