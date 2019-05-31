<?php
session_start();
require('../includes/db.php');
include('settings.php');
$invalidLogin = 0;
if (isset($_SESSION['email']) && time() < $_SESSION['access_token_expiry']) {
    header('location:../dashboard');
}
if (isset($_POST['password']) && isset($_POST['email'])) {
    $email = $_POST['email'];

    $password = md5($_POST['password']);

    //Verify credentials with database
    $validateLoginQuery = "SELECT uid, fname, lname FROM user WHERE email='$email' AND password='$password'";

    $validateLoginResult = mysqli_query($con, $validateLoginQuery) or die(mysqli_error());

    if (mysqli_num_rows($validateLoginResult) > 0) {
        //Set email session variable
        $_SESSION['email'] = $email;

        $userDetails = mysqli_fetch_array($validateLoginResult);

        //Set uid session variable
        $_SESSION['uid'] = $userDetails['uid'];

        //Set name session variable
        $fname = $userDetails['fname'];
        $_SESSION['fname'] = $fname;

        $lname = $userDetails['lname'];
        $_SESSION['lname'] = $lname;

        //Set expiry of the access token
        $_SESSION['access_token_expiry'] = time() + 3600;

        if ($_POST['remember-me'] == "true") {
            setcookie("email", $email, time() + (30 * 86400));
            setcookie("password", $password, time() + (30 * 86400));
        }
        header('location:../dashboard');
    } else {
        $invalidLogin = 1;
    }

}

//Login through cookie
if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];
    $validateLoginQuery = "SELECT uid, fname,lname FROM user WHERE email='$email' AND password='$password'";

    $validateLoginResult = mysqli_query($con, $validateLoginQuery) or die(mysqli_error());

    if (mysqli_num_rows($validateLoginResult) > 0) {
        //Set email session variable
        $_SESSION['email'] = $email;

        //Set uid session variable
        $_SESSION['uid'] = $userDetails['uid'];

        //Set name session variable
        $userDetails = mysqli_fetch_array($validateLoginResult);

        $fname = $userDetails['fname'];
        $_SESSION['fname'] = $fname;

        $lname = $userDetails['lname'];
        $_SESSION['lname'] = $lname;

        //Set expiry of the access token
        $_SESSION['access_token_expiry'] = time() + 3600;

        setcookie("email", $email, time() + (30 * 86400));
        setcookie("password", $password, time() + (30 * 86400));
        header('location:../dashboard');
    } else {
        $invalidLogin = 1;
    }

}

$google_login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=offline&prompt=consent';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Orion - Login</title>

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
<?php require 'homeNav.php';?>
<br><br>
<main class="login-bg">
<div class="container">
    <?php if ($invalidLogin == 1) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Invalid login credentials! Please try again or use Forgot Password.
        </div>
    <?php } ?>
    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <form class="user" method="POST" action="">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email"
                                               id="exampleInputEmail" aria-describedby="emailHelp"
                                               placeholder="Enter Email Address...">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="password"
                                               id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" name="remember-me"
                                                   id="customCheck" value="true">
                                            <label class="custom-control-label" for="customCheck">Remember Me</label>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-user btn-block" value="Login"/>
                                    <hr>
                                    <a href="<?= $google_login_url ?>" class="btn btn-google btn-user btn-block">
                                        <i class="fab fa-google fa-fw"></i> Login with Google
                                    </a>
                                    <!--<a href="#" class="btn btn-facebook btn-user btn-block">
                                        <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                    </a>-->
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="forgot-password.php">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="register.php">Create an Account!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
</main>
<?php require 'homeFooter.php';?>
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
</div>
</body>

</html>