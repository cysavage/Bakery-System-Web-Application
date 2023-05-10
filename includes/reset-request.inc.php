<?php
require_once 'functions.inc.php';
if(isset($_POST["reset-request-submit"])){
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);
    //change later
    $url = "https://vibeguide.co/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token); 
    
    $expires = date("U") + 1800;
    
    require 'mysql-connect.php';
            
    $userEmail = $_POST["email"];
    
    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
    $stmt = mysqli_stmt_init($conn);
    
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo "There was an error!";
        exit();
    }else {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }    
    $sql = "INSERT INTO pwdReset(pwdResetEmail,pwdResetSelector,pwdResetToken,pwdResetExpires) VALUES(?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo "There was an error!";
        exit();
    }else {
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }
    //Create User email exists
            
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    /*
    $to = $userEmail;
    $subject = 'Password Reset for VibeGuide';
    
    $message = '<p>We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email.</p>';
    $message .= '<p> Here is your password reset link: </br>';
    $message .= '<a href="'. $url . '">' . $url . '</a></p>';

    $headers = "From: dax.b.shethia@gmail.com <dax.b.shethia@gmail.com>\r\n";
    $headers .= "Reply-to: dax.b.shethia@gmail.com\r\n";
    $headers .= "Content-type: text/html\r\n";
    
    mail($to,$subject,$message,$headers);
    */
    $to = $userEmail; // Replace with your email address - **Note create a vibe guide admin email
    $subject = 'Password Reset for VibeGuide';    
    $message = 'We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email.';
    $message .= 'Here is your password reset link:';
    $message .=  '
    <a href="'. $url . '">' . $url . '</a></p>';
    
   
    $headers = "From: dax.b.shethia@gmail.com";
    if (mail($to, $subject, $message, $headers)) {
        echo "success"; // Return success message to JavaScript
    } else {
        echo "error"; // Return error message to JavaScript
    }
    
    
    //to use this locally localhost/create-new-password.php?selector=    $selector   &validator=" bin2hex($token);
    
    
    header("Location: ../reset-password.php?reset=success");
    
}else{
    header("Location: ../index.php");
}
