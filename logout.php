

<?php
    //resetting the values for which user is logged in
    session_start();
    unset($_SESSION['currUser']);
    header("Location: login.php");
?>