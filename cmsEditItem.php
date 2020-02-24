<!DOCTYPE html>
<html>

<?php 
    include 'common.php';
    //Checks if a user is logged in
    checkLoginCMS();
?>

<head>

    <link rel="stylesheet" href="style.css">
    <title>Edit Item</title>

</head>
<script>
    //AJAX POST request that sends an itemId and expects an HTML string to display it
    function displaySelectedItem(){
        let id = localStorage.getItem("selectedItem");
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("itemVis").innerHTML = resp;
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "cms_return_selected_item.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("selectedId=" + id);
    }

    //AJAX POST request that sends an itemId and expects an HTML string to display the input form 
    function fillItemFields(){
        let id = localStorage.getItem("selectedItem");
        let request = new XMLHttpRequest();
        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("itemForm").innerHTML = resp;
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "cms_return_item_data.php");
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send("selectedId=" + id);
    }

    //AJAX POST request that sends the data from the input form that will be used to updete the product
    function editSelectedItem(){
        let request = new XMLHttpRequest();
        let fd = new FormData();
        let inpFile = document.getElementById("productImageUpload");

        for (const file of inpFile.files){
            fd.append("imgUpload", file);
        }    
            fd.append("id", document.getElementById("productId").value);
            fd.append("img", document.getElementById("productImage").value);
            fd.append("name", document.getElementById("productName").value);
            fd.append("description", document.getElementById("productDescription").value);
            fd.append("cost", document.getElementById("productCost").value);
            fd.append("stkCount", document.getElementById("stockCount").value);

        request.onload = () => {
            if (request.status == 200) {
                let resp = request.responseText;
                document.getElementById("feedback").innerHTML = resp;
                displaySelectedItem(),fillItemFields()
            } else {
                alert("error communicating with server");
            }
        }
        request.open("POST", "cms_edit_item.php");
        request.send(fd);

    }

    ////AJAX POST request that sends an productId to get the product deleted 
    function deleteItem(){
        let id = localStorage.getItem("selectedItem");
        let request = new XMLHttpRequest();
            request.onload = () => {
                if (request.status == 200) {
                    let resp = request.responseText;
                    document.getElementById("feedback").innerHTML = resp;
                    if(resp == "Product deleted successfully"){
                        window.location.href = 'cmsHome.php';
                    }
                } else {
                    alert("error communicating with server");
                }
            }
            request.open("POST", "cms_delete_product.php");
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send("productId=" + id);
    }


</script>

<body onload=displaySelectedItem(),fillItemFields()>

<!-- Header -->
<?php loadHeaderCMS(); ?>

    <!-- Edit Item Window-->
    <div class="editItem">

        <img src="img/eCom.png" alt="">

        <h5>Edit shopping items here</h5>

        <br>
        <!-- Visual Representation of the Item-->
        <div id="itemVis" class="itemVisual">

        </div>

        <!-- Edit Item Form-->
        <div class="form">
            <h3>Item Details:</h3>
            <form id="itemForm">
            </form>
        </div>
        <button onclick="editSelectedItem(); return false,displaySelectedItem(); return false,fillItemFields(); return false">Save</button>
        <button onclick="deleteItem();">Delete</button>
        <button onclick="window.location.href = 'cmsHome.php'">Back</button>
        <br>
        <br>
        <div id="feedback"></div>
    
    </div>



    <!-- Footer-->
    <footer>
        <h6>Ecom Website. 2020</h6>
    </footer>

</body>

</html>