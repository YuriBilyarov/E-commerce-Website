<!DOCTYPE html>
<html>

<?php 
    //display navbar
    include 'common.php';
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Account Options Page</title>

</head>

<body>

    <!-- Header -->
    <?php loadHeaderCustomer(); ?>

    <!-- Account Options Menu -->
    <div class="accountMenu">

        <img src="img/eCom.png" alt="">

        <h5>Choose from one of the following options</h5>
        <h3>Account Options:</h3>

        <button onclick="window.location.href = 'editAccount.php'">Edit Account</button>
        <button onclick="window.location.href = 'pastOrderSelection.php'">View Past Orders</button>

    </div>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>
