<!DOCTYPE html>
<html>

<?php 
    //creating the navbar
    include 'common.php';
?>

<head>
    <link rel="stylesheet" href="style.css">
    <title>Edit Account Page</title>
</head>

<?php
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $customers = $db->customers;
    //intialize db to get current values that the user wants to change
    $cursor = $customers->find(['email' => $_SESSION['currUser'],]);
    foreach ($cursor as $curr) {
        $fn = $curr['fName'];
        $ln = $curr['lName'];
        $e = $curr['email'];
        $p = $curr['pass'];
        $ad = $curr['address'];
        $pc = $curr['postcode'];
        $ph = $curr['ph'];
    }
?>

<script>
    function updateAcc() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("message").innerHTML = resp;
            } else {
                alert("error communicating with server");
            }
        }
        //send request to server with updated values for the user
        request.open("POST", "update_customer.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("lname=" + document.getElementById("myLastName").value + 
                    "&fname=" + document.getElementById("myFirstName").value + 
                    "&email=" + document.getElementById("myEmail").value + 
                    "&emailc=" + document.getElementById("myEmailConf").value + 
                    "&pass=" + document.getElementById("myPasswordReg").value + 
                    "&passc=" + document.getElementById("myPasswordRegConf").value + 
                    "&address=" + document.getElementById("myAddress").value + 
                    "&postcode=" + document.getElementById("myPostCode").value + 
                    "&ph=" + document.getElementById("myPhoneNumber").value
        );
    }
</script>

<body>

    <!-- Header -->
    <?php loadHeaderCustomer(); ?>

    <!-- Edit Account Window -->
    <div class="editAccount">

        <img src="img/eCom.png" alt="">

        <h5>Edit your eCom shopping account</h5>
        <h3>Account Details:</h3>

        <div class="form">

            <form>
                <!--initialize the fields with the current values of the current users attributes-->
                <input type="text" id="myFirstName" value=<?php echo $fn?> required>
                <input type="text" id="myLastName" value=<?php echo $ln?> required>
                <input type="email" id="myEmail" value=<?php echo $e?> required>
                <input type="email" id="myEmailConf" value=<?php echo $e?> required>
                <input type="password" id="myPasswordReg" placeholder="password" required>
                <input type="password" id="myPasswordRegConf" placeholder="confirm password" required>
                <input type="address" id="myAddress" value=<?php echo $ad?>>
                <input type="text" id="myPostCode" value=<?php echo $pc?>>
                <input type="text" id="myPhoneNumber" value=<?php echo $ph?>>
                <br>
                <p id="message"></p>


            </form>
        </div>

        <button onclick="window.location.href = 'accountMenu.php'">Back</button>
        <button onclick="updateAcc(); return false">Save Changes</button>

    </div>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>