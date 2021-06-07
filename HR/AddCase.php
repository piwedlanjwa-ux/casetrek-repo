<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: /GitHub/casetrek/index.php");
        exit;
    }
    // Include config file
    require_once "DBConnection.php";
    
    // Define variables and initialize with empty values
    $date_of_sitting = $chairman = $employer_rep = $employee_name = $department = $position = "";
    
    $date_of_sitting_err = $chairman_err = $employer_rep_err = $employee_name_err = $department_err = $position_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        //Validate date of sitting
        if(empty(trim($_POST["date_of_sitting"])))
        {
            $date_of_sitting_err = "Please select the date of the sitting.";
        }
        else
        {
            $date_of_sitting = trim($_POST["date_of_sitting"]);
        }
        
        
        // Validate chairman
        if(empty(trim($_POST["chairman"])))
        {
            $chairman_err = "Please enter first and last name of the chairman.";
        } 
        else
        {
            $chairman = trim($_POST["chairman"]);
        }
        
        //Validate employer representative
        if(empty($_POST["employer_rep"]))
        {
            $employer_rep_err = "Please enter first and last name of employer representative.";
        }
        else
        {
            $employer_rep = trim($_POST["employer_rep"]);
            
        }
        
        //Validate employee representative
        if(empty($_POST["employee_name"]))
        {
            $employee_name_err = "Please enter first and last name of the employee representative.";
        }
        else
        {
            $employee_name = trim($_POST["employee_name"]);
        }
        
        // Validate department
        if(empty(trim($_POST["department"])))
        {
            $department_err = "Please enter department in which the employee is working under.";     
        } 
        else
        {
            $department = trim($_POST["department"]);
        }
        
        //Validate job title
        if(empty($_POST["position"]))
        {
            $position_err = "Please enter job title of the employee.";
        }
        else
        {
            $position = trim($_POST["position"]);
        }
        
        // Check input errors before inserting in database
        if(empty($date_of_sitting_err) && empty($chairman_err) && empty($employer_rep_err) && empty($employee_name_err) && empty($department_err) && empty($position_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO casestb (date_of_sitting, chairman, employer_rep, employee_name, department, position) VALUES (?, ?, ?, ?, ?, ?)";

            $sanction = "-";
            $validation = "-";
            $date_of_dismissal  = "";
            $status = "Open";
            
            if($stmt = mysqli_prepare($DBConnect, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $param_date_of_sitting, $param_chairman, $param_employer_rep, $param_employee_name, $param_department, $param_position);
                
                // Set parameters
                $param_date_of_sitting = $date_of_sitting;
                $param_chairman = $chairman;
                $param_employer_rep = $employer_rep;
                $param_employee_name = $employee_name; 
                $param_department = $department;
                $param_position = $position;     

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    $rowsql = $DBConnect->query("SELECT MAX(case_id) AS max FROM `casestb`;");
                    $rows = mysqli_fetch_array($rowsql);
                    $case_id = $rows['max'];

                    //Redirect to view cases page
                    $sql2 = "INSERT INTO closed_casestb (case_id, sanction, validation, status, date_of_dismissal) VALUES(?, ?, ?, ?, ?)";
                    
                    if($stmt2 = mysqli_prepare($DBConnect, $sql2))
                    {
                        mysqli_stmt_bind_param($stmt2, "sssss", $param_case_id, $param_sanction, $param_validation, $param_status, $param_date_of_dismissal);

                        $param_case_id = $case_id;
                        $param_sanction = $sanction;
                        $param_validation = $validation;
                        $param_status = $status;
                        $param_date_of_dismissal = $date_of_dismissal;

                        if(mysqli_stmt_execute($stmt2))
                        {
                            header("location: ViewCases.php");
                        }
                        else
                        {
                            echo "Stmt2 Something went wrong. Please try again later.";
                        }
                    }
                    
                } 
                else
                {
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        
        // Close connection
        mysqli_close($DBConnect);
    }
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Add Case</title>
            <style>
                body {
                    background: #555;
                }
                .content {
                    max-width: 550px;
                    margin: auto;
                    background: white;
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
                .form-control {
                    background-color: #ffffff;
                    background-image: none;
                    border: 1px solid #999999;
                    border-radius: 3px;
                    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
                    color: #333333;
                    display: block;
                    font-size: 14px;
                    height: 34px;
                    line-height: 1.42857;
                    padding: 6px 12px;
                    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
                    width: 122%;
                }
                .form-control{width:120% !important;}

                .login-box, .register-box {
                    width: 95%;
                    margin: 7% auto;
                    
                }
                .login-box-body{
                    border-radius: 8px;
                }
                .login-page, .register-page {
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
            </style>
            <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
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
                    <strong><a href="dashboard.php"><img src="assets/images/logo2.png" style="width: 70px; height: auto; margin: auto; padding-bottom: 10px; padding-right: 10px;" ></a></strong>
                    <span style="font-size: 13px;">Welcome<br><?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]);?></span>             
                </div>

                <ul class="list-unstyled components">
                    <li>
                        <a href="dashboard.php" >
                            <img src="https://img.icons8.com/carbon-copy/30/ffffff/details.png">
                            Dashboard
                        </a>
                    </li>
                    <li class="active">
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
                <div class="login-box">
                    <div class="login-box-body">
                        <h1 class="login-box-msg">Add Case</h1>       
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" >
                            <div class="form-group row <?php echo (!empty($employee_name_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Employee Name</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" type="text" name="employee_name" value="<?php echo $employee_name; ?>">
                                        <option selected disabled>Select Employee</option>
                                        <?php
                                            $query = "SELECT emp_id, firstName, lastName FROM employeestbl ORDER BY firstName";
                                            $r_set = $DBConnect->query($query);

                                            if($r_set)
                                            {
                                                
                                                while($row=$r_set->fetch_assoc())
                                                {
                                                    
                                                    echo "<option value='" . $row['firstName'] . " " . $row['lastName'] . "'>$row[firstName] $row[lastName] </option>";
                                                    
                                                }
                                                echo "</select>";
                                            }
                                            else{
                                                echo $DBConnect->error;
                                            }
                                        ?>
                                </div>
                                <span class="help-block"><?php echo $employee_name_err; ?></span>
                            </div>
                            
                            <div class="form-group row <?php echo (!empty($department_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Department</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" type="text" name="department" value="<?php echo $department; ?>">
                                        <option selected disabled>Select Department</option>
                                        <?php
                                            $query = "SELECT dept_id, department FROM departments ORDER BY department";
                                            $r_set = $DBConnect->query($query);

                                            if($r_set)
                                            {
                                                
                                                while($row=$r_set->fetch_assoc())
                                                {
                                                    
                                                    echo "<option value='" . $row['department'] . "'>$row[department] </option>";
                                                    
                                                }
                                                echo "</select>";
                                            }
                                            else{
                                                echo $DBConnect->error;
                                            }
                                        ?>
                                </div>
                                <span class="help-block"><?php echo $department_err; ?></span>
                            </div>
                            
                            <div class="form-group row <?php echo (!empty($position_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Position</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" type="text" name="position" value="<?php echo $position; ?>">
                                        <option selected disabled>Select Job Title</option>
                                        <?php
                                            
                                            $query = "SELECT pos_id, position, department FROM positions ORDER BY position";
                                            $r_set = $DBConnect->query($query);

                                            if($r_set)
                                            {
                                                
                                                while($row=$r_set->fetch_assoc())
                                                {
                                                    
                                                    echo "<option value='" . $row['position'] . "'>$row[position] </option>";
                                                    
                                                }
                                                echo "</select>";
                                            }
                                            else{
                                                echo $DBConnect->error;
                                            }
                                        ?>
                                </div>
                                <span class="help-block"><?php echo $position_err; ?></span>
                            </div>
                            
                            <div class="for-group row <?php echo (!empty($employer_rep_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Employer Representative</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" type="text" name="employer_rep" value="<?php echo $employer_rep; ?>">
                                        <option selected disabled>Select Employer Rep</option>
                                        <?php
                                            $query = "SELECT emp_id, firstName, lastName FROM employeestbl ORDER BY firstName";
                                            $r_set = $DBConnect->query($query);

                                            if($r_set)
                                            {
                                                
                                                while($row=$r_set->fetch_assoc())
                                                {
                                                    
                                                    echo "<option value='" . $row['firstName'] . " " . $row['lastName'] . "'>$row[firstName] $row[lastName] </option>";
                                                    
                                                }
                                                echo "</select>";
                                            }
                                            else{
                                                echo $DBConnect->error;
                                            }
                                        ?>
                                </div>
                                <span class="help-block"><?php echo $employer_rep_err; ?></span>
                            </div><br>
                        
                            <div class="form-group row <?php echo (!empty($date_of_sitting_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Date of Sitting</label></div>
                                <div class="col-sm-7"><input class="form-control" type="date" name="date_of_sitting" value="<?php echo $date_of_sitting; ?>" /></div>
                                <span class="help-block"><?php echo $date_of_sitting_err; ?></span>
                            </div>                        
                            
                            <div class="form-group row <?php echo (!empty($chairman_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Chairman</label></div>
                                <div class="col-sm-7"><input class="form-control" type="text" name="chairman" placeholder="First name & Last name" value="<?php echo $chairman; ?>" /></div>
                                <span class="help-block"><?php echo $chairman_err; ?></span>
                            </div>
                                        
                            <input type="submit" class="btn btn-primary" value="Add"/>&nbsp;<input type="reset" class="btn btn-outline-primary" value="Clear"/>&nbsp;<a href="ViewCases.php"><input type="cancel" class="btn btn-outline-secondary" value="Cancel"/></a>
                        </form>
                        
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
