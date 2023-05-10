<?php
require_once 'mysql-connect.php';

$targetDir ="location_uploads/";
$fileName = basename($_FILES["file2"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
$allowTypes = array('jpg','png','jpeg','gif');

if (isset($_POST['add_location'])) {
    // Retrieve the form data
    $vibeID = $_POST['vibeID'];
    $locationName = $_POST['locationName'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $zipcode = $_POST['zipcode'];


    if(in_array($fileType, $allowTypes)){ // Upload file to server
        if(move_uploaded_file($_FILES["file2"]["tmp_name"], $targetFilePath)){ // Insert image file name into database
            $query = "INSERT INTO Locations(vibeID, locationName, description, latitude, longitude, zipcode, picture)
                        VALUES ('$vibeID', '$locationName', '$description', '$latitude', '$longitude', '$zipcode', '$fileName')";
            if(mysqli_query($conn, $query)){
                header("location: ../profile.php?error=UploadSuccess");
            }else{
                header("location: ../profile.php?error=UploadFail");
            } 
        }else{
            header("location: ../profile.php?error=UploadError");
        }
    }else{
        header("location: ../profile.php?error=IncorrectFileType");
    }
}

if (isset($_POST['edit_location'])) {
    // Retrieve the form data
    $locationID = $_POST['locationID'];
    $vibeID = $_POST['vibeID'];
    $locationName = $_POST['locationName'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $zipcode = $_POST['zipcode'];

    if(in_array($fileType, $allowTypes)){ // Upload file to server
        if(move_uploaded_file($_FILES["file2"]["tmp_name"], $targetFilePath)){ // Insert image file name into database
            $sql = "UPDATE Locations 
                     SET vibeID='$vibeID', locationName='$locationName', description='$description', latitude='$latitude', longitude='$longitude', zipcode='$zipcode', picture='$fileName'
                     WHERE locationID='$locationID'";
            if(mysqli_query($conn, $sql)){
                header("location: ../profile.php?error=UploadSuccess");
            }else{
                header("location: ../profile.php?error=UploadFail");
            } 
        }else{
            header("location: ../profile.php?error=UploadError");
        }
    }else{
        header("location: ../profile.php?error=IncorrectFileType$fileType");
    }
}



?>
