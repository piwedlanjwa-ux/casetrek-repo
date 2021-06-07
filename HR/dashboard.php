<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: /GitHub/casetrek/index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
    <title>Dashboard</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="assets/css/DataTables/DataTables-1.10.20/css/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="assets/css/style4.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>
        .modals {
            position: fixed;
            top: 120px !important;
            right: 0;
            padding: 0px 30px;
            left: 0;
            z-index: 1050;
            display: none;
            overflow: hidden;
            outline: 0;
        }
        
        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            color: black
        }

        
        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        #myBtn{
            background-color: inherit;
            border: none;
            color: white;
            cursor: pointer;
        }
    </style>
    <script>
        window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title:{
                        text: "Simple Line Chart"
                },
                axisY:{
                        includeZero: false
                },
                data: [{        
                        type: "line",
                indexLabelFontSize: 16,
                        dataPoints: [
                                { y: 450 },
                                { y: 414},
                                { y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
                                { y: 460 },
                                { y: 450 },
                                { y: 500 },
                                { y: 480 },
                                { y: 480 },
                                { y: 410 , indexLabel: "\u2193 lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
                                { y: 500 },
                                { y: 480 },
                                { y: 510 }
                        ]
                }]
        });
        chart.render();

        }
    </script>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><a href="dashboard.php"><img src="assets/images/logo.png" style="width: 200px; height: auto;" ></a></h3>
                <strong><a href="dashboard.php"><img src="assets/images/logo2.png" style="width: 70px; height: auto; margin: auto; padding-bottom: 10px; padding-right:10px;" ></a></strong>
                <span style="font-size: 13px;">Welcome<br><?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]); echo "<br>"; echo htmlspecialchars($_SESSION["userType"]);?></span>           
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#homeSubmenu" >
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
                <li>
                    
                    <a href="#employeesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <img src="https://img.icons8.com/ios/30/ffffff/conference-background-selected.png">
                        Employees
                    </a>
                    <ul class="collapse list-unstyled" id="employeesSubmenu">
                        <li>
                            <a href="ViewEmployees.php">View Employees</a>
                        </li>
                        <li>
                            <a href="AddEmployees.php">Add Employees</a>
                        </li>
                    </ul>
                </li>
                <li>
                    
                    <a href="#usersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <img src="https://img.icons8.com/dotty/30/ffffff/add-user-group-woman-man.png">
                        Users
                    </a>
                    <ul class="collapse list-unstyled" id="usersSubmenu">
                        <li>
                            <a href="ViewUsers.php">View Users</a>
                        </li>
                        <li>
                            <a href="AddUser.php">Add User</a>
                        </li>
                        
                    </ul>
                </li>
                
            </ul>

        </nav>

        <!-- Page Content  -->
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
                        <?php
                            include "DBConnection.php";
                            $SQLString = "SELECT * FROM `casestb` ";
                            $QueryResult = $DBConnect->query($SQLString);
                            if($QueryResult)
                            {
                                if($QueryResult->num_rows>0)
                                {
                                    $count = $QueryResult->num_rows;
                                    echo'
                                        <div class="col-xl-3 col-sm-6 mb-3">
                                            <div class="card text-white bg-primary o-hidden h-100">
                                                <div class="card-body">
                                                    <div class="card-body-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                                  </svg>
                                                    </div>
                                                    <div class="mr-5"> '. $count . ' Total Cases</div>
                                                </div>
                                                <a class="card-footer text-white clearfix small z-1" href="ViewCases.php">
                                                    <span class="float-left">View Details</span>
                                                    <span class="float-right">
                                                        <i class="fa fa-angle-right"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>';
                                                        
                                }
                                else    
                                {
                                    echo '<div class="col-xl-3 col-sm-6 mb-3">
                                                <div class="card text-white bg-primary o-hidden h-100">
                                                    <div class="card-body">
                                                        <div class="card-body-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                        <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                                      </svg>
                                                        </div>
                                                        <div class="mr-5"> 0 Cases </div>
                                                    </div>
                                                    <a class="card-footer text-white clearfix small z-1" href="ViewCases.php">
                                                        <span class="float-left">View Details</span>
                                                        <span class="float-right">
                                                            <i class="fa fa-angle-right"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>';
                                }
                            }
                            
                                            
                            $SQLString = "SELECT * FROM `closed_casestb` WHERE status='Open'";
                            $QueryResult = $DBConnect->query($SQLString);
                            if($QueryResult)
                            {
                                if($QueryResult->num_rows>0)
                                {
                                    $count = $QueryResult->num_rows;
                                    echo '
                                            <div class="col-xl-3 col-sm-6 mb-3">
                                                <div class="card text-white bg-warning o-hidden h-100">
                                                    <div class="card-body">
                                                        <div class="card-body-icon">
                                                        <i class="fas fa-folder-open"></i>
                                                        </div>
                                                        <div class="mr-5">'. $count . ' Active Cases</div>
                                                    </div>

                                                    

                                                    <a class="card-footer text-white clearfix small z-1" href="activeCases.php">
                                                        <span class="float-left">View Details</span>
                                                        <span class="float-right">
                                                            <i class="fa fa-angle-right"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>';
                                                        
                                }
                                else
                                {
                                    echo '<div class="col-xl-3 col-sm-6 mb-3">
                                                <div class="card text-white bg-warning o-hidden h-100">
                                                    <div class="card-body">
                                                        <div class="card-body-icon">
                                                        <i class="fas fa-folder-open"></i>
                                                        </div>
                                                        <div class="mr-5">0  Active Cases</div>
                                                    </div>

                                                    

                                                    <a class="card-footer text-white clearfix small z-1" href="Active Cases">
                                                        <span class="float-left">View Details</span>
                                                        <span class="float-right">
                                                            <i class="fa fa-angle-right"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>';
                                }
                            }

                            $SQLString = "SELECT * FROM `closed_casestb` WHERE sanction='Dismissal'";
                            $QueryResult = $DBConnect->query($SQLString);
                            if($QueryResult)
                            {
                                if($QueryResult->num_rows>0)
                                {
                                    $count = $QueryResult->num_rows;
                                    echo '<div class="col-xl-3 col-sm-6 mb-3">
                                            <div class="card text-white bg-success o-hidden h-100">
                                                <div class="card-body">
                                                    <div class="card-body-icon">
                                                    <i class="fas fa-user-minus"></i>
                                                    </div>
                                                    <div class="mr-5">' . $count . ' Dismissals!</div>
                                                </div>
                                                <a class="card-footer text-white clearfix small z-1" href="#">
                                                    <span class="float-left">View Details</span>
                                                    <span class="float-right">
                                                    <button id="myBtn"><i class="fa fa-angle-right"></i></button>
                                                    </span>
                                                </a>
                                                <div id="myModal" class="modal">
                                                    <!-- Modal content -->
                                                    <div class="modal-content">
                                                        <span class="close">&times;</span>
                                                        <div class="table-responsive-sm">
                                                            <table id="example" class="table table-hover table-striped table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <td>Case No.</td>
                                                                        <td>Employee Name</td>
                                                                        <td>Department</td>
                                                                        <td>Job Title</td>
                                                                        <td>Date of Dismissal</td>
                                                                    </tr>
                                                                </thead>';

                                                                while($rows = mysqli_fetch_array($QueryResult))
                                                                {
                                                                    $id=$rows['case_id'];
                                                                    $SQLString2 = "SELECT * FROM `casestb` WHERE case_id=$id";
                                                                    $QueryResult2 = $DBConnect->query($SQLString2);
                                                                    $row = mysqli_fetch_array($QueryResult2);

                                                                    echo '<tr>
                                                                            <td>'. $rows["case_id"] . '</td>
                                                                            <td>'. $row["employee_name"] . '</td>
                                                                            <td>'. $row["department"] . '</td>
                                                                            <td>'. $row["position"] . '</td>
                                                                            <td>'. $rows["date_of_dismissal"] . '</td>
                                                                          </tr>';
                                                                }
                                                                echo '
                                                                </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>'   ;
                                                     
                                }
                                else
                                {
                                    echo '<div class="col-xl-3 col-sm-6 mb-3">
                                            <div class="card text-white bg-success o-hidden h-100">
                                                <div class="card-body">
                                                    <div class="card-body-icon">
                                                    <i class="fas fa-user-minus"></i>
                                                    </div>
                                                    <div class="mr-5">0 Dismissals</div>
                                                </div>
                                                <a class="card-footer text-white clearfix small z-1" href="#">
                                                    <span class="float-left">View Details</span>
                                                    <span class="float-right">
                                                        <i class="fa fa-angle-right"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>';
                                }
                            }     

                            $SQLString = "SELECT * FROM `closed_casestb` WHERE status='Active'";
                            $QueryResult = $DBConnect->query($SQLString);
                            if($QueryResult)
                            {
                                if($QueryResult->num_rows>0)
                                {
                                    $count = $QueryResult->num_rows;
                                    echo '<div class="col-xl-3 col-sm-6 mb-3">
                                            <div class="card text-white bg-danger o-hidden h-100">
                                                <div class="card-body">
                                                    <div class="card-body-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                                    <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2h-7zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48V8.35zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                                                  </svg>
                                                    </div>
                                                    <div class="mr-5">' . $count . ' Active Sanctions!</div>
                                                </div>
                                                <a class="card-footer text-white clearfix small z-1" href="#">
                                                    <span class="float-left">View Details</span>
                                                    <span class="float-right">
                                                        <i class="fa fa-angle-right"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>';
                                                        
                                }
                                else
                                {
                                    echo '<div class="col-xl-3 col-sm-6 mb-3">
                                            <div class="card text-white bg-success o-hidden h-100">
                                                <div class="card-body">
                                                    <div class="card-body-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                                    <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2h-7zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48V8.35zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                                                    </svg>
                                                    </div>
                                                    <div class="mr-5">0 Active Sanctions</div>
                                                </div>
                                                <a class="card-footer text-white clearfix small z-1" href="#">
                                                    <span class="float-left">View Details</span>
                                                    <span class="float-right">
                                                        <i class="fa fa-angle-right"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>';
                                }
                            }
                        ?>        
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area mr-1"></i>
                                    Disciplinary Hearings Per Month
                                </div>
                                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    Cases Per Month
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                            </div>
                        </div>
                    </div>
		<div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i>
                Cases Per Category Per Department
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Department</th>
                                                <th>Absense</th>
                                                <th>Instructions</th>
                                                <th>Work Performance</th>
                                                <th>Organisational Property</th>
                                                <th>Service Management</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>18</th>
                                                <th>19</th>
                                                <th>21</th>
                                                <th>24</th>
                                                <th>2</th>
												
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            
                                            <tr>
                                                <td>Construction</td>
                                                <td>4</td>
                                                <td>6</td>
                                                <td>8</td>
                                                <td>10</td>
                                                <td>12</td>
                                            </tr>
                                           <tr>
                                                <td>Service Management</td>
                                                <td>41</td>
                                                <td>61</td>
                                                <td>81</td>
                                                <td>1</td>
                                                <td>2</td>
                                            </tr>
											<tr>
                                                <td>IT</td>
                                                <td>4</td>
                                                <td>6</td>
                                                <td>8</td>
                                                <td>10</td>
                                                <td>12</td>
                                            </tr>
											<tr>
                                                <td>Finance</td>
                                                <td>41</td>
                                                <td>61</td>
                                                <td>81</td>
                                                <td>1</td>
                                                <td>2</td>
                                            </tr>
                                        </tbody>
                    </table>
                </div>
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
    <script src="assets/js/Chart.js-master/Chart.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="assets/css/DataTables/DataTables-1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>                      
    <script src="assets/css/DataTables/DataTables-1.10.20/js/dataTables.bootstrap4.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
    <script>
            
            $(document).ready(function() {
                    $('#example').DataTable( {
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    } );
                } );
                </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Dismissal', 'Hours per Day'],
        ['Written Warnings', 8],
        ['Suspensions', 2],
        ['Disciplinary Hearings', 4],
        ['Verbal Warnings', 8]
        ]);

        // Optional; add a title and set the width and height of the chart
        var options = {'title':'My Average Day', 'width':550, 'height':400};

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
        }
    </script>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>