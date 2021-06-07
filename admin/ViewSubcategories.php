<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    use Phppot\DataSource;

    require_once 'DataSource.php';
    $db = new DataSource();
    $connect = $db->getConnection();
    
    if (isset($_POST["import"])) {
        
        $fileName = $_FILES["file"]["tmp_name"];
        
        if ($_FILES["file"]["size"] > 0) {
            
            $file = fopen($fileName, "r");
            
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                
                $subcategory_name= "";
                if (isset($column[0])) {
                    $subcategory_name = mysqli_real_escape_string($connect, $column[0]);
                } 

                $category= "";
                if (isset($column[1])) {
                    $category = mysqli_real_escape_string($connect, $column[1]);
                } 
                
                $sqlInsert = "INSERT into charge_subcategorytbl (subcategory_name, category)
                       values (?, ?)";
                $paramType = "ss";
                $paramArray = array(
                    $subcategory_name,
                    $category
                );
                $insertId = $db->insert($sqlInsert, $paramType, $paramArray);
                
                if (! empty($insertId)) {
                    $type = "success";
                    $message = "CSV Data Imported into the Database";
                } else {
                    $type = "error";
                    $message = "Problem in Importing CSV Data";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
        <title>Schedule of Offences</title>
            <style>
                body {
                        background: #555;
                }
                .content {
                    max-width: 600px;
                    margin: auto;
                    background: white;
                    padding: 8%;
                    padding-top: 10%;
                    border: 1px grey;
                }
                @font-face {
                font-family: 'Dosis';
                font-style: normal;
                font-weight: 300;
                src: local('Dosis Light'), local('Dosis-Light'), url(http://fonts.gstatic.com/l/font?kit=RoNoOKoxvxVq4Mi9I4xIReCW9eLPAnScftSvRB4WBxg&skey=a88ea9d907460694) format('woff2');
                }
                @font-face {
                font-family: 'Dosis';
                font-style: normal;
                font-weight: 500;
                src: local('Dosis Medium'), local('Dosis-Medium'), url(http://fonts.gstatic.com/l/font?kit=Z1ETVwepOmEGkbaFPefd_-CW9eLPAnScftSvRB4WBxg&skey=21884fc543bb1165) format('woff2');
                }
                body {
                background: #d2d6de;
                    font-family: 'Source Sans Pro', 'Helvetica Neue', Arial, sans-serif,  Open Sans;
                    font-size: 14px;
                    line-height: 1.42857;
                    height: 350px;
                    padding: 0;
                    margin: 0;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    font-weight: 400;
                    overflow-x: hidden;
                    overflow-y: auto;

                }
                .table tbody tr td a {
                    color: #007bff !important;
                    text-align: center;
                }
                .table tbody tr td a:hover{
                    color: #142538 !important;
                }
                .form-control {
                    background-color: #ffffff;
                    background-image: none;
                    border: 1px solid #999999;
                    border-radius: 19 !important;
                    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
                    color: #333333;
                    display: block;
                    font-size: 14px;
                    height: 34px;
                    line-height: 1.42857;
                    padding: 6px 12px;
                    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
                    width: 100%;
                }
                .form-control{width:100% !important;}

                .login-box, .register-box {
                    width: 96%;
                    margin: 7% auto;
                }.login-page, .register-page {
                    background: #d2d6de;
                }

                .login-logo, .register-logo {
                    font-size: 35px;
                    text-align: center;
                    margin-bottom: 25px;
                    font-weight: 300;
                }.login-box-msg, .register-box-msg {
                    margin: 0;
                    text-align: center;
                    padding: 0 20px 20px 20px;
                }.login-box-body, .register-box-body {
                    background: #fff;
                    padding: 20px;
                    border-top: 0;
                    color: #666;
                    border-radius: 8px;
                }.has-feedback {
                    position: relative;
                }
                .form-group {
                    margin-bottom: 15px;
                }.has-feedback .form-control {
                    padding-right: 42.5px;
                }.login-box-body .form-control-feedback, .register-box-body .form-control-feedback {
                    color: #777;
                }
                .form-control-feedback {
                    position: absolute;
                    top: 0;
                    right: 0;
                    z-index: 2;
                    display: block;
                    width: 34px;
                    height: 34px;
                    line-height: 34px;
                    text-align: center;
                    pointer-events: none;
                }.checkbox, .radio {
                    position: relative;
                    display: block;
                    margin-top: 10px;
                    margin-bottom: 10px;
                }.icheck>label {
                    padding-left: 0;
                }
                .checkbox label, .radio label {
                    min-height: 20px;
                    padding-left: 20px;
                    margin-bottom: 0;
                    font-weight: 400;
                    cursor: pointer;
                }
                table {
                    counter-reset: row-num -1;
                }
                table tr {
                    counter-increment: row-num;
                }
                table tr td:first-child::before {
                    content: counter(row-num);
                }
            </style>

        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style4.css">

        <!-- Font Awesome JS -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

    </head>

    <body>

        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3><a href="dashboard.php"><img src="assets/images/logo.png" style="width: 200px; height: auto;" ></a></h3>
                    <strong><a href="dashboard.php"><img src="assets/images/logo2.png" style="width: 70px; height: auto; margin: auto; padding-bottom: 10px;" ></a></strong>
                    <span style="font-size: 15px;">Welcome<br><?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]);?></span>             
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
                    <li>
                        <a href="#departmentsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <img src="https://img.icons8.com/carbon-copy/30/ffffff/department.png"/>
                            Departments
                        </a>
                        <ul class="collapse list-unstyled" id="departmentsSubmenu">
                            <li>
                                <a href="ViewDepartments.php">View Departments</a>
                            </li>
                            <li>
                                <a href="AddDepartment.php">Add Departments</a>
                            </li>
                            
                        </ul>
                    </li>
                    <li>
                        <a href="#positionsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <img src="https://img.icons8.com/wired/30/ffffff/job.png"/>
                            Job Titles
                        </a>
                        <ul class="collapse list-unstyled" id="positionsSubmenu">
                            <li>
                                <a href="ViewPositions.php">View Job Titles</a>
                            </li>
                            <li>
                                <a href="AddPosition.php">Add Job Title</a>
                            </li>
                            
                        </ul>
                    </li>
                    <li class="active">
                        <a href="#categoriesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <img src="https://img.icons8.com/carbon-copy/30/ffffff/category.png"/>
                            Schedule of Offences
                        </a>
                        <ul class="collapse list-unstyled" id="categoriesSubmenu">
                            <li>
                                <a href="ViewSubcategories.php">View Schedule of Offences</a>
                            </li>
                            <li>
                                <a href="AddSubcategory.php">Add Schedule of Offences</a>
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
                <div class='login-box'>
                    <div class='login-box-body'>
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-8"><h3><b>Schedule of Offences</b></h3></div>
                                <div class="col-sm-4">
                                    <a href="AddSubcategory.php"><button type="button" class="btn btn-primary add-new"><i class="fa fa-plus"></i> New Offence</button></a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                                    <div class="input-row">
                                        <label class="col-sm-3 control-label">Choose CSV File</label> 
                                        <input type="file" name="file" id="file" class="col-sm-4" accept=".csv">
                                        <button type="submit" id="submit" name="import" class="col-sm-1 btn btn-primary add-new">Import</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>    
                        <?php
                            include "DBConnection.php";
                            $SQLString = "SELECT * FROM `charge_subcategorytbl`";
                            $QueryResult = $DBConnect->query($SQLString);
                            $counter = 0;
                            if($QueryResult)
                            {
                                if($QueryResult->num_rows>0)
                                {
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-hover table-striped table-sm'>";
                                    echo "<tr><th></th><th>Subcategory</><th>Category</th><th>Action</th></tr>";
                                    while($row = $QueryResult->fetch_assoc())
                                    {
                                        echo "<tr><td></td>";
                                        echo "<td>". $row['subcategory_name'] . "</td>";
                                        echo "<td>". $row['category'] . "</td>";
                                        echo "<td><a href ='EditSubcategory.php?id= " . $row['subcategory_id'] . "'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                                        <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                                        <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/>
                                      </svg></a> | <a href ='DeletePosition.php?id= " . $row['subcategory_id'] . "'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
                                      <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
                                      <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
                                    </svg></a></td></tr>";
                                    }
                                    echo "</table>";
                                    echo "</div></div></div></div>";
                                }
                                else
                                {
                                    echo $DBConnect->error;
                                }
                            }
                        ?>
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