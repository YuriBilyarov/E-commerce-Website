<?php
    //Logout the CMS user and send them to the login page
    session_start();
    unset($_SESSION['adminUser']);
    header("Location: cmsLogin.php");
?>