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
$host = $_SERVER['HTTP_HOST'];
$url = $host . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
parse_str($query, $params);

	$s = $params['userid'];
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
	
	
    <div class="body-image6">
        <div class="flexArea">
                <!-- User Info Panel (Let-side flex) -->
                <div class="userInfo">
                <?php while(list($usersID, $firstName, $lastName, $email, $userid,$profilePic, $bio) = mysqli_fetch_row($result)) { ?>
		            <table class="profile-form-table">
                        <img class="profile-img" src="includes/uploads/<?=$profilePic?>">
		            
                        <tr><td>Name: </td><td><?=$firstName?> <?=$lastName?></td></tr>
                        <tr><td>email: </td><td><?=$email?></td></tr>
                        <tr><td>Username: </td><td><?=$userid?></td></tr>
                        <!-- Bio Panel -->    
 
                        <form method="POST">
                            <tr><td colspan="3">
                                <p><?=$bio?></p>
                            </td></tr>
                        </form>    
                           
                    </table>
                <?php } ?>
                <!--Start Friends Section-->
                    <?php
                        $current_user_id = $_SESSION['usersID']; // replace with actual user ID
                        $sql = "SELECT friendsID FROM friends WHERE usersID = $current_user_id";
                        $result = $conn->query($sql);
                        $friend_list = array();
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $friend_list[] = $row["friendsID"];
                            }
                        }
                        
                        // check if new friend is already in the list
                        $new_friend_id = $params['userid']; // replace with actual friend ID
                        $new_friend_name = $params['login']; // replace with actual friend username
                        if ($new_friend_id != $_SESSION['usersID']) {
                            if (in_array($new_friend_id, $friend_list)) {
                                // display "Remove Friend" button
                                echo '<form method="post"><button name="remove_friend" onclick="return confirm(\'Are you sure you want to remove this friend?\')">Remove Friend</button></form>';
                            } else {
                                // display "Add Friend" button
                                echo '<form method="post"><button name="add_friend">Add Friend</button></form>';
                            }
                        }    
                        // handle adding a new friend
                        if (isset($_POST['add_friend'])) {
                            $sql = "INSERT INTO friends (usersID, friendsID) VALUES ($current_user_id, $new_friend_id),($new_friend_id , $current_user_id)";
                            $conn->query($sql);
                            // refresh the page to show the updated friend list
                            header('Location: ../user_profile.php?login='.$new_friend_name.'&userid='.$new_friend_id.'');
                        }
                        
                        // handle removing a friend
                        if (isset($_POST['remove_friend'])) {
                            $sql = "DELETE FROM friends WHERE (usersID = $current_user_id AND friendsID = $new_friend_id) OR (usersID = $new_friend_id AND friendsID = $current_user_id )";
                            $conn->query($sql);
                            // refresh the page to show the updated friend list
                            header('Location: ../user_profile.php?login='.$new_friend_name.'&userid='.$new_friend_id.'');
                        }
    
                        ?>
                        <!--End Friends Section-->
                        
                    </div>
                    
                
                <div class="friend-section">
          <h2>Friends</h2>
          <?php
          // connect to MySQL database and retrieve list of friends
          $current_user_id = $params['userid']; // replace with actual user ID
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
                                <div class="favoriteBox favBox2">
                        <!-- Favorites Panel Display (Right-side flex) -->
                    
                        <?php
                        if (isset($_SESSION["usersID"])) {
                            $usersID = $params['userid'];
                        
                        
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
                                echo "This user has not added any locations to their favorites.";
                            }
                        }
                    ?>
                    
                    <!--End Favorites Panel Display-->
        </div>
    </div>
</div>


<?php 
include_once 'footer.php';
?> 