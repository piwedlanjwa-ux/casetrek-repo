<?php
      include("DBConnection.php");
      $id = $_GET["id"];
      $SQLString = "DELETE FROM employeestbl WHERE emp_id=$id";
      $QueryResult = $DBConnect->query($SQLString);
      if($QueryResult)
      {
          if($DBConnect->affected_rows > 0)
          {
              header("Location: ViewEmployees.php");
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
