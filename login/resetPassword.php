<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 30/5/19 1:04 PM.
 */
require '../includes/db.php';
if(!isset($_GET['uid']) || !isset($_GET['ph'])){
    die("Invalid link for password reset cannot be processed.");
}
if(isset($_POST['uid']) && isset($_POST['password'])){
    $uid = $_POST['uid'];
    $password = $_POST['password'];
    $updateQuery = "UPDATE user SET password='".md5($password)."' WHERE uid=$uid";
    $updateResult = mysqli_query($con, $updateQuery) or die(mysqli_error($con));
    header('location:index.php');
}
$uid = $_GET['uid'];
$ph = $_GET['ph'];
$checkQuery = "SELECT * FROM user WHERE uid=$uid AND password='$ph'";
$checkResult = mysqli_query($con, $checkQuery) or die(mysqli_error($con));
if(mysqli_num_rows($checkResult)==1){
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Reset Password | Orion E-Learning</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
              rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link href="css/orion.css" rel="stylesheet">
        <link href="../favicon.ico" rel="icon">

    </head>

    <body class="login-bg">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Reset Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your new password below and all will be back to normal.</p>
                                    </div>
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" placeholder="Enter New Password...">
                                            <input type="hidden" name="uid" value="<?php echo $uid;?>">
                                        </div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block"
                                               value="Reset Password">
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="index.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    </body>

    </html>
    <?php
} else{
    die("Invalid link for password reset cannot be processed.");
}