<?php
require_once 'mysql-connect.php';
session_start();

$targetDir ="uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
$session= $_SESSION["usersID"];

if(isset($_POST["upload"])&& !empty($_FILES["file"]["name"])){
    $allowTypes = array('jpg','png','jpeg','gif');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            
        // Insert image file name into database
            $query = "UPDATE users "
                    . "SET profilePic = ('".$fileName."') "
                    . "WHERE usersid= '". $session ."'";
            if(mysqli_query($conn, $query)){
                header("location: ../profile.php?error=UploadSuccess");
            }else{
                header("location: ../profile.php?error=UploadFail");
            } 
        }else{
            header("location: ../profile.php?error=UploadError");
        }
    }else{
        header("location: ../profile.php?error=IncorrectFileType");;
    }
}else{
    header("location: ../profile.php?error=NoFile");
}
?>
}