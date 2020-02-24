<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
    //Checks if a user is logged in
    checkLoginCMS();
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Add Item</title>

</head>

<script>

    //AJAX POST request that sends the data from the form input fields
    function addItem() {
        let request = new XMLHttpRequest();
        let fd = new FormData();
        let inpFile = document.getElementById("productImageUpload");
        for (const file of inpFile.files){
            fd.append("imgUpload", file);
        }  

        fd.append("name", document.getElementById("productName").value);
        fd.append("description", document.getElementById("productDescription").value);
        fd.append("cost", document.getElementById("productCost").value);
        fd.append("stkCount", document.getElementById("stockCount").value);

        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("message").innerHTML = resp;
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "cms_add_product.php");
        request.send(fd);
    }
    
</script>

<body>

<!-- Header -->
<?php loadHeaderCMS(); ?>

        <!-- Add Item Window-->
        <p id="message"><p>
    <div class="addItem">

        <img src="img/eCom.png" alt="">

        <h5>Add shopping items here</h5>
        <h3>Enter New Item Details:</h3>

        <!-- Add Item Form-->
        <div class="form">

            <form>

                <input type="file" id="productImageUpload">

                <input type="text" id="productName" placeholder="Product Name">

                <input type="text" id="productDescription" placeholder="Description">

                <input type="text" id="productCost" placeholder="Cost">

                <input type="text" id="stockCount" placeholder="Stock Count">

                <br>
                <br>

                
            </form>
        </div>
        <button onclick="addItem()">Add New Item</button>
        <br>
        <br>
        <button onclick="window.location.href = 'cmsHome.php'">Back</button>

    </div>

    <!-- Footer -->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>