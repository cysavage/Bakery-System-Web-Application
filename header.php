<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
  <title>VibeGuide</title>
  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.css" rel="stylesheet">
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.js"></script>
  <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
  <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/8f4da38e87.js" crossorigin="anonymous"></script>
  <link href="index.css?version=1532" rel="stylesheet">
   <!--  <link href="index(1).css?version=1" rel="stylesheet"> -->
  <link href="index_mobile.css?version=205" rel="stylesheet">
</head>

<body>
  <div class="box-area">
    <header>
      <a href="index.php" style="display:inline;"><div class="logo-image" style="display:inline;"><img src="images/logo3.png" class="img-fluid"></div></a>
      <nav>
        <?php if (isset($_SESSION["username"])) {
            // Check if the user is an admin 
            if($_SESSION["username"] == 'admin'){  ?> 
                <a href='admin_profile.php'><i class='fa fa-fw fa-user'></i>Admin</a>
                <a href='profile.php'><i class='fa fa-fw fa-user'></i>Profile</a>
                <a href ='includes/logout.inc.php'><i class='fa fa-fw fa-user'></i>Logout</i></a>
            <?php }
            else{ ?>
                <a href='profile.php'><i class='fa fa-fw fa-user'></i>Profile</a>
                <a href ='includes/logout.inc.php'><i class='fa fa-fw fa-user'></i>Logout</i></a>
            <?php }
        } 
        else {  ?>
          <a href='login.php'><i class='fa fa-fw fa-user'></i> Login</a>
          <a href ='signup.php'><i class='fa fa-fw fa-user'></i>Register</i></a>
        <?php } ?>
        <a href='contact.php'><i class="fa fa-fw fa-envelope"></i> Contact</a>
      </nav>
    </header>
 </div> 
  
<!-- <a class="active" href="index.php"><i class="fa-solid fa-house"></i></a> -->