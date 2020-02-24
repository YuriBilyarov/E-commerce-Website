<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $products = $db->products;
    
    //Check if the item is uploaded from the client
    if (!array_key_exists("imgUpload", $_FILES)){
        echo "File missing 1.";
        return;
    }

    //Check if file has a name/exists
    if ($_FILES["imgUpload"]["name"] == "" || $_FILES["imgUpload"]["name"] == null){
        echo "File missing 2.";
        return;
    }

    //Set a variable to the file name
    $uploadFineName = $_FILES["imgUpload"]["name"];
    
    //Check if the file is an image by checking if it has image size
    if (getimagesize($_FILES["imgUpload"]['tmp_name']) === false){
        echo "File is not an image.";
        return;
    }

    //Set a variable to the image file type
    $imageFileType = pathinfo($uploadFineName, PATHINFO_EXTENSION);

    //Check if and image is in the supported file type
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png"){
        echo "Only JPG, JPEG, PNG files accepted";
        return;
    } 

    //Retreive the output from the POST request
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $cost = filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_STRING);
    $stockCount = filter_input(INPUT_POST, 'stkCount', FILTER_SANITIZE_STRING);

    //Check if a field is empty
    if($name == "" || $description == "" || $cost == "" || $stockCount == ""){
        echo "All fields are required";
        return;
    }

    //Check if a product with the entered name already exists
    $fc = ['name' => $name,];
    $cursor = $products->find($fc);
    $val = 0;
    foreach ($cursor as $cust) {
    $val = 1;
    }
    if ($val == 1){
        echo "Product already exists";
        return;
    }

    //Set a varable to the the target file and the specified upload directory
    $targetFile = "uploadedImages/" .$uploadFineName;

    //Upload the file and display the appropriate feedback
    if (move_uploaded_file($_FILES["imgUpload"]["tmp_name"], $targetFile)){
        echo "The file ". basename($uploadFineName). " has been uploaded successfuly. ";
    } else {
        echo "Error uploading file";
    }
    
    //If a product with the same name doesn't exist  add that product to the database
    if ($val == 0) {
        $idGen = uniqid();
        $dArray = [
            "id" => $idGen, "img" => $uploadFineName, "name" => $name, "description" => $description, "cost" => $cost, "stkCount" => $stockCount,
        ];
        
        //Display appropriate feedback
        $insertResults = $products->insertOne($dArray);
        if ($insertResults->getInsertedCount() == 1) {
            echo 'Product added successfully!';
        } else {
            echo 'Error adding product';
        }
    } 
        
?>