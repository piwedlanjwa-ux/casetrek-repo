<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', '156.38.171.181');
define('DB_USERNAME', 'pbwdxvam_admin');
define('DB_PASSWORD', 'Piwe#1992');
define('DB_NAME', 'pbwdxvam_casetrek');
 
 
/* Attempt to connect to MySQL database */
$DBConnect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($DBConnect === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if(isset($_POST['submit']))
{
    $pname = rand(1000,100000)."-".$_FILES["notes"]["name"];
    $tname = $_FILES["notes"]["tmp_name"];
    $uploads_dir = "/images";

    //move_uploaded_file($tname, $uploads_dir."/".$pname);

    $sql = "INSERT INTO cases(notes) VALUES('$pname')";

    if(mysqli_query($DBConnect, $sql))
    {
        echo "successfully uploaded";
    }
    else
    {
        echo "error";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>upload test</title>
    </head>
    <body>
        <h1> Upload </h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Upload Case Notes</label>
            <input type="file" name="notes" accept="application/pdf"/>
            <input type="submit" name="submit" value="Upload"/>
        </form><br><br>

        <?php
            $SQLString = "SELECT * FROM `cases`";
            $QueryResult = $DBConnect->query($SQLString);
            $counter = 0;
            if($QueryResult)
            {
                if($QueryResult->num_rows>0)
                {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-hover table-striped table-sm'>";
                    echo "<tr><th></th><th>ID</th><th>File Name</th><th>Action</th></tr>";
                    while($row = $QueryResult->fetch_assoc())
                    {
                        echo "<tr><td></td>";
                        echo "<td>". $row['file_id'] . "</td>";
                        echo "<td>". $row['filename'] . "</td>";
                        echo "<td><a href ='viewfile.php?id= " . $row['file_id'] . "'>View</a></td></tr>";
                    }
                    echo "</table>";
                    echo "</div></div></div></div>";
                }
            }
        ?>
    </body>
</html>