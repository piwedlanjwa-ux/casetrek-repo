<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Include config file
require_once "/xampp/htdocs/GitHub/casetrek/DBConnection.php";
 
// Define variables and initialize with empty values
$dateOfSitting = $charges = $chairman = $employerRep = $employeeRep = $department = $position = $employerWitness = $employeeWitness = $verdict = $sanction = $validation = $status = "";
$dateOfSitting_err = $charges_err = $chairman_err = $employerRep_err = $employeeRep_err = $department_err = $position_err = $employerWitness_err = $employeeWitness_err = $verdict_err = $sanction_err = $validation_err = $status_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //Validate date of sitting
    if(empty(trim($_POST["dateOfSitting"])))
    {
        $dateOfSitting_err = "Please select the date of the sitting.";
    }
    else
    {
        $dateOfSitting = trim($_POST["dateOfSitting"]);
    }
    
    //Validate charges
    if(empty(trim($_POST["charges"])))
    {
        $charges_err = "Please enter the charges of this case";
    }
    else
    {
        $charges = trim($_POST["charges"]);
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
    if(empty($_POST["employerRep"]))
    {
        $employerRep_err = "Please enter first and last name of employer representative.";
    }
    else
    {
        $employerRep = trim($_POST["employerRep"]);
        
    }
    
    //Validate employee representative
    if(empty($_POST["employeeRep"]))
    {
        $employeeRep_err = "Please enter first and last name of the employee representative.";
    }
    else
    {
        $employeeRep = trim($_POST["employeeRep"]);
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
    
    //declare employer witness
        $employerWitness = trim($_POST["employerWitness"]);
    //declare employee witness
        $employeeWitness = trim($_POST["employeeWitness"]);
        
    //Validate verdict
    if(empty($_POST["verdict"]))
    {
        $verdict_err = "Please select verdict.";
    }
    else
    {
        $verdict = trim($_POST["verdict"]);
    }
    
    //Validate sanction
    if(empty($_POST["sanction"]))
    {
        $sanction_err = "Please select sanction.";
    }
    else
    {
        $sanction = trim($_POST["sanction"]);
    }
    
    //Validate validation period
    if(empty($_POST["validation"]))
    {
        $validation_err = "Please select validation period.";
    }
    else
    {
        $validation = trim($_POST["validation"]);
    }
    
    //Validate status
    if(empty($_POST["status"]))
    {
        $status_err = "Please select status.";
    }
    else
    {
        $status = trim($_POST["status"]);
    }
    
    // Check input errors before inserting in database
    if(empty($dateOfSitting_err) && empty($chairman_err) && empty($employerRep_err) && empty($employeeRep_err) && empty($department_err) && empty($position_err) && empty($employerWitness_err) && empty($employeeWitness_err) && empty($verdict_err) && empty($sanction_err) && empty($validation_err) && empty($status_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO casestbl (dateOfSitting, chairman, employerRep, employeeRep, department, position, employerWitness, employeeWitness, verdict, sanction, validation, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($DBConnect, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $param_dateOfSitting, $param_chairman, $param_employerRep, $param_employeeRep, $param_department, $param_position, $param_employerWitness, $param_employeeWitness, $param_verdict, $param_sanction, $param_validation, $param_status);
            
            // Set parameters
            $param_dateOfSitting = $dateOfSitting;
            $param_chairman = $chairman;
            $param_employerRep = $employerRep;
            $param_employeeRep = $employeeRep; 
            $param_department = $department;
            $param_position = $position;
            $param_employerWitness = $employerWitness;
            $param_employeeWitness = $employeeWitness;
            $param_verdict = $verdict;
            $param_sanction = $sanction;
            $param_validation = $validation;
            $param_status = $status;
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                //Redirect to view cases page
                header("location: ViewCases.php");
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
                width: 1100px;
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
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon.ico">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="/css/style4.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><a href="dashboard.php"><img src="images/logo.png" style="width: 200px; height: auto;" ></a></h3>
                <strong><a href="dashboard.php"><img src="images/logo2.png" style="width: 70px; height: auto; margin: auto; padding-bottom: 10px;" ></a></strong>
                <span style="font-size: 15px;">Welcome<br><?php echo htmlspecialchars($_SESSION["firstName"]); echo " "; echo htmlspecialchars($_SESSION["lastName"]);?></span>             
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
                    <h1 class="login-box-msg">Add Charges</h1>       
                    <div class="form-group">
				<<form action="upload-manager.php" method="post" enctype="multipart/form-data">
                                    <h2>Upload File</h2>
                                    <label for="fileSelect">Filename:</label>
                                    <input type="file" name="photo" id="fileSelect">
                                    <input type="submit" name="submit" value="Upload">
                                    <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB.</p>
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
    <script>
        $(document).ready(function(){
                var i=1;
                $('#add').click(function(){
                        i++;
                        $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="charges[]" placeholder="Charge No. '+i+'" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                });

                $(document).on('click', '.btn_remove', function(){
                        var button_id = $(this).attr("id"); 
                        $('#row'+button_id+'').remove();
                });

                $('#submit').click(function(){		
                        $.ajax({
                                url:"name.php",
                                method:"POST",
                                data:$('#add_charge').serialize(),
                                success:function(data)
                                {
                                        alert(data);
                                        $('#add_charge')[0].reset();
                                }
                        });
                });

        });
    </script>
    
</body>

</html>
