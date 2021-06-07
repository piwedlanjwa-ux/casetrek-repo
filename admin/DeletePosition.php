<?php
      include("DBConnection.php");
      $id = $_GET["id"];
      $SQLString = "DELETE FROM positions WHERE pos_id=$id";
      $QueryResult = $DBConnect->query($SQLString);
      if($QueryResult)
      {
          if($DBConnect->affected_rows > 0)
          {
              header("Location: ViewPositions.php");
          }
          else
          {
              print "delete failed";
          }
      }
      else
      {
          print "Error: " . $DBConnect->error;
      }
?>
