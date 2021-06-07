<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
        <title>Profile</title>

        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style4.css">

        <!-- Font Awesome JS -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
        <style>
            .card-heading{
                padding: 25px 0px 10px 26px;
            }
            .profile-card{
                border-radius: 8px !important;
                width: 90%;
                margin: 0 auto !important;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
        <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3><a href="dashboard.php"><img src="assets/images/logo.png" style="width: 200px; height: auto;" ></a></h3>
                    <strong><a href="dashboard.php"><img src="assets/images/logo2.png" style="width: 70px; height: auto; margin: auto; padding-bottom: 10px; padding-right:10px;" ></a></strong>
                    <span style="font-size: 13px;">Welcome<br><?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]);?></span>

                </div>

                <ul class="list-unstyled components">
                    <li>
                        <a href="dashboard.php" >
                            <img src="https://img.icons8.com/carbon-copy/30/ffffff/details.png">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#casesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <img src="https://img.icons8.com/wired/30/ffffff/soccer-yellow-card.png">
                            Cases
                        </a>
                        <ul class="collapse list-unstyled" id="casesSubmenu">
                            <li>
                                <a href="ViewCases.php">View Cases</a>
                            </li>
                            <li>
                                <a href="AddCase.php">Add Case</a>
                            </li>
                            <li>
                                <a href="activeCases.php">Active Cases</a>
                            </li>
                        </ul>
                    </li>
                    
                </ul>

            </nav>
            <div id="content">

            <div class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">

                        <img src="https://img.icons8.com/android/24/70a4cd/menu.png">
                    </button>
                    
                
                
                    <ul class=" navbar-right">
                        <li class="nav-item dropdown open show" style="padding-left: 15px;">
                            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="true">
                                <?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]);?>
                                <img src="https://img.icons8.com/android/16/000000/expand-arrow.png">
                            </a>
                            <div class="dropdown-menu dropdown-usermenu pull-right " aria-labelledby="navbarDropdown" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(10px, 21px, 0px);">
                                <a class="dropdown-item" href="profile.php"> Profile</a>
                                <a class="dropdown-item" href="/GitHub/casetrek/logout.php"><img src="https://img.icons8.com/metro/17/000000/exit.png"> Log Out</a>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </div>
            <br><br>
            <div class="container">
                <div class="row">
                    <div class="card card-5 profile-card">
                        <div class="card-heading">
                            <h2 class="title">Profile</h2>
                        </div>
                        <div class="card-body">
                                <form action="editDetails.html">
                                    <div class='table-responsive'>
                                    <table class="table">
                                        
                                        <tr>
                                            <td>Employee Name</td>
                                            <td><?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]);?></td>
                                        </tr>
                                        <tr>
                                            <td>Email Address</td>
                                            <td><?php echo htmlspecialchars($_SESSION["email"]);?></td>
                                        </tr>
                                        <?php
                                            include "DBConnection.php";

                                            $session_email = htmlspecialchars($_SESSION["email"]);

                                            $SQLString = "SELECT * FROM employeestbl WHERE `email`='$session_email'";
                                            $QueryResult = $DBConnect->query($SQLString);
                                            $row = mysqli_fetch_array($QueryResult);

                                        ?>
                                        <tr>
                                            <td>Job Title</td>
                                            <td><?php echo $row['jobTitle'];?></td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td><?php echo $row['department'];?></td>
                                        </tr>
                                    </table>
                                    </div>
                                </form>
                                <button class="btn btn-primary">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- jQuery CDN - Slim version (=without AJAX) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <!-- Popper.JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
                });
            });
        </script>
    </body>
</html>
