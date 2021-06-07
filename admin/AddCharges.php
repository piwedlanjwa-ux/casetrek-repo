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
    $id=$_GET['id'];

    // Define variables and initialize with empty values
    $category = $subcategory = $description = $verdict = "";
    $case_id = $id;
    $category_err = $subcategory_err = $description_err = $verdict_err = "";

    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        //Validate description
        if(empty(trim($_POST["description"])))
        {
            $description_err = "Please describe the charge.";
        }
        else
        {
            $description = trim($_POST["description"]);
        }
        
        //Validate verdict
        if($_POST["verdict"] == "Select Verdict")
        {
            $verdict_err = "Please select verdict.";
        }
        else
        {
            $verdict = $_POST["verdict"];
        }
        
        //Validate category
        if($_POST["category"] == "Select Category")
        {
            $category_err = "Please select category.";
        }
        else
        {
            $category = $_POST["category"];
        }

        //Validate subcategory
        if($_POST["subcategory"] == "Select Subcategory")
        {
            $subcategory_err = "Please select subcategory.";
        }
        else
        {
            $subcategory = $_POST["subcategory"];
        }

        // Check input errors before inserting in database
        if(empty($description_err) && empty($verdict_err) && empty($category_err) && empty($subcategory_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO chargestb (case_id, description, verdict, category, subcategory) VALUES (?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($DBConnect, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss", $param_case_id, $param_description, $param_verdict, $param_category, $param_subcategory);
                
                // Set parameters
                $param_case_id = $case_id;
                $param_description = $description;
                $param_verdict = $verdict;
                $param_category = $category; 
                $param_subcategory = $subcategory;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    //Redirect to view cases page
                    header("location: ViewCase.php?id=" . $id);
                } else{
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
            else
            {
                echo "Oops, Something went wrong: " . $DBConnect->error;
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

    <title>Add Charge</title>
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
     <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
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
                <strong><a href="dashboard.php"><img src="assets/images/logo2.png" style="width: 70px; height: auto; margin: auto; padding-bottom: 10px; padding-right: 10px;"></a></strong>
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
            <div class="login-box">
                <div class="login-box-body">
                    <h1 class="login-box-msg">Add Charge</h1>       
                    <form action="AddCharges.php?id=<?php echo $id; ?>" method="POST" >
                        
                             <div class="form-group row ">
                                <div class="col-sm-3"><label>Category</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" type="text" name="category" value="<?php echo $category; ?>">
                                        <option selected value="Select Category" disabled>Select Category</option>
                                        <option>Misconduct</option>
                                        <option>Incapacity Due to Poor Performance</option>
                                        <option>Incapacity Due to Ill Health</option>
                                    </select>
                                </div>
                                <span class="help-block"></span>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3"><label>Subcategory</label></div>
                                <div class="col-sm-7">
                                        <select class="form-control" type="text" name="subcategory" value="<?php echo $subcategory?>">
                                            <option selected value="Select Subcategory" disabled>Select Subcategory</option>
                                            <?php
                                            $query = "SELECT subcategory_id, subcategory_name FROM charge_subcategorytbl ORDER BY subcategory_name";
                                            $r_set = $DBConnect->query($query);

                                            if($r_set)
                                            {
                                                while($row=$r_set->fetch_assoc())
                                                {
                                                    echo "<option value=$row[subcategory_name]>$row[subcategory_name]</option>";
                                                }
                                                echo "</select>";
                                            }
                                            else{
                                                echo $DBConnect->error;
                                            }
                                        ?>
                                    </div>
                            </div><br>

                            <div class="form-group row <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                                <div class="col-sm-3"><label>Description</label></div>
                                <div class="col-sm-7"><textarea cols="100" rows="6" class="form-control" type="text" name="description" placeholder="Description of the charge" value="<?php print $row['description']; ?>"></textarea></div>
                                <span class="help-block"><?php echo $description_err ?></span>
                            </div>
                            
                            <div class="form-group row ">
                                <div class="col-sm-3"><label>Verdict</label></div>
                                <div class="col-sm-7">
                                    <select class="form-control" name="verdict" value="<?php echo $verdict; ?>">
                                        <option selected disabled>Select Verdict</option>
                                        <option>Open</option>
                                        <option>Guilty</option>
                                        <option>Not Guilty</option>
                                    </select>
                                </div>
                                <span class="help-block"></span>
                            </div>
                                       
                        <input type="submit" class="btn btn-primary" value="Add">&nbsp;<input type="reset" class="btn btn-outline-primary" value="Clear">&nbsp;<a href='ViewCase.php?id=<?php print $id; ?>'><button class="btn btn-outline-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg></button></a>
                    </form>
                    
                </div>
            </div>
        </div>
    
    <!-- jQuery CDN - Slim version (=without AJAX) -->
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
                                url:"add_cases.php",
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
