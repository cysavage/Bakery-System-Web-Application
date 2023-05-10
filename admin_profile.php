
<?php 
include_once 'header.php';
include_once 'config.php';
include 'includes/mysql-connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();

}    
if (!($_SESSION["username"] == 'admin')){
    // If the user is not logged in, redirect them to the login page
    header("Location: profile.php");
    exit();
}
?>




<?php 
if(isset($_POST['add_user'])) { // adding a new user
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $username = $_POST['userid'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (firstName, lastName, userid, password) VALUES ('$firstname', '$lastname', '$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=User%20Added');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<?php
if(isset($_POST['delete_user'])) { // deleting an existing user
    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE usersID=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=User%20Deleted');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>
<div class="body-image5">
<h1>Admin Panel</h1>

<!-- Adding a new user form -->

 <div class="master">
<!--
<div class="manage">
<h2>Add User</h2>
<br>
<form method="POST">
    <label for="firstName">First Name:</label>
    <input type="text" name="firstName" required><br>

    <label for="lastName">Last Name:</label>
    <input type="text" name="lastName" required><br>

    <label for="userid">Username:</label>
    <input type="text" name="userid" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit" name="add_user">Add User</button>
</form>
</div>
 -->
<div class="manage">
<h2>Edit User </h2>
<br>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>UsersID:</label>
    <input type="text" name="id" value="<?php echo $user['usersID']; ?>"><br>
    <label>First Name:</label>
    <input type="text" name="firstName" value="<?php echo $user['firstName']; ?>"><br>
    <label>Last Name:</label>
    <input type="text" name="lastName" value="<?php echo $user['lastName']; ?>"><br>
    <label>Username:</label>
    <input type="text" name="username" value="<?php echo $user['userid']; ?>"><br>
    <label>Password:</label>
    <input type="password" name="password" value="<?php echo $user['password']; ?>"><br>
    <button type="submit" name="edit_user">Save Changes</button>
    <br>
    
<?php
if(isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashPass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET firstName='$firstname', lastName='$lastname', userid='$username', password='$hashPass' WHERE usersID=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=User%20Updated');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
</form>




<!-- Displaying all users -->
<div class="userList">
<table class="adminTable">
    <tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Username</th>
        <th>Password</th>
        <th>Delete</th>
    </tr>
    <?php
   $sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["usersID"] . "</td>";
        echo "<td>" . $row["firstName"] . "</td>";
        echo "<td>" . $row["lastName"] . "</td>";
        echo "<td>" . $row["userid"] . "</td>";
        echo "<td>" . $row["password"] . "</td>";
       
        echo "<td><form method='POST'><input type='hidden' name='id' value='" . $row["usersID"] . "'><button type='submit' name='delete_user'>Delete</button></form></td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}
    ?>
</table>
</div>
</div>









<div class="manage">
<h2>Add Vibes</h2>
<br>
<!-- Add a new vibe -->
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="vibeName">Vibe Name:</label>
    <input type="text" name="vibeName" required>
    <label for="color">Color:</label>
    <input type="text" name="color">
    <button type="submit" name="add_vibe">Add Vibe</button><br>
</form>

<?php
if(isset($_POST['add_vibe'])) {
    $vibeName = $_POST['vibeName'];
    $color = $_POST['color'];
    $sql = "SELECT * FROM Vibes WHERE vibeName = '$vibeName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<script>window.location.replace('?message=Vibe%20already%20exists');</script>";
    } else {
        $sql = "INSERT INTO Vibes (vibeName, color) VALUES ('$vibeName', '$color')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.replace('?message=Vibe%20added%20successfully');</script>";
        } else {
            echo "<script>window.location.replace('?message=Error%20adding%20vibe');</script>";
        }
    }
}
?>


</form>
<br>
<!-- Modify a vibe -->
<?php
if(isset($_POST['edit_vibe_vibe'])) {
    $vibeID = $_POST['vibeID'];
    $vibeName = $_POST['vibeName'];
    $color = $_POST['color'];
    $sql = "UPDATE Vibes SET vibeName='$vibeName' WHERE vibeID='$vibeID'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=Vibe%20Edited');</script>";
    } else {
        echo "Error updating vibe: " . $conn->error;
    }
}
?>

<?php
if(isset($_POST['edit_vibe_color'])) {
    $vibeID = $_POST['vibeID'];
    $vibeName = $_POST['vibeName'];
    $color = $_POST['color'];
    $sql = "UPDATE Vibes SET  color='$color' WHERE vibeID='$vibeID'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=Vibe%20Edited');</script>";
    } else {
        echo "Error updating vibe: " . $conn->error;
    }
}
?>


<!-- Delete a vibe -->
<?php
if(isset($_POST['delete_vibe'])) {
    $vibeID = $_POST['vibeID'];
    $sql = "DELETE FROM Vibes WHERE vibeID='$vibeID'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=Vibe%20Deleted');</script>";
    } else {
        echo "Error deleting vibe: " . $conn->error;
    }
}
?>




<!-- Display all vibes in a table -->
<div class="vibeList">
<table class="adminTable">
    <tr>
        <th>VibeID</th>
        <th>Vibe Name</th>
        <th>Color</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php
    $sql = "SELECT * FROM Vibes";
    $result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["vibeID"] . "</td>";
        echo "<td>" . $row["vibeName"] . "</td>";
        echo "<td>
                <form method='POST' action='" . $_SERVER["PHP_SELF"] . "'>
                    <input type='hidden' name='vibeID' value='" . $row["vibeID"] . "'>
                    <input type='text' name='color' value='" . $row["color"] . "'><br>
                    <button type='submit' name='edit_vibe_color'>Save Color</button>
                </form>
              </td>";
        echo "<td>
                <form method='POST' action='" . $_SERVER["PHP_SELF"] . "'>
                    <input type='hidden' name='vibeID' value='" . $row["vibeID"] . "'>
                    <input type='text' name='vibeName' value='" . $row["vibeName"] . "'><br>
                    <button type='submit' name='edit_vibe_vibe'>Save Name</button>
                </form>
              </td>";
       
        echo "<td>
                <form method='POST' action='" . $_SERVER["PHP_SELF"] . "' onsubmit=\"return confirm('Are you sure you want to delete this vibe?');\">
                    <input type='hidden' name='vibeID' value='" . $row["vibeID"] . "'>
                    <button type='submit' name='delete_vibe'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }




    } else {
        echo "<tr><td colspan='5'>0 results</td></tr>";
    }
    ?>
</table>
</div>
</div>


<br>
<!-- Display all Locations in a table -->
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
<?php

// Edit an existing location
if (isset($_POST['edit_location'])) {
    // Retrieve the form data
    $locationID = $_POST['locationID'];
    $vibeID = $_POST['vibeID'];
    $locationName = $_POST['locationName'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $zipcode = $_POST['zipcode'];
    
    // Perform the database query to update the location
    $sql = "UPDATE Locations SET vibeID='$vibeID', locationName='$locationName', description='$description', latitude='$latitude', longitude='$longitude', zipcode='$zipcode' WHERE locationID='$locationID'";
    $conn->query($sql);
}
?>



<?php
// Delete a location
if (isset($_POST['delete_location'])) {
    // Retrieve the form data
    $locationID = $_POST['locationID'];
    
    // Perform the database query to delete the location
//$stmt1 = $conn->prepare("DELETE FROM ratings WHERE locationID=?");
//$stmt1->bind_param("i", $locationID);
//$stmt1->execute();

$stmt2 = $conn->prepare("DELETE FROM Locations WHERE locationID=?");
$stmt2->bind_param("i", $locationID);
$stmt2->execute();

}



?>
<div class="manage">
<div class="control">
<form action="includes/locationupload.inc.php" method="POST" enctype="multipart/form-data">
    <caption><h2>Add Location</h2></caption>
    <label for="vibeID">Vibe:</label>
    <select name="vibeID" id="vibeID">
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
<!--<tr><td width="750px"><input type="file" name="file" id="upload"></td></tr>-->
    <tr><td><input type="file" class="inputStyle" name="file2" id="file2"  for="file2" style="width:220px;"></td></tr>
    <tr><td width="750px"><input type="text" name="locationName" id="locationName" placeholder="Location Name" required></td></tr>
    <tr><td width="750px"><input type="text" name="description" id="description" placeholder="Description" required></td></tr>
    <tr><td width="750px"><input type="number" step="any" name="latitude" id="latitude" placeholder="Latitude" ></td></tr>
    <tr><td width="750px"><input type="number" step="any" name="longitude" id="longitude" placeholder="Longitude" ></td></tr>
    <tr><td width="750px"><input type="text" name="zipcode" id="zipcode" placeholder="Zipcode" required></td></tr>
    </table>
    <button type="submit" name="add_location">Add Location</button>
    
</form>
</div>
<?php
// Get the location ID from the query string
$locationID = $_GET['locationID'];

// Retrieve the location data from the database
$sql = "SELECT * FROM Locations WHERE locationID = '$locationID'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>



<div class="control">
<form action="includes/locationupload.inc.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="locationID" value="<?php echo $row['locationID']; ?>">
    <caption><h2>Edit Location</h2></caption>
   
    <label for="vibeID">Vibe:</label>
    <select name="vibeID" id="vibeID">
        <?php
        // Retrieve the list of vibes
        $sql = "SELECT * FROM Vibes";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($vibeRow = $result->fetch_assoc()) {
                $selected = ($vibeRow['vibeID'] == $row['vibeID']) ? "selected" : "";
                echo "<option value='" . $vibeRow['vibeID'] . "' $selected>" . $vibeRow['vibeName'] . "</option>";
            }
        }
        ?>
    </select>
    <table><tr><td><input type="file" class="inputStyle" name="file2" id="file2"  for="file2" style="width:220px;"></td></tr>
    <tr><td width="750px">
    <input type="number" name="locationID" id="locationID" value="<?php echo $row['locationID']; ?>" placeholder="Location ID" required>
    </td></tr>
    
    <tr><td width="750px">
    <input type="text" name="locationName" id="locationName" value="<?php echo $row['locationName']; ?>" placeholder="Location Name" >
    </td></tr>
    
    <tr><td width="750px">
    <input type="text" name="description" id="description" value="<?php echo $row['description']; ?>" placeholder="Description" >
    </td></tr>
    
    <tr><td width="750px">
    <input type="number" step="any" name="latitude" id="latitude" value="<?php echo $row['latitude']; ?>" placeholder="Latitude" >
    </td></tr>
    
    <tr><td width="750px">    
    <input type="number" step="any" name="longitude" id="longitude" value="<?php echo $row['longitude']; ?>" placeholder="Longitude" >
    </td></tr>
    
    <tr><td width="750px">  
    <input type="text" name="zipcode" id="zipcode" value="<?php echo $row['zipcode']; ?>" placeholder="Zipcode"  >
    </td></tr>

    </table>
    <button type="submit" name="edit_location">Update Location</button>
    
</form>
</div>


<div class="locationList">
    <caption><h2>Locations</h2></caption>
	<table class="adminTable">
    <tr>
        <th>Location ID</th>
        <th>vibe ID</th>
        <th>location Name</th>
        <th>description</th>
        <th>rating</th>
        <th>latitude</th>
        <th>longitude</th>
        <th>zipcode</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    <?php
$sql = "SELECT * FROM Locations";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["locationID"] . "</td>";
        echo "<td>" . $row["vibeID"] . "</td>";
        echo "<td>" . $row["locationName"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["rating"] . "</td>";
        echo "<td>" . $row["latitude"] . "</td>";
        echo "<td>" . $row["longitude"] . "</td>";
        echo "<td>" . $row["zipcode"] . "</td>";
    
        echo "<td><form method='POST' action='" . $_SERVER['PHP_SELF'] . "' onsubmit='return confirm(\"Are you sure you want to delete this location?\")'><input type='hidden' name='locationID' value='" . $row["locationID"] . "'><button type='submit' name='delete_location'>Delete</button></form></td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}



?>
</table>
</div>
</div>



</form>
<br>


    <!---remove report from table
    <?php
if (isset($_POST['removeReport'])) {
  $reportID = $_POST['reportID'];

  $deleteReportSql = "DELETE FROM reports WHERE reportID = '$reportID'";
  $result = $conn->query($deleteReportSql);

  if ($result === TRUE) {
    echo "Report removed successfully.";
  } else {
    echo "Error removing report: " . $conn->error;
  }
}
?>
<?php
if (isset($_POST['deleteComment'])) {
  $commentID = $_POST['commentID'];
  $deleteReportSQL = "DELETE FROM reports WHERE reportID = '$reportID'";
  $deleteCommentSQL = "DELETE FROM comments WHERE commentID = '$commentID'";
  if ($conn->query($deleteReportSQL) === TRUE && $conn->query($deleteCommentSQL) === TRUE) {
    echo "<script>alert('Comment deleted successfully.')</script>";
    echo "<script>window.location.href='yourtable.php';</script>";
  } else {
    echo "<script>alert('Error deleting comment.')</script>";
  }
}
?>

<!-- Display reported comments in a table -->
<div class="manage">
  <caption><h2>Comment Reports</h2></caption>
  <table class="adminTable">
    <tr>
      <th>ReportID</th>
      <th>CommentID</th>
      <th>UserID</th>
      <th>Comment</th>
      <th>Report-Reason</th>
      <th>Date-Reported</th>
      <th>Ignore-Report</th>
      <th>Delete-Comment</th>
    </tr>
    <?php
      $sql = "SELECT r.reportID, c.commentID, c.usersID, c.message, r.reason, r.date
              FROM reports r
              JOIN comments c ON r.commentID = c.commentID";

      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['reportID'] . "</td>";
          echo "<td>" . $row['commentID'] . "</td>";
          echo "<td>" . $row['usersID'] . "</td>";
          echo "<td>" . $row['message'] . "</td>";
          echo "<td>" . $row['reason'] . "</td>";
          echo "<td>" . $row['date'] . "</td>";


 echo "<td><form method='POST' action='" . $_SERVER["PHP_SELF"] . "'>
            <input type='hidden' name='reportID' value='" . $row["reportID"] . "'><button type='submit' name='removeReport'>Ignore</button></form></td>";

          echo "<td><form method='POST' action='" . $_SERVER["PHP_SELF"] . "'>
            <input type='hidden' name='commentID' value='" . $row["commentID"] . "'><button type='submit' name='deleteComment'>Delete</button></form></td>";



          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='8'>No reports found</td></tr>";
      }

?>
  </table>
</div>





<!-- Modify a vibe -->
<?php
if(isset($_POST['edit_vibe'])) {
    $vibeID = $_POST['vibeID'];
    $vibeName = $_POST['vibeName'];
    $sql = "UPDATE Vibes SET vibeName='$vibeName' WHERE vibeID='$vibeID'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.replace('?message=Vibe%20Updated');</script>";
    } else {
        echo "Error updating vibe: " . $conn->error;
    }
}
?>


<!-- Delete a vibe -->
<?php
if(isset($_POST['delete_vibe'])) {
    $tempid = $_POST['tempid'];
    $sql = "DELETE FROM tempvibes WHERE tempid='$tempid'";
    if ($conn->query($sql) === TRUE) {
        echo "echo <script>window.location.replace('?message=Vibe%20Deleted');</script>";
    } else {
        echo "Error deleting vibe: " . $conn->error;
    }
}
?>

<!-- Add vibes from tempvibes table to vibes table -->
<?php
if(isset($_POST['add_from_tempvibes'])) {
    $tempid = $_POST['tempid'];
    $sql = "SELECT * FROM tempvibes WHERE tempid='$tempid'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $vibeName = $row["tempvibename"];
            $sql = "SELECT * FROM Vibes WHERE vibeName = '$vibeName'";
            $result2 = $conn->query($sql);
            if ($result2->num_rows == 0) {
                $sql = "INSERT INTO Vibes (vibeName) VALUES ('$vibeName')";
                $sql2 = "DELETE FROM tempvibes WHERE tempid='$tempid'";
                if ($conn->query($sql) === TRUE) {
                    echo "";
                } else {
                    echo "Error adding vibe: " . $conn->error;
                }
            } else {
                echo "<script>window.location.replace('?message=Vibe%20already%20exists%20in%20the%20database');</script>";
            }
        }
    } else {
        echo "No vibes found";
    }
}
?>
<!-- Display all vibes in a table -->
<div class = "manage">
       <caption><h2>Vibe Recommendations - For Approval</h2></caption>
       <br>
<table class="adminTable">
 
    <br>

    <tr>
        <th>UserID</th>
        <th>TempID</th>
        <th>Vibe Name</th>
        <th>Add Vibe</th>
        <th>Delete</th>
    </tr>
    <?php
    $sql = "SELECT * FROM tempvibes";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["usersID"] . "</td>";
            echo "<td>" . $row["tempid"] . "</td>";
            echo "<td>" . $row["tempvibename"] . "</td>";
            echo "<td><form method='POST' action='" . $_SERVER["PHP_SELF"] . "'>
            <input type='hidden' name='tempid' value='" . $row["tempid"] . "'><button type='submit' name='add_from_tempvibes'>Add</button></form></td>";
            echo "<td><form method='POST' action='" . $_SERVER["PHP_SELF"] . "'><input type='hidden' name='tempid' value='" . $row["tempid"] . "'><button type='submit' name='delete_vibe'>Delete</button></form></td>";
            echo "</tr>";
        }
    } else {
        echo "No vibes found";
    }
    $conn->close();
    ?>
</table>
</div>





    



<?php
$conn->close();
    
?>


<?php 
include_once 'footer.php';
?>
