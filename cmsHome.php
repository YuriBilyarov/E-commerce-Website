<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
    //Checks if a user is logged in
    checkLoginCMS();
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>CMS Home Page</title>

</head>

<script>

    //Function that creates a local storage entry for a selected productId
    //and takes the user to the edit page where they can edit that item
    function setSelectedItem(id){
        localStorage.clear("selectedItem");
        localStorage.setItem("selectedItem", id);
        window.location.href = 'cmsEditItem.php';

    }

    //AJAX POST request that sends the search data from the search bar and 
    //expects HTML strings to display the products that meet the criteria
    function search() {
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("cmsItems").innerHTML = resp;
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "cms_search.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("searchInput=" + document.getElementById("searchBar").value);
   
    }

</script>


<body onload="search()">

    <!-- Header -->
    <?php loadHeaderCMS(); ?>

        <!-- Search Bar -->
    <div class="cmsSearch">
        <input type="text" id="searchBar" placeholder="Search Items" required>
        <button onclick="search()">Search</button>
    </div>

        <!-- Item List-->
    <div class="cmsItems" id="cmsItems">
   
    </div>
    

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>



</body>

</html>