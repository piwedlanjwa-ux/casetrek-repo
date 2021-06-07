<?php
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
    
    if(isset($_GET['file_id']))
    {
        $id = mysqli_real_escape_string($_GET["file_id"]);
        $query = mysqli_query("SELECT * FROM `cases` WHERE `file_id`='$id'");
        while($row = mysqli_fetch_assoc($query))
        {
            $fileData = $row['notes'];
        }
        header("content-type: application/pdf");
        echo $fileData;
    }
?>