<!DOCTYPE html>
<html>

<?php 
    //display navbar
    include 'common.php';
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Sign In Page</title>

</head>

<script>
    function signIn() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                alert(resp);
            } else {
                alert("error communicating with server");
            }
        }
        //send accross email and password to server, to get a response for whether user exists
        request.open("POST", "login_authentication.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("email=" + document.getElementById("email").value + 
                     "&pass=" + document.getElementById("password").value);

    }
</script>

<body>

    <!-- Header -->
    <?php loadHeaderCustomer(); ?>


    <!-- Sign In Form -->
    <div id="signIn" class="signIn">
        <img src="img/eCom.png" alt="">
        <div id="signInForm">
            <h5>Sign In using your eCom account</h5>
            <form>
                <!--Input to get for request sending-->
                <input type="email" id="email" placeholder="Email" required>
                <input type="password" id="password" placeholder="Password" required>
                <button onclick="signIn(); return false">Sign In</button>
            </form>
            <br>
        </div>
    </div>

    <!-- Register Button -->
    <div class="register">
        <a>Don't have an account?</a>
        <button onclick="window.location.href = 'register.php'">Register</button>
    </div>

    <!-- Footer-->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>


</body>

</html>