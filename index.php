<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
date_default_timezone_set('America/New_York');


include_once 'header.php';
include 'includes/mysql-connect.php';

if (isset($_SESSION["username"])) {
    
}    

$query2 = "SELECT vibeName
                FROM Vibes";
$result2 = mysqli_query($conn, $query2);
if (isset($_GET['vibeName']))	{$vibeQuery = $_GET['vibeName'];}		else $vibeQuery = NULL;
if (isset($_POST['category']))  {$category = $_POST['category'];}       else $category = 'All';
if (isset($_POST['locationQuery']))  {$locationQuery = $_POST['locationQuery'];}       else $locationQuery = NULL;

if (isset($_POST['sortBy']))  {$sortBy = $_POST['sortBy'];}       else $sortBy = 'DESC';
if (isset($_POST['abcBy']))  {$abcBy = $_POST['abcBy'];}       else $abcBy = NULL;


if($category == 'All'){ 
	$query = "SELECT l.locationID, l.locationName, l.latitude, l.longitude, l.description, l.zipcode, l.picture, l.rating, l.vibeID, v.vibeName, v.color
	            FROM Locations l INNER JOIN Vibes v ON l.vibeID=v.vibeID
	            WHERE l.locationName LIKE '%$locationQuery%'
	            ORDER BY rating $sortBy";
	$result = mysqli_query($conn, $query);
}
else{
	$query = "SELECT l.locationID, l.locationName, l.latitude, l.longitude, l.description, l.zipcode, l.picture, l.rating, l.vibeID, v.vibeName, v.color
	            FROM Locations l INNER JOIN Vibes v ON l.vibeID=v.vibeID
				WHERE v.vibeName='$category'  AND l.locationName LIKE '%$locationQuery%'
				ORDER BY rating $sortBy";
	$result = mysqli_query($conn, $query);
}
	
    if (isset($_GET['locationID']))         {$locationID = $_GET['locationID'];}            else $locationID = NULL;	
    if (isset($_GET['locationName']))	{$locationName = $_GET['locationName'];}	else $locationName = NULL;
    if (isset($_GET['latitude']))		{$latitude = $_GET['latitude'];}		else $latitude = NULL;
    if (isset($_GET['longitude']))		{$longitude = $_GET['longitude'];}		else $longitude = NULL;
    if (isset($_GET['picture']))	{$locationimg = $_GET['picture'];}
    if (isset($_GET['vibeName']))	{$vibeName = $_GET['vibeName'];}
    if (isset($_GET['description']))	{$description = $_GET['description'];}		else $description = NULL;
    if (isset($_GET['zipcode']))            {$zipcode = $_GET['zipcode'];}                  else $zipcode = NULL;



    // Get comments for selected location
if ($locationID !== NULL) {
    $comments_query = "SELECT * FROM Comments WHERE locationID = $locationID";
    $comments_result = mysqli_query($conn, $comments_query);
    
    
    
}


?>

<div class="content-area">
		
        
        <!-- Search vibe table for Locations. -->	
            <div class="searchDiv">
                <form method="POST" id="filterID">
                    <div> <input type='text' id='user_input' name='locationQuery' onkeyup='table_filter()' 
                            placeholder='Search Vibes' title='Filter results based on vibes'></div>
                            
                    <!-- VibeID Filter Dropdown. -->        
                    
                        <div>
        				<select name="category" class="vibeSelect" >
    						    <option value="All">All</option>
        					<?php while(list($vibeQuery) = mysqli_fetch_row($result2)) { ?>
        						<option value="<?=$vibeQuery?>" name="category"><?=$vibeQuery?></option>
        					<?php } ?></select>
    			        
    			        
    			        
    			        <!-- Sort By Rating Dropdown. -->  
    			        <select name="sortBy" class="vibeSelect" >
        						<option value="DESC" name="sortBy">Highest Rating</option>
        						<option value="ASC" name="sortBy">Lowest Rating</option>
        				</select>
        				
        				
        				<!-- Sort By Alphabetical Order Dropdown.
        				<div><select name="abcBy" class="vibeSelect" >
        						<option value="DESC" name="abcBy">A-Z</option>
        						<option value="ASC" name="abcBy">Z-A</option>
        				</select></div> -->  
    			        <button type="submit" name="filterBtn" onclick='filterID.submit()'>Filter</button></div>
			        </form>
            </div>



        
            <!-- Load the `mapbox-gl-geocoder` plugin. -->	
            <div id="newmap"></div>
                

            <!-- Table for searched Locations. -->
            <div class="leftSide">
            <table id="locationsTable" class="table">
                <?php 
                $voted = false;
                while ($row = mysqli_fetch_assoc($result)) { 
                    $locationID = $row['locationID']; 
                    $locationName = $row['locationName'];
                    $latitude = $row['latitude'];
                    $longitude = $row['longitude'];
                    $description = $row['description'];
                    $zipcode = $row['zipcode'];
                    $rating = $row['rating'];
                    $locationimg = $row['picture'];
                    $vibeName = $row['vibeName'];
                    $vibeColor = $row['color'];
                ?>
                <!--Start Location table query-->
                        <tr id='<?=$locationID?>' class='locationInfo'>
                            <td class="location-img" style="min-width:200px;"><b>
                            
        
                            
                            
                            
                            <!--Left side Box Start-->
                            </b><br><figure><img src="includes/location_uploads/<?=$locationimg?>">
                            <figcaption>
                               <!-- Favorites button --> 

                                <?php 
                                    if (isset($_SESSION["usersID"])) {
                                        $usersID = $_SESSION["usersID"];
                
                                        // Handle form submission
                                        if(isset($_POST['add_favorite']) && $_POST['locationID'] == $locationID) {
                                            // Check if the location is already in the user's favorites
                                            $check_sql = "SELECT * FROM favorites WHERE usersID = '$usersID' AND locationID = '$locationID'";
                                            $check_result = mysqli_query($conn, $check_sql);
                
                                            if(mysqli_num_rows($check_result) > 0) {
                                                echo "<em>".$locationName."has been added to Favorites</em><br>" ;
                                            } else {
                                                // Add the location to the user's favorites
                                                $add_sql = "INSERT INTO favorites (usersID, locationID) VALUES ('$usersID', '$locationID')";
                                                $add_result = mysqli_query($conn, $add_sql);
                                                if ($add_result) {
                                                    echo "$locationName added to favorites!<br>";
                                                } else {
                                                    echo "Error adding location to favorites: " . mysqli_error($conn);
                                                }
                                            }
                                        }
                
                                        // Display the "Add to Favorites" button
                                        $check_sql = "SELECT * FROM favorites WHERE usersID = '$usersID' AND locationID = '$locationID'";
                                        $check_result = mysqli_query($conn, $check_sql);
                
                                        if(mysqli_num_rows($check_result) > 0) {
                                            echo "<form method='POST' action=''>";
                                            echo "<button name='favorited' class='favorited' disabled>&#x2764;</button>";
                                            echo "</form>";
                                        } else {
                                            echo "<form method='POST' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                                            echo "<input type='hidden' name='locationID' value='$locationID'>";
                                            echo "<button type='submit' name='add_favorite' class='add_favorite'>&#x2764;</button>";
                                            echo "</form>";
                                        }
                                    }
                            ?>
                            <!-- Favorites button ends -->
                        
                            <!-- Ratings button starts -->
                            <?php
                                if (isset($_SESSION["usersID"])) {     
                            ?>
                                <form class="rating" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                    <input type="hidden" name="locationID" value="<?=$locationID?>">
                                    <button type="submit" name="upvote" class="fa" value="<?=$locationID?>"><i class="fa fa-thumbs-up"></i></button>
                                    <?php echo $rating; ?>
                                    <button type="submit" name="downvote" value="<?=$locationID?>"><i class="fa fa-thumbs-down"></i></button> 
                                </form> 
                                
                                <?php
                            } 
                            else {
                                echo 'rating: '.$rating . '<br>'; 
                            }
                            

                            if (isset($_SESSION["usersID"])) {
                            $usersID = $_SESSION["usersID"];
                            $check_sql = "SELECT * FROM ratings WHERE locationID = $locationID AND usersID = $usersID";
                            $check_result = mysqli_query($conn, $check_sql);
                            if (mysqli_num_rows($check_result) > 0) {
                                // User has already voted, don't show the voting buttons
                                $voted = true;
                            } else {
                                $voted = false;
                            }
                        }
                        
                        
                                
                                if (isset($_POST['upvote']) && $_POST['upvote'] == $locationID && !$voted) {
                                    // Insert a new row in the rating table
                                    $usersID = $_SESSION["usersID"];
                                    $sql = "INSERT INTO ratings(locationID, usersID, rating) VALUES ($locationID, $usersID, 1)";
                                    mysqli_query($conn, $sql);

                                    // Update the rating for the location in the Locations table
                                    $sql = "UPDATE Locations SET rating = rating + 1 WHERE locationID = $locationID";
                                    mysqli_query($conn, $sql);

                                    $voted = true;
                                }
                                if (isset($_POST['downvote']) && $_POST['downvote'] == $locationID && !$voted) {
                                    $usersID = $_SESSION["usersID"];
                                    $sql = "INSERT INTO ratings(locationID, usersID, rating) VALUES ($locationID, $usersID, -1)";
                                    mysqli_query($conn, $sql);

                                    // Update the rating for the location in the Locations table
                                    $sql = "UPDATE Locations SET rating = rating - 1 WHERE locationID = $locationID";
                                    mysqli_query($conn, $sql);

                                    $voted = true;
                                }
                        ?> 
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                            <?php   
                            if (isset($_SESSION["username"])) {
                            ?>
                             <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                              <input type="hidden" name="locationID" value="<?=$locationID?>">
                              <textarea name="message" placeholder="Add a comment"  style:"padding-right: 150px;"></textarea>
                              <input type="submit" name="submit_comment" value="Post">
                            </form>
                            <?php   
                            }
                            ?>   
                            </figcaption>
                            </figure>
                            </td>
                        <!-- Ratings button end -->    
                            
                            <!--Left side Box End-->
                            
                            <!--Right side Box Start-->
                            
                            <td style='width:100%; height: 350px;'>
                            <h1><?=$locationName?></h1>    
                            <i style='background-color:<?=$vibeColor?>; border-radius:5px; padding: 3px; color:black'>  <?=$vibeName?></i>
                            <!--Check to see if the user Favorited the location-->
                            <i>
                                <?php
                                    if (isset($_SESSION["usersID"])) {
                                        $usersID = $_SESSION["usersID"];
                                        $check_sql = "SELECT * FROM favorites WHERE locationID = $locationID AND usersID = $usersID";
                                        $check_result = mysqli_query($conn, $check_sql);

                                    if (mysqli_num_rows($check_result) > 0) {
                                        // User has already voted, show the appropriate message
                                            echo "<i style='background-color:gold; border-radius:5px; padding: 3px; color:black'>Favorited</i>";
                                                $voted = true;
                                            } else {
                                                $voted = false;
                                        }
                                    }
                                ?>
                            <!--Check to see if the user vibed the location-->
                            <i>
                                <?php
                                    if (isset($_SESSION["usersID"])) {
                                        $usersID = $_SESSION["usersID"];
                                        $check_sql = "SELECT * FROM ratings WHERE locationID = $locationID AND usersID = $usersID";
                                        $check_result = mysqli_query($conn, $check_sql);

                                    if (mysqli_num_rows($check_result) > 0) {
                                        // User has already voted, show the appropriate message
                                            echo "<i style='background-color:#2862bf; border-radius:5px; padding: 3px; color:black'>Vibed</i>";
                                                $voted = true;
                                            } else {
                                                $voted = false;
                                        }
                                    }
                                ?>
                            </i>
    
                            </i><p>"<?=$description?>"</p>
                                

     
</div></p>
                            
    
                     
                        

  
    
    
    
    
    
    
<?php
// Display the Comments
$sql4 = "SELECT comments.usersID, comments.message, comments.date, comments.locationID, comments.commentID, users.userid, users.profilePic 
        FROM comments INNER JOIN users ON comments.usersID=users.usersID
        WHERE comments.locationID = $locationID";
$result4 = mysqli_query($conn, $sql4);


echo "<div id='comments-$locationID' class='comments-container'><table class='cSection'>";
while ($row = mysqli_fetch_assoc($result4)) {
    $IDCheck = $_SESSION['usersID'];
    $userID = $row['usersID'];
    $message = $row['message'];
    $orgDate = $row['date'];
    $username = $row['userid'];
    $pp = $row['profilePic'];
    $newDate = date("M j, Y g:iA", strtotime($orgDate));

    if ($IDCheck == $userID) {
        $redirect = 'profile.php';
    } else {
        $redirect = 'user_profile.php';
    }

    echo "<tr style='border:none; background-color:#555; border-radius:10px; color:white;padding-top:5px;'>";
    echo "<td><a href='../$redirect?login=".$username."&userid=". $userID ."'><img class='profile-img2' src='includes/uploads/".$pp."'></a><p>". $username ."</p></td>";
    echo "<td colspan='2' width='200%'><br>" . $message;
    echo "<br><i style='font-size: .85em;'>" . $newDate . "</i>";
    
    
    // report buttons
    if (isset($_SESSION["username"])) {
    

    echo "<div class='drop-wrapper'>
      <button class='btn' data-target='#dropdown'>&#9873;</button>
      <div class='drop-menu dropright' id='dropdown'>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='comment_id' value='".$row['commentID']."'>";
        echo "<input type='hidden' name='location_id' value='".$row['locationID']."'>";
        echo "<input type='hidden' name='user_id' value='".$row['usersID']."'>";
        
        echo "<button type='submit' name='report_type' value='offensive'>Offensive Content</button>";
        echo "<button type='submit' name='report_type' value='spam'>Spam</button>";
        echo "<button type='submit' name='report_type' value='inappropriate'>Inappropriate Content</button>"; 
    
        echo "</form>";
    
        echo "</div></div></td>";
    echo "</tr>";
    }    
}

echo "</table>";
}
?>
</td>
                    <!-- Ratings button ends -->   
             </tr></table>
        </div>


<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected report type
    $reportType = $_POST['report_type'];

    // Get the comment ID from the form
    $commentID = $_POST['comment_id'];

    // Get the user ID and location ID from the comments table
    

    $commentsQuery = "SELECT usersID, locationID FROM comments WHERE commentID = ?";
    $stmt = $conn->prepare($commentsQuery);
    $stmt->bind_param('s', $commentID);
    $stmt->execute();
    $stmt->bind_result($userID, $locationID);
    $stmt->fetch();
    $stmt->close();

    // Set the report reason based on the selected report type
    switch ($reportType) {
        case 'offensive':
            $reason = 'Offensive content';
            break;
        case 'spam':
            $reason = 'Spam';
            break;
        case 'inappropriate':
            $reason = 'Inappropriate content';
            break;
        default:
            $reason = '';
            break;
    }

    // Insert the report into the database
    $stmt = $conn->prepare("INSERT INTO reports (commentID, reason, date, usersID, locationID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $commentID, $reason, date('Y-m-d'), $userID, $locationID);
    $stmt->execute();
    $stmt->close();

}
?>

  
            <!--Create a Comment Section-->
    


<?php

if (isset($_POST['submit_comment'])) {
    // Get the form data
    $locationID = $_POST['locationID'];
    $message = $_POST['message'];
    $date = new DateTime(); // Create a new DateTime object
    $dateFormatted = $date->format('Y-m-d H:i:s'); // Format the date as a string

    // Insert the comment into the database
    $sql = "INSERT INTO comments (usersID, locationID, message, date) VALUES ('$usersID', '$locationID', '$message', '$dateFormatted')";
    
    if ($conn->query($sql) === TRUE) {
        // Remove the submit_comment input field from the form
        unset($_POST['submit_comment']);
        
        // Redirect to a different URL using a GET request to prevent form resubmission
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "Error adding comment: " . $conn->error;
    }
}

?>

</div>
             
                  
                <!-- End Comments Table -->
                 
                <!-- End Form Table -->
                
                <!--End Comment Section -->
                    

<script>




    function table_filter() {
            var input = document.getElementById("user_input");
            var filter = input.value.toUpperCase();
            var table = document.getElementById("locationsTable");
            var tr = table.getElementsByTagName("tr");
            var td, tdArr, i, j;

            for(i = 0; i < tr.length; i++) {
                    tdArr = tr[i].getElementsByTagName("td");
                    for(j = 0; j < tdArr.length; j++) {
                            td = tdArr[j];
                            if (td) {
                                    if(td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                            tr[i].style.display = "";
                                            break;
                                    } else {
                                            tr[i].style.display = "none";
                                    }
                            }
                    }
            }
    }


<?php
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($category == 'All'){ 
$sql = "SELECT l.latitude, l.longitude, l.locationName, l.picture, v.color, v.vibeName 
        FROM Locations l JOIN Vibes v ON v.vibeID = l.vibeID 
        WHERE l.latitude IS NOT NULL AND l.longitude IS NOT NULL AND l.locationName LIKE '%$locationQuery%'";

}
else{
    $sql = "SELECT l.latitude, l.longitude, l.locationName, l.picture, v.color, v.vibeName 
        FROM Locations l JOIN Vibes v ON v.vibeID = l.vibeID 
        WHERE l.latitude IS NOT NULL AND l.longitude IS NOT NULL AND v.vibeName='$category' AND l.locationName LIKE '%$locationQuery%'";
}



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create an array to store the location data
    $locations = array();
    while($row = $result->fetch_assoc()) {
        // Add the latitude, longitude and locationName to the array
        $location = array(
            'lat' => $row['latitude'], 
            'lng' => $row['longitude'], 
            'name' => $row['locationName'],
            'color' => $row['color'],
            'vibe' => $row['vibeName'],
            'pic' => $row['picture']
        );
        array_push($locations, $location);
    }
}
$conn->close();

?>

document.addEventListener("DOMContentLoaded", function() {
  mapboxgl.accessToken = 'pk.eyJ1IjoiY3lzYXZhZ2UiLCJhIjoiY2xla2NpY2Z4MGpidjN3bnpoc2hub3ZjbyJ9.hO_U__2LAtHISSBt-osFCQ';

  function getUserLocation(callback) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        callback(position.coords.latitude, position.coords.longitude);
      });
    } else {
      // Geolocation not supported
      callback(null, null);
    }
  }

  getUserLocation(function(lat, lng) {
    var userMarker = new mapboxgl.Marker({
      color: 'blue'
    }).setLngLat([lng, lat]).addTo(map);
    map.flyTo({
      center: [lng, lat],
      zoom: 12
    });
  });

  var map = new mapboxgl.Map({
    container: 'newmap',
    style: 'mapbox://styles/mapbox/streets-v12',
    maxBounds: [
      [-74.390144, 40.427784], // Southwest coordinates
      [-71.856225, 41.423031]  // Northeast coordinates
    ]
  });

  // Add the location pins to the map
<?php foreach($locations as $location): ?>
    var marker = new mapboxgl.Marker({
        color: '<?php echo $location['color']; ?>'
    })
        .setLngLat([<?php echo $location['lng']; ?>, <?php echo $location['lat']; ?>])
        .setPopup(new mapboxgl.Popup().setHTML('<?php echo $location['name'] . 
            "  <br><i style=\'padding:3px; border-radius:5px;color:black;background-color:" . $location['color'] .";\'>"  . $location['vibe'] . 
            "</i><br><br><img src=\'includes/location_uploads/" .$location['pic']. "\'>"; ?>'))
.addTo(map);

<?php endforeach; ?>

});










    


        
</script>


</div>

</body>


<?php
include_once 'footer.php';
?>