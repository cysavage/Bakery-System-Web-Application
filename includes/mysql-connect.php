        <?php
      
            $mysql_host ='localhost';
            $mysql_user ='u982607796_root';
            $mysql_pass = 'BCS430GroupPROJ!';
            $mysql_db = 'u982607796_vibeguide';
        
        
            $conn = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

        // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . mysqli_connect_error());
            }
                echo "Connected successfully";
        ?>