<?php
    //Connect to the database
    require __DIR__ . '/vendor/autoload.php';
    $mongoClient = (new MongoDB\Client);
    $db = $mongoClient->ecommerce;
    $products = $db->products;

    //Retreive the item data from the POST request
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $img = filter_input(INPUT_POST, 'img', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $cost = filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_STRING);
    $stkCount = filter_input(INPUT_POST, 'stkCount', FILTER_SANITIZE_STRING);

    //Check if a field is empty
    if($name == "" || $description == "" || $cost == "" || $stkCount == ""){
        echo "All fields are required";
        return;
    }
    
    //Set a variable to the image name that is already used
    $uploadFineName = $img;

    //If a file has been uploaded carry out the appropriate checks and change
    //the  $uploadFileName variable to the newly uploaded image name
    if (array_key_exists("imgUpload", $_FILES)){
        
        //Check if a file exists
        if ($_FILES["imgUpload"]["name"] == "" || $_FILES["imgUpload"]["name"] == null){
            echo "File missing error_2.";
            return;
        }
        
        //Change the variale to use the newly uploaded image name
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
        
        //Set a varable to the the target file and the specified upload directory
        $targetFile = "uploadedImages/" .$uploadFineName;
        
        //Upload the file and display the appropriate feedback
        if (move_uploaded_file($_FILES["imgUpload"]["tmp_name"], $targetFile)){
            echo "The file ". basename($uploadFineName). " has been uploaded successfuly. ";
        } else {
            echo "Error uploading file";
        }
    }
   
    //Set a variable to store the replace criteria for the item
    $replaceCriteria = ['id' => $id];

    //Create and array that stores all the data needed for the replace
    $dArray = [
        "id" => $id, "img" => $uploadFineName, "name" => $name, "description" => $description, "cost" => $cost, "stkCount" => $stkCount
    ];

    //Replace the data of the item that matches the id with the data in $dArray
    $replaceResult = $products->replaceOne($replaceCriteria, $dArray);

    //Display appropriate feedback
    if ($replaceResult->getModifiedCount() == 1) {
        echo 'Account updated successfully!';
    } else {
        echo 'Error updating account ';
    }
?>