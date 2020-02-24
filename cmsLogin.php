<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
    //Display a header without any links
    hideLinks();
?>

<?php
    //If a user is logged in change the page to the cmsHome page
    if (array_key_exists('adminUser', $_SESSION)) {
        header("Location: cmsHome.php");
    }
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>CMS Sign In Page</title>

</head>

<script>

    //AJAX GET request that sets up user login details
    function adminSetup(){
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("message").innerHTML = resp;
                
            } else {
                alert("error communicating with server");
            }
        }
        request.open("GET", "admin_setup.php");
        request.send();
    }

    //AJAX POST request that sends the email and password data from the input fields
    //and expects appropriate feedback
    function signIn() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("message").innerHTML = resp;
                if(resp == "You are now logged in!"){
                location.reload();
                }
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "cms_login_authentication.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("email=" + document.getElementById("cmsEmail").value + 
                     "&pass=" + document.getElementById("cmsPassword").value);

    }
    
</script>

<body onload=adminSetup();>

    <p id="message"><p>


    <!-- Sign In Form -->
    <div id="signIn" class="signIn">
        <img src="img/eCom.png" alt="">
        <div id="signInForm">
            <h5>Sign In to the eCom CMS</h5>
            <form>
                <input type="email" id="cmsEmail" placeholder="Email" required>
                <input type="password" id="cmsPassword" placeholder="Password" required>
                <button onclick="signIn(); return false">Login</button>
            </form>
            <br>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>


</body>

</html>