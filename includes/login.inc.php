<?php
    
    if(isset($_POST["submit"])){
        
        $username=$_POST["userid"];
        $password=$_POST["password"];
        
    require_once 'mysql-connect.php';       
    require_once 'functions.inc.php';
        
        if(emptyInputLogin($username, $password)!==false){
        header("location: ../login.php?error=EmptyInput");
        exit();
        }
        
        loginUser($conn,$username,$password);
        
    }
    else{
        header("location: ../login.php");
        exit();
        
    }
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

