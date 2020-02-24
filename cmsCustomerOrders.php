
<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
    //Checks if a user is logged in
    checkLoginCMS();
?>

<script>
    //AJAX POST request that sends the customer search input
    //To retreive the matching customers
    function search() {
            let request = new XMLHttpRequest();
            request.onload = () => {
                if (request.status == 200) {
                    let resp = request.responseText;
                    document.getElementById("userList").innerHTML = resp;
                } else {
                    alert("error communicating with server");
                }
            }
            request.open("POST", "cms_search_customers.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("searchInput=" + document.getElementById("searchBar").value);
    
        }
    //AJAX POST request that sends the name and email of a customer
    //to retreive their past orders
    function getPastOrders(name, email){
        let request = new XMLHttpRequest();
            request.onload = () => {
                if (request.status == 200) {
                    let resp = request.responseText;
                    document.getElementById("userList2").innerHTML = resp;
                } else {
                    alert("error communicating with server");
                }
            }
            request.open("POST", "cms_get_past_orders.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("name=" + name + "&email=" + email);
    }

    //AJAX POST request that sends an orderId to get the order deleted 
    function deleteOrder(orderId){
        let request = new XMLHttpRequest();
            request.onload = () => {
                if (request.status == 200) {
                    let resp = request.responseText;
                    document.getElementById("userList2").innerHTML = resp;
    
                } else {
                    alert("error communicating with server");
                }
            }
            request.open("POST", "cms_delete_past_order.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("orderId=" + orderId);
    }

    //Function that creates a local storage entry of a selected order
    //and takes the user to a different page where they can view each item
    function setSelectedOrder(id){
            localStorage.clear("selectedOrder");
            localStorage.setItem("selectedOrder", id);
            window.location.href = 'cmsSelectedOrder.php';

    }
</script>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Customer Orders</title>

</head>

<body onload=search()>

<!-- Header -->
<?php loadHeaderCMS(); ?>

    <!-- User search bar -->
    <div class="search2">
        <input type="text" id="searchBar" placeholder="Search Users" required>
        <button onclick="search()">Search</button>
    </div>
    <!-- displaying the list of users found from search bar -->
    <div class="userList" id="userList">

    </div>
    <!-- Displaying the list of past orders for selected user from searched list-->
    <div class="userList2" id="userList2">

    </div>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>



</body>

</html>