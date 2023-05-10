<?php

if(isset($_POST["submit"])){
   
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $username = $_POST["userid"];
    $password = $_POST["password"];
    $passrepeat = $_POST["passrepeat"];
    
    require_once 'mysql-connect.php';       
    require_once 'functions.inc.php';
    
    if(emptyInputSignup($firstname, $lastname, $email, $username, $password, $passrepeat)!==false){
        header("location: ../signup.php?error=EmptyInput");
        exit();
    }
    if(InvalidUserid($username)!==false){
        header("location: ../signup.php?error=InvalidUserID");
        exit();
    }
    if(InvalidEmail($email)!==false){
        header("location: ../signup.php?error=InvalidEmail");
        exit();
    }
    if(PasswordMatch($password,$passrepeat)!==false){
        header("location: ../signup.php?error=PasswordsDontMatch");
        exit();
    }
    if(UseridExists($conn, $username, $email)!==false){
        header("location: ../signup.php?error=UserTaken");
        exit();
    }

    createUser($conn, $firstname, $lastname, $email, $username, $password );
}
else {
    header("location: ../signup.php");
}
