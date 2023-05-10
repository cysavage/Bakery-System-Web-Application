<?php
include 'header.php';
require 'includes/reset-password.inc.php';
?>
<div class="body-image">
    <div class="loginStyle">
    <section class="signup-form">
        <?php
        $selector = 'cff1ed05e1c0dcf2';
        
        if(empty($selector)){
            echo "Could not validate your request!";
        }else{
            if(ctype_xdigit($selector)){
                if(isset($_POST['reset-password-submit'])){
                    $result = resetPassword($selector, $_POST['pwd'], $_POST['pwd-repeat']);
                    if ($result === true){
                        header("Location: index.php?passwordreset=success");
                        exit();
                    } else {
                        echo $result;
                    }
                }
        ?>
        <form action="" method="post">
            <table class="signup-form-table">
            <tr><td><h2>New Password</h2></td></tr>
            <tr><td><input type="password" name="pwd" placeholder="Enter a new Password"></td></tr>
            <tr><td><input type="password" name="pwd-repeat" placeholder="Repeat new Password"></td></tr>
            <tr><td><button type="submit" name="reset-password-submit"> Reset Password </button></td></tr>
        </form>
        <?php
            }
        }
        ?>
    </section>
    </div>
</div>

<?php
include 'footer.php';
?>