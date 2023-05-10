<?php 
include_once 'header.php';
?>
<div class="body-image2">
    <section class="signup-form">
        <form action ="includes/signup.inc.php" method="post">
		
		<table class="signup-form-table">
				<tr><td><h1>Create An Account</h1></tr></td>
				<tr><td><input type="text" name="firstname" placeholder="First Name"></td></tr>
				<tr><td><input type="text" name="lastname" placeholder="Last Name"></td></tr>
				<tr><td><input type="text" name="email" placeholder="E-mail"></td></tr>
            <!-- Birthday Drop Down
  
            <label >birthday : </label>           
            <SELECT id ="year" name = "yyyy" placeholder="Year" onchange="change_year(this)"></SELECT>
            <SELECT  id ="month" name = "mm" placeholder="Month" onchange="change_month(this)"></SELECT>
            <SELECT id ="day" name = "dd" placeholder="Day"></SELECT>
            <br>
            
            -->
				<tr><td><input type="text" name="userid" placeholder="Username"></td></tr>
				<tr><td><input type="password" name="password" placeholder="Password"></td></tr>
				<tr><td><input type="password" name="passrepeat" placeholder="Confirm Password"></td></tr>
				<tr><td><button type="submit" name="submit">Sign up</button>
		</table>		
        </form>

<?php
    if(isset($_GET["error"])){
        if($_GET["error"] == "EmptyInput") {
            echo "<p>Fill in all the fields!</p>";
        }
        else if($_GET["error"] == "InvalidUserIDt") {
            echo "<p>Choose a proper Username!</p>";
        } 
        else if($_GET["error"] == "InvalidEmail") {
            echo "<p>Choose a proper E-mail!</p>";
        } 
        else if($_GET["error"] == "PasswordsDontMatch") {
            echo "<p>The Passwords don't match!</p>";
        } 
        else if($_GET["error"] == "UserTaken") {
            echo "<p>Username already taken!</p>";
        }
        else if($_GET["error"] == "stmtfailed") {
            echo "<p>Something went wrong!</p>";
        } 
        else if($_GET["error"] == "none") {
            echo "<p>You successfully created an account!</p>";
        } 
    }

?>
        
    </section>
</div>

<?php 
include_once 'footer.php';
?>
