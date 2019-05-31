<?php
include('settings.php');

require('../includes/db.php');

//OAuth 2.0 signup
$google_signup_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_SU_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=offline&prompt=consent';

$userAlreadyExists = 0;

//Local sign-up
if (isset($_POST['password'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $type = strtolower($_POST['account-type']);

    $userExistsQuery = "SELECT name FROM user WHERE email='$email'";

    $userExistsResult = mysqli_query($con, $userExistsQuery);

    if ($userExistsResult != null && mysqli_num_rows($userExistsResult) > 0) {
        $userAlreadyExists = 1;
    }

    $insertAccountDetailsQuery = "INSERT INTO user(fname,lname,email,password,type) VALUES ('$fname','$lname','$email','$password',$type)";
    $insertAccountDetiailsResult = mysqli_query($con, $insertAccountDetailsQuery) or die(mysqli_error($con));
    $_SESSION['email'] = $email;
    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['type'] = $type;
    $uidQuery = "SELECT uid FROM user WHERE email = '$email'";
    $uidResult = mysqli_query($con, $uidQuery);
    $uidData = mysqli_fetch_array($uidResult);
    $_SESSION['uid'] = $uidData['uid'];
    header('location:../login');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register | Orion E-Learning</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/orion.css" rel="stylesheet">
    <link href="../favicon.ico" rel="icon">

    <!-- Sheets from UD3M@-->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/vendors.css" rel="stylesheet">
    <link href="css/icon_fonts/css/all_icons.min.css" rel="stylesheet">
    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">

</head>

<body class="login-bg">
<div id="page">
    <?php require 'homeNav.php'; ?>
    <br><br>
    <main class="login-bg">
        <div class="container">
            <?php if ($userAlreadyExists == 1) {
                ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    User already exists! Please use another e-mail address to register or login <a
                            href="login.php">here</a>.
                </div>
                <?php

            }
            ?>
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                </div>
                                <form class="user" method="POST" action="">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" name="fname"
                                                   id="exampleFirstName" placeholder="First Name">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" name="lname"
                                                   id="exampleLastName" placeholder="Last Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email"
                                               id="exampleInputEmail" placeholder="Email Address">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user"
                                                   name="password"
                                                   id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user"
                                                   name="repeat-password"
                                                   id="exampleRepeatPassword" placeholder="Repeat Password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="label" for="account-type">Choose an account type:</label>
                                        <select class="form-control p-0" id="account-type" name="account-type"/>
                                        <option value="0">Student</option>
                                        <option value="1">Trainer</option>
                                        <option value="2">Admin</option>
                                        </select>
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-user btn-block"
                                           value="Register Account"/>
                                    <hr>
                                    <a href="<?= $google_signup_url ?>" class="btn btn-google btn-user btn-block">
                                        <i class="fab fa-google fa-fw"></i> Register with Google
                                    </a>
                                    <!--<a href="index.html" class="btn btn-facebook btn-user btn-block">
                                        <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                    </a>-->
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="index.php">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <?php require 'homeFooter.php';?>
</div>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<script src="js/common_scripts.js"></script>
<script src="js/main.js"></script>
<script src="assets/validate.js"></script>
</body>

</html>
