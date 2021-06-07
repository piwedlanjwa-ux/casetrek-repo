<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    // Include config file
    require_once "DBConnection.php";
    
    // Define variables and initialize with empty values
    $email = $firstName = $lastName = $userType = $jobTitle = $password = $confirm_password = "";
    $email_err =$firstName_err = $lastName_err = $userType_err = $jobTitle_err = $password_err = $confirm_password_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        //Validate first name
        if(empty(trim($_POST["firstName"])))
        {
            $firstName_err = "Please enter first name.";
        }
        else
        {
            $firstName = trim($_POST["firstName"]);
        }
        
        //Validate last name
        if(empty(trim($_POST["lastName"])))
        {
            $lastName_err = "Please enter last name";
        }
        else
        {
            $lastName = trim($_POST["lastName"]);
        }
        
        // Validate email
        if(empty(trim($_POST["email"])))
        {
            $email_err = "Please enter a email address.";
        } 
        else
        {
            // Prepare a select statement
            $sql = "SELECT user_id, firstName, lastName FROM userstbl WHERE email = ?";
            
            if($stmt = mysqli_prepare($DBConnect, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // Set parameters
                $param_email = trim($_POST["email"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "User with this email address already exists.";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        //Validate user type
        if(empty($_POST["userType"]))
        {
            $userType_err = "Please select user type.";
        }
        else
        {
            $userType = trim($_POST["userType"]);
            
        }
        
        //Validate job title
        if(empty($_POST["jobTitle"]))
        {
            $jobTitle_err = "Please enter job title.";
        }
        else
        {
            $jobTitle = trim($_POST["jobTitle"]);
        }
        
        // Validate password
        if(empty(trim($_POST["password"])))
        {
            $password_err = "Please enter a password.";     
        } 
        elseif(strlen(trim($_POST["password"])) < 6)
        {
            $password_err = "Password must have atleast 6 characters.";
        } else
        {
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_err = "Please confirm password.";     
        } 
        else
        {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password))
            {
                $confirm_password_err = "Password did not match.";
            }
        }
        
        // Check input errors before inserting in database
        if(empty($firstName_err) && empty($lastName_err) && empty($email_err) && empty($userType_err) && empty($jobTitle_err) && empty($password_err) && empty($confirm_password_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO userstbl (firstName, lastName, email, password, userType, jobTitle) VALUES (?, ?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($DBConnect, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $param_firstName, $param_lastName, $param_email, $param_password, $param_userType, $param_jobTitle);
                
                // Set parameters
                $param_firstName = $firstName;
                $param_lastName = $lastName;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash    
                $param_userType = $userType;
                $param_jobTitle = $jobTitle;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    //Redirect to view users page
                    header("location: ViewUsers.php");
                } else{
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
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
    <title>Add User</title>
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
                border-radius: 0;
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
                text-align: center !important;
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
                <li class="active">
                    
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
                <li>
                        <a href="#categoriesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <img src="https://img.icons8.com/carbon-copy/30/ffffff/category.png"/>
                            Schedule of Offences
                        </a>
                        <ul class="collapse list-unstyled" id="positionsSubmenu">
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
            <div class="login-box">
                <div class="login-box-body">
                    <h1 class="login-box-msg">Add User</h1>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                          <div class="form-group row <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-3"><label>Name</label></div>
                              <div class="col-sm-7"><input type="text" class="form-control" id="firstName" name="firstName" placeholder="First name" value="<?php echo $firstName; ?>"required/></div>
                              <span class="help-block"><?php echo $firstName_err; ?></span>
                          </div>
                        
                          <div class="form-group row <?php echo (!empty($lastName_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-3"><label>Surname</label></div>
                              <div class="col-sm-7"><input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last name" value="<?php echo $lastName; ?>" required/></div>
                              <span class="help-block"><?php echo $lastName_err; ?></span>
                          </div>
                        
                          <div class="form-group row <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-3"><label>Email</label></div>
                            <div class="col-sm-7"><input type="text" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>" required/></div>
                             <span class="help-block"><?php echo $email_err; ?></span>
                          </div>
                        
                        <div class="form-group row <?php echo (!empty($userType_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-3"><label>Role</label></div>
                            <div class="col-sm-7">
                                <select class="form-control" name="userType" value="<?php echo $userType; ?> required">
                                    <option selected disabled>Select Role</option>
                                    <option>Manager</option>
                                    <option>HR</option>
                                    <option>System Admin</option>
                                </select>
                            </div>
                            <span class="help-block"><?php echo $userType_err; ?></span>
                        </div>
                
                            <div class="form-group row <?php echo (!empty($jobTitle_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Position</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" type="text" name="jobTitle" value="<?php echo $jobTitle; ?>">
                                        <option selected disabled>Select Job Title</option>
                                        <?php
                                            $query = "SELECT pos_id, position FROM positions ORDER BY position";
                                            $r_set = $DBConnect->query($query);

                                            if($r_set)
                                            {
                                                
                                                while($row=$r_set->fetch_assoc())
                                                {
                                                    
                                                    echo "<option value=$row[position]>$row[position]</option>";
                                                    
                                                }
                                                echo "</select>";
                                            }
                                            else{
                                                echo $DBConnect->error;
                                            }
                                        ?>
                                </div>
                                <span class="help-block"><?php echo $jobTitle_err; ?></span>
                            </div>
                        
                          <div class="form-group row <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-3"><label>Password</label></div>
                            <div class="col-sm-7"><input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>"></div>
                            <span class="help-block"><?php echo $password_err; ?></span>
                          </div>
                        
                          <div class="form-group row <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                          <div class="col-sm-3"><label>Confirm Password</label></div>
                              <div class="col-sm-7"><input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>"></div>
                              <span class="help-block"><?php echo $confirm_password_err; ?></span>
                          </div>
                        
                        <input type="submit" class="btn btn-primary" value="Add">&nbsp;<input type="reset" class="btn btn-outline-primary" value="Clear">&nbsp;<a href="ViewUsers.php"><input type="cancel" class="btn btn-outline-secondary" value="Cancel"></a>

                  </form>
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


