<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

include_once 'header.php';
include 'includes/mysql-connect.php';
include_once 'config.php';



if (!isset($_SESSION["username"])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit();
}


?>
<?php
	$s = $_SESSION["usersID"];
    $query = "SELECT usersID, firstName, lastName, email, userid, profilePic, bio
                FROM users
                WHERE usersid='$s'";
    $result = mysqli_query($conn, $query);
if (isset($_GET['bio']))         {$bio = $_GET['bio'];} else $bio = NULL;

if(isset($_POST['edit'])){
    $bio = $_POST['bio'];
    $update = "UPDATE users SET bio='$bio'
                WHERE usersid='$s'";
    $result3 = mysqli_query($conn, $update);
}
?>
	
	
    <div class="body-image4">
        <div class="flexArea">
                <!-- User Info Panel (Let-side flex) -->
                <div class="userInfo">
                <?php while(list($usersID, $firstName, $lastName, $email, $userid,$profilePic, $bio) = mysqli_fetch_row($result)) { ?>
		            <table class="profile-form-table">
                        <form action="includes/upload.inc.php" method="post" enctype="multipart/form-data">       
			            <input type="file" name="file" id="upload" hidden><label for="upload"><img class="profile-img" src="includes/uploads/<?=$profilePic?>"></label>
                        <tr><td colspan="2">click to change picture</td></tr>			            
                        <tr><td>Name: </td><td><?=$firstName?> <?=$lastName?></td></tr>
                        <tr><td>email: </td><td><?=$email?></td></tr>
                        <tr><td>Username: </td><td><?=$userid?></td></tr>
                        
                             <?php
                                if(isset($_GET["error"])){
                                    if($_GET["error"] == "UploadSuccess"){
                                        echo '<tr><td colspan="2"><p class ="signupsuccess">Your Image has been uploaded!</p></td></tr>';
                                    }
                                    else if($_GET["error"] == "UploadFail"){
                                        echo '<tr><td colspan="2"><p class ="signupsuccess">Your upload has failed...</p></td></tr>';
                                    }
                                    else if($_GET["error"] == "UploadError"){
                                        echo '<tr><td colspan="2"><p class ="signupsuccess">There was an error D:</p></td></tr>';
                                    }
                                    else if($_GET["error"] == "IncorrectFileType"){
                                        echo '<tr><td colspan="2"><p class ="signupsuccess">Please upload a jpg, png, or gif file type</p></td></tr>';
                                    }
                                    else if($_GET["error"] == "NoFile"){
                                        echo '<tr><td colspan="2"><p class ="signupsuccess">Please select a file to Upload!</p></td></tr>';
                                    }
                                }
                            ?>
                        <!-- Bio Panel -->    
                        
                        <tr><td colspan="2"><button type="submit" name="upload">Upload</button></td></tr></form>    
                        <form method="POST">
                            <tr><td colspan="3">
                                <article class="bioEdit">Bio: <input type="text" class="inputStyle" id="bio" name="bio" value="<?=$bio?>">
                                <button type="submit" name="edit">Edit</button></article>
                            </td></tr>
                        </form>    
                            
                    </table>
                <?php } ?>
                </div>
                
                
                
                
                <div class="friend-section">
          <h2>Friends</h2>
          <?php
          // connect to MySQL database and retrieve list of friends
          $current_user_id = $_SESSION['usersID']; // replace with actual user ID
          $sql = "SELECT friendsID FROM friends 

                    WHERE friends.usersID = $current_user_id";
          $result = $conn->query($sql);
          $friend_list = array();
          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $friend_list[] = $row["friendsID"];
              }
          }

            if (count($friend_list) > 0) {
                echo '<table width=100%>';
                $friend_name_arr = array();
                foreach ($friend_list as $friend_id) {
                    $friend_name_arr[] = "usersID = " . $friend_id;
                }
                $sql = "SELECT usersID,userID, firstName,profilePic FROM users WHERE " . implode(" OR ", $friend_name_arr);
                $result = $conn->query($sql);
                
                $count = 0; // initialize count variable
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $userID = $row['usersID'];
                        $username = $row['userID'];
                        $pp = $row['profilePic'];
                        if ($count % 4 == 0) { // start a new row every 4 items
                            echo '<tr>';
                        }
                        echo "<td><a href='../user_profile.php?login=".$username."&userid=". $userID ."'><img class='profile-img2' src='includes/uploads/".$pp."'></a><p>". $username ."</p></td>";
                        $count++;
                        if ($count % 4 == 0) { // end the row after 4 items
                            echo '</tr>';
                        }
                    }
                }
                echo '</table>';
                                
                            } else {
                                echo 'You have no friends.';
                            }
                          ?>
                          <!-- A suprise mouse-ca-tool for later!
                          <form method="post" action="<php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="text" name="friend_name" placeholder="Enter friend's name">
                            <button type="submit" name="add_friend">Add Friend</button>
                          </form>
                          -->
                          
            </div>  

<!--add new vibe-->
<?php

if(isset($_POST['add_vibe'])) {
    $tempvibename = $_POST['tempvibename'];
    $usersID = $_SESSION['usersID'];
    $sql = "INSERT INTO tempvibes (tempvibename, usersID) VALUES ('$tempvibename', '$usersID')";
    if ($conn->query($sql) === TRUE) {
        echo "Vibe sent for approval";
    } else {
        echo "Error adding vibe: " . $conn->error;
    }

}
?>
<div class="profilemanage">
<h2>Add Vibes</h2>
<br>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="tempvibename">Vibe Name:</label>
    <input type="text" class="inputStyle" name="tempvibename" required>
    <button type="submit" name="add_vibe">Add Vibe</button>
</form>
</div>

<div class="favoriteBox">
   <!-- Favorites Panel (Right-side flex) -->

<?php
if (isset($_SESSION["usersID"])) {
    $usersID = $_SESSION["usersID"];

    // Handle form submission
    if(isset($_POST['delete_favorite'])) {
        $locationID = $_POST['locationID'];

        // Check if the location is already in the user's favorites
        $sql = "SELECT * FROM favorites WHERE usersID = '$usersID' AND locationID = '$locationID'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) == 0) {
        }
        else {
            // Deletes the location to the user's favorites
            $sql = "DELETE FROM favorites WHERE usersID = '$usersID' AND locationID = '$locationID'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $locationName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT locationName FROM Locations WHERE locationID = '$locationID'"))['locationName'];
                echo "$locationName has been removed from favorites";
            } else {
                echo "Error removing location from favorites: " . mysqli_error($conn);
            }
        }
    }

    // Display the drop-down list of locations and "Add to Favorites" button
    //$sql = "SELECT * FROM Locations WHERE locationID NOT IN (SELECT locationID FROM favorites WHERE usersID = '$usersID')";
    
    $sql = "SELECT * FROM favorites JOIN Locations ON favorites.locationID = Locations.locationID WHERE usersID = '$usersID'";
    $result = mysqli_query($conn, $sql);
   
    if(mysqli_num_rows($result) > 0) {
        
        echo "<form method='POST' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
        echo "<select name='locationID' class='profileSelect'>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['locationID'] . "'>" . $row['locationName'] . "</option>";
        }
        echo "</select>";
        echo "<button type='submit' name='delete_favorite'>Delete from Favorites</button>";
        echo "</form>";
    } 

    // Display a table of the user's favorites and their corresponding location names
    $sql = "SELECT favorites.locationID, Locations.locationName, Locations.picture FROM favorites JOIN Locations ON favorites.locationID = Locations.locationID WHERE favorites.usersID = '$usersID'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        echo "<table class='favorites_table'>";
        echo "<tr><th colspan='2'>Favorite Locations</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td><img src='includes/location_uploads/" . $row['picture'] . "'></td>";
            echo "<td>" . $row['locationName'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "You have not added any locations to your favorites.";
    }
}
?>

</div>
<?php
// Add a new location
if (isset($_POST['add_location'])) {
    // Retrieve the form data
    $vibeID = $_POST['vibeID'];
    $locationName = $_POST['locationName'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $zipcode = $_POST['zipcode'];
    
    // Perform the database query to insert the new location
    $sql = "INSERT INTO Locations (vibeID, locationName, description, latitude, longitude, zipcode) VALUES ('$vibeID', '$locationName', '$description', '$latitude', '$longitude', '$zipcode')";
    $conn->query($sql);
}
?>


<!-- Add Vibe location -->
        <div class="profilemanage vibelocation">
        <form action="includes/locationupload.inc.php" method="POST" enctype="multipart/form-data">
            <caption><h2>Add Location</h2></caption>
            <label for="vibeID">Vibe:</label>
            <select name="vibeID" id="vibeID" class="profileSelect">
                <?php
                // Retrieve the list of vibes
                $sql = "SELECT * FROM Vibes";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["vibeID"] . "'>" . $row["vibeName"] . "</option>";
                    }
                }
                ?>
                <br>
            </select>
        
            <table>
            <tr><td>Location Image: </td>
                <td><input type="file" class="inputStyle" name="file2" id="file2"  hidden>
                <label for="file2"><img src="includes/location_uploads/default-placeholder.png" style='width:50px; height:50px;'></label>
            </td></tr>
            <tr><td><label for="locationName">Location Name:</label></td><td>
            <input type="text" class="inputStyle" name="locationName" id="locationName" required></td>
            </tr>
            <tr><td><label for="description">Description:</label></td><td width="750px">
            <input type="text" class="inputStyle" name="description" id="description" required></td>
            </tr>
            <tr><td><label for="latitude">Latitude:</label></td><td width="750px">
            <input type="number" class="inputStyle" step="any" name="latitude" id="latitude" required></td>
            </tr>
            <tr><td><label for="longitude">Longitude:</label></td><td width="750px">
            <input type="number" class="inputStyle" step="any" name="longitude" id="longitude" required></td>
            </tr>
            <tr><td><label for="zipcode">Zipcode:</label></td><td width="750px">
            <input type="text" class="inputStyle" name="zipcode" id="zipcode" required></td>
            </table>
            
            <button type="submit" name="add_location">Add Location</button>
            
        </form>
        </div>
        

          
          
          
          
    </div>
    </div>
    

</div>


<?php 
include_once 'footer.php';
?> 