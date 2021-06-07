<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: localhost:10080/GitHub/casetrek/index.php");
        exit;
    }
?>
<!DOCTYPE html>
<?php
    include("DBConnection.php");
    $id = $_GET['id'];
    $SQLString = "SELECT * FROM `casestb` WHERE case_id=$id";
    $QueryResult = $DBConnect->query($SQLString);

    $SQLString2 = "SELECT * FROM `closed_casestb` WHERE case_id=$id";
    $QueryResult2 = $DBConnect->query($SQLString2);
    
    if($QueryResult)
    {
        if($QueryResult->num_rows>0)
        {
            $row = $QueryResult -> fetch_assoc();
            $rows = $QueryResult2 -> fetch_assoc();
        }
        else
        {
            print "no users found";
        }
    }
    
    if($_POST)
    {
        if(isset($_POST["update"]))
        {
            $date_of_sitting = $_POST["date_of_sitting"];

            $sql = "UPDATE `casestb` SET `date_of_sitting`='$date_of_sitting' WHERE case_id=$id";
            $result = $DBConnect->query($sql);

            if($result)
            {
                header("Location: ViewCase.php?id=". $id . "");
            }
            else
            {
                print "Error: " . $DBConnect->error;
            }
        }
    }

    $SQLString2 = "SELECT * FROM `chargestb` WHERE case_id=$id";
    $QueryResult2 = $DBConnect->query($SQLString2);
    $no_of_charges = mysqli_num_rows($QueryResult2);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon.ico">
        <title>View Case</title>
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
                .btn-postpone {
                    color: #007bff !important;
                    background-color: #ffffff !important;
                    border-color: #007bff !important;
                }

                .btn-postpone:hover {
                    color:  #ffffff !important;
                    background-color: #007bff !important;
                    border-color: rgba(0, 0, 0, 0.1);
                }
                .login-box-body{
                    border-radius: 8px;
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
                    width: 89%;
                    margin: 7% auto;
                }.login-page, .register-page {
                    background: #d2d6de;
                }
                .table tbody tr td a:hover{
                    color: #142538 !important;
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
                .table tbody tr td a {
                    color: #007bff !important;
                }
            </style>

        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style4.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/solid.min.css">
        <!-- Font Awesome JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/solid.min.js" integrity="sha512-JkeOaPqiSsfvmKzJUsqu7j2fv0KSB6Yqb1HHF0r9FNzIsfGv+zYi4h4jQKOogf10qLF3PMZEIYhziCEaw039tQ==" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
        <style>
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
                            <h4 class="login-box-msg">Postpone Case No. <?php echo $id?></h4>
                            <form>
                                <div class="form-group row">
                                    <div class="col-sm-3"><b>Employee</b></div>
                                    <div class="col-sm-3"><?php echo $row['employee_name'];?></div>
                                    <div class="col-sm-3"><b>Employer Representative</b></div>
                                    <div class="col-sm-3"><?php echo $row['employer_rep'];?></div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3"><b>Date of Sitting</b></div>
                                    <div class="col-sm-3"><?php echo $row['date_of_sitting'];?></div>
                                    <div class="col-sm-3"><b>Chairman</b></div>
                                    <div class="col-sm-3"><?php echo $row['chairman'];?></div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3"><b>Department</b></div>
                                    <div class="col-sm-3"><?php echo $row['department'];?></div>
                                    <div class="col-sm-3"><b>Position</b></div>
                                    <div class="col-sm-3"><?php echo $row['position'];?></div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3"><b>Number of Charges</b></div>
                                    <div class="col-sm-3"><?php echo $no_of_charges;?></div>
                                    <div class="col-sm-3"><b>Status</b></div>
                                    <div class="col-sm-3"><?php echo $rows['status'];?></div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                    <div class='login-box'>
                        <div class='login-box-body'>
                            
                        <form action="PostponeCase.php?id=<?php print $id; ?>" method="POST" >
                                
                                <div class="form-group row">
                                    <div class="col-sm-3"><label>New Date of Sitting</label></div>
                                    <div class="col-sm-7"><input class="form-control" type="date" name="date_of_sitting" value="<?php print $row['date_of_sitting']; ?>" /></div>
                                    <span class="help-block"></span>
                                </div>                        
                                <input type="submit" name="update" class="btn btn-primary" value="Postpone"/>&nbsp;<input type="reset" class="btn btn-outline-primary" value="Clear"/>&nbsp;<a href="ViewCase.php?id=<?php print $id; ?>"><input type="cancel" class="btn btn-outline-secondary add-new" value="Cancel"/></a>
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