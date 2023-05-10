<?php 
include_once 'header.php';


?>

<div class="body-image">
        <div class="loginStyle">
        <form action ="includes/login.inc.php" method="post">
			<table class="signup-form-table">
				<tr><td><h1>Log In</h1></tr></td>
				<tr><td><input type="text" name="userid" placeholder="Username/E-mail"></td></tr>
				<tr><td><input type="password" name="password" placeholder="Password"></td></tr>
				<tr><td><button type="submit" name="submit">Log in</button></td></tr>
<!--                                <tr><td><a href ="reset-password.php">Forgot your password?</a></td></tr>
                                <php
                                if(isset($_GET["newpwd"])){
                                    if($_GET["newpwd"] == "passwordupdated"){
                                        echo '<tr><td><p class ="signupsuccess">Your Password has been reset!</p></td></tr>';
                                    }
                                }
                                ?>
-->
			</table>
        </form>
       </div> 
        
        
<?php
    if(isset($_GET["error"])){
        if($_GET["error"] == "EmptyInput") {
            echo "<p>Fill in all the fields!</p>";
        }
        else if($_GET["error"] == "WrongLogin") {
            echo "<p>Incorrect Login Credentials!</p>";
        } 
    }

?>
        
</div>
<?php 
include_once 'footer.php';
?>