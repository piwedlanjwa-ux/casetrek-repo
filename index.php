<?php
    // Initialize the session
    session_start();
    
    // Check if the user is already logged in, if yes then redirect him to index page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
    }
    
    // Include config file
    require_once "DBConnection.php";
    
    // Define variables and initialize with empty values
    $email = $password = $userType = "";
    $email_err = $password_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        // Check if email is empty
        if(empty(trim($_POST["email"]))){
            $email_err = "<span style='color: red;'>Please enter email.</span>";
        } else{
            $email = trim($_POST["email"]);
        }
        
        // Check if password is empty
        if(empty(trim($_POST["password"]))){
            $password_err = "<span style='color: red;'>Please enter your password.</span>";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate credentials
        if(empty($email_err) && empty($password_err)){
            // Prepare a select statement
            $sql = "SELECT user_id, firstName, lastName, email, password, userType FROM userstbl WHERE email = ?";
            
            if($stmt = mysqli_prepare($DBConnect, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // Set parameters
                $param_email = $email;
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Store result
                    mysqli_stmt_store_result($stmt);
                    
                    // Check if email exists, if yes then verify password
                    if(mysqli_stmt_num_rows($stmt) == 1){                    
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $user_id, $firstName, $lastName, $email, $hashed_password, $userType);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                // Password is correct, so start a new session
                                session_start();
                                
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["user_id"] = $user_id;
                                $_SESSION["firstName"] = $firstName;
                                $_SESSION["lastName"] = $lastName;
                                $_SESSION["email"] = $email;
                                $_SESSION["userType"] = $userType;                           
                                
                                if($_SESSION['userType'] == "System Admin")
                                {
                                    // Redirect user to index page
                                    header("location: admin/dashboard.php");
                                }
                                elseif($_SESSION['userType'] == "HR")
                                {
                                    header("Location: HR/dashboard.php");
                                }
                                else
                                {
                                    header("Location: manager/dashboard.php");
                                }
                            } 
                            else{
                                // Display an error message if password is not valid
                                $password_err = "<span style='color: red;'>The password you entered is not valid.</span>";
                            }
                        }
                    } else{
                        // Display an error message if email doesn't exist
                        $email_err = "<span style='color: red;'>No account found with that email address.</span>";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
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
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
        <style>
            body {
                background: #555;
            }
            .content {
                max-width: 500px;
                margin: auto;
                background: white;
                padding: 8%;
                padding-top: 15%;
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
                width: 100%;
            }

            .login-box, .register-box {
                width: 480px;
                margin: 7% auto;
                background: #fff;
                border-radius: 8px;
                
            }.login-page, .register-page {
                background: #d2d6de;
            }

            .login-logo, .register-logo {
                font-size: 35px;
                text-align: center;
                font-weight: 300;
            }.login-box-msg, .register-box-msg {
                margin: 0;
                text-align: center;
                padding: 0 20px 20px 20px;
            }.login-box-body, .register-box-body {
                background: #fff;
                padding-left: 30px;
                padding-right: 30px;
                padding-bottom: 30px;
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
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style4.css">

        <!-- Font Awesome JS -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

    </head>
    <body>
        
        
         <div class="login-box">
            <div class="login-logo">
                <img id="logo" src="assets/images/logo.png" style="width: 340px; height: auto;">
            </div>
            <div class="login-box-body">
                
                <h1 class="login-box-msg" style="text-align: center;">Login</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group row <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-10"><input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo $email; ?>"/></div>
                            <span class="help-block"><?php echo $email_err; ?></span>
                        </div>
                        <div class="form-group row <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <div class="col-sm-10"><input class="form-control" type="password" name="password" placeholder="Password"/></div>
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Login"/>&nbsp;<button type="reset" class="btn btn-outline-primary">Clear</button>   
                    </form>
            </div>
         </div>
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
