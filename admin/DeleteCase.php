<?php
      include("DBConnection.php");
      $id = $_GET["id"];
      $SQLString = "DELETE FROM casestb WHERE case_id=$id";
      $SQLString2 = "DELETE FROM closed_casestb WHERE case_id=$id";
      $QueryResult = $DBConnect->query($SQLString);
      $QueryResult2 = $DBConnect->query($SQLString2);
      if($QueryResult&&$QueryResult2)
      {
          if($DBConnect->affected_rows > 0)
          {
              header("Location: ViewCases.php");
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
