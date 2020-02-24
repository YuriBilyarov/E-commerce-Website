<!DOCTYPE html>
<html>

<?php 
    //for dislaying the nav bar
    include 'common.php';
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Sign Up Page</title>

</head>

<script>
    function register() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                //send data to add customer and alert the response, to notify user that they have created an account
                alert(resp);
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "add_customer.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //sending all data
        request.send("lname=" + document.getElementById("lName").value + 
                    "&fname=" + document.getElementById("fName").value + 
                    "&email=" + document.getElementById("email").value + 
                    "&emailc=" + document.getElementById("emailc").value + 
                    "&pass=" + document.getElementById("pass").value + 
                    "&passc=" + document.getElementById("passc").value + 
                    "&address=" + document.getElementById("address").value + 
                    "&postcode=" + document.getElementById("postcode").value + 
                    "&ph=" + document.getElementById("ph").value
        );
    }
</script>

<body>

    <!-- Header -->
    <?php loadHeaderCustomer(); ?>
    <!-- Sign Up Form -->
    <div id="signUp" class="signUp">

        <img src="img/eCom.png" alt="">

        <h5>Sign Up for and eCom shopping account</h5>

        <form>
            <!--All fields for data input, gathered and sent when button is pressed-->
            <input type="text" id="fName" placeholder="First Name*" required>
            <input type="text" id="lName" placeholder="Last Name*" required>
            <input type="email" id="email" placeholder="Email*" required>
            <input type="email" id="emailc" placeholder="Confirm Email*" required>
            <input type="password" id="pass" placeholder="Password*" required>
            <input type="password" id="passc" placeholder="Confirm Password*" required>
            <input type="address" id="address" placeholder="Address">
            <input type="text" id="postcode" placeholder="Post Code">
            <input type="text" id="ph" placeholder="Phone Number">
            <br>
            <br>
            <!--Trigger register when button is clicked-->
            <button onclick="register(); return false">Sign Up</button>
        </form>
        <p id="message"><p>
    </div>


    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>