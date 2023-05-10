<?php 

require 'mysql-connect.php';

function resetPassword($selector, $password, $passwordRepeat) {
    // Check if the password fields are not empty
    if(empty($password)){
        return "Please input something into the Password field...";
    }
    
    // Check if the password and repeat password fields match
    if($password != $passwordRepeat){
        return "Passwords don't match!";
    }

    // Select the password reset record with the matching selector and validator
    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector='$selector'";
    $stmt = mysqli_stmt_init($conn);  
    
    if(!mysqli_stmt_prepare($stmt,$sql)){
        return "error";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $selector);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
            

                $tokenEmail = $row['pwdResetEmail'];
                
                // Update the user's password in the users table
                $sql = "UPDATE users SET password=? WHERE email='$tokenEmail'";
                $stmt = mysqli_stmt_init($conn);
    
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    return "error";
                } else {
                    $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ss", $newPwdHash,$tokenEmail);
                    mysqli_stmt_execute($stmt);
                    
                    // Delete the password reset record
                    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                    $stmt = mysqli_stmt_init($conn);
                    
                    if(!mysqli_stmt_prepare($stmt,$sql)){
                        return "error";
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);
                        
                        // Check if email is valid
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                            return "success";
                        } else {
                            return "invalidemail";
                        }
                    }
                }
            
        
    }
}
?>
