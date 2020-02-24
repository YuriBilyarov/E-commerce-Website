

<?php 
    //Connect to the database
    function initDB() {
        require __DIR__ . '/vendor/autoload.php';
        $mongoClient = (new MongoDB\Client);
        $db = $mongoClient->ecommerce;
        return $db;
    }
    //Start the session
    session_start();

    //Loads the header for the Client
    function loadHeaderCustomer() {
        //html to display the icon as well as first part of nav bar
        echo "<header> <img src='img/eCom.png' alt=''>
            <nav>
            <a href='index.php'>Home</a>";

        //checking if the user is logged in, if so display appropriate links, other wise just login
        if (array_key_exists('currUser', $_SESSION)) {
            echo "<a href='logout.php'>Logout</a>";
            echo "<a href='accountMenu.php'>Account</a>";       
        } else {
            echo "<a href='login.php'>Sign In</a>";
        }

        //rest of nav bar and end header
        echo "<a href='register.php'>Sign Up</a>
            <a href='cart.php'>Cart</a>
        </nav>
        </header>";
    }

    //Loads the header for the CMS
    function loadHeaderCMS() {
        echo "<header> <img src='img/eCom.png' alt=''>
        <a>CMS</a>
        <nav>
            <a href='cmsHome.php'>Home</a>";

        if (array_key_exists('adminUser', $_SESSION)) {
            echo "<a href='cms_logout.php'>Logout</a>";      
        } else {
            echo "<a href='cmsLogin.php'>Sign In</a>";
        }

        echo "<a href='cmsAddItem.php'>Add Item</a>
            <a href='cmsCustomerOrders.php'>Customer Orders</a>
        </nav>
        </header>";
    }

    //Checks if a CMS user is logged in
    function checkLoginCMS(){
        if (!array_key_exists('adminUser', $_SESSION)){
            header("Location:cmsLogin.php");
            echo "<header> <img src='img/eCom.png' alt=''></header>";
        }
    }

    //Displays a header without links
    function hideLinks(){
        if (!array_key_exists('adminUser', $_SESSION)){
            echo "<header> <img src='img/eCom.png' alt=''></header>";
        } 
    }

?>