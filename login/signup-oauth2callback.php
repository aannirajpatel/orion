<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "orion");

// Holds the Google application Client Id, Client Secret and Redirect Url
require_once('settings.php');

// Holds the various APIs functions
require_once('google-login-api.php');

if (isset($_POST['password'])) {
    //Collect signup data - name, email, password and type
    $name = $_SESSION['name'];
    $nameArray = explode(' ', $name, 2);
    $fname = $nameArray[0];
    $lname = $nameArray[1];

    $email = $_SESSION['email'];

    $password = md5($_POST['password']);

    $type = $_POST['account-type'];

    //Destroy session so user cannot misuse it
    session_destroy();

    $insertAccountDetailsQuery = "INSERT INTO user(fname,lname,email,password) VALUES ('$fname','$lname','$email','$password',$type)";
    $insertAccountDetiailsResult = mysqli_query($con, $insertAccountDetailsQuery) or die(mysqli_error());

    header('location:../login');
}

// Google passes a parameter 'code' in the Redirect Url
if (isset($_GET['code'])) {
    try {
        // Get the access token
        $data = GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_SU_URL, CLIENT_SECRET, $_GET['code']);

        // Access Token
        $access_token = $data['access_token'];

        // Get user information
        $user_info = GetUserProfileInfo($access_token);

        // Check if the account already exists
        $checkAccountExistsQuery = "SELECT * FROM user WHERE email = '" . $user_info['email'] . "'";

        $checkAccountExistsResult = mysqli_query($con, $checkAccountExistsQuery) or die(mysqli_error());

        if (mysqli_num_rows($checkAccountExistsResult) > 0) {
            echo "Your account already exists. Please click <a href='../login'>here</a> to login.";
        } //If account doesn't already exist, create it.
        else {
            ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>

                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta name="description" content="">
                <meta name="author" content="">

                <title>Orion - Register</title>

                <!-- Custom fonts for this template-->
                <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
                <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                      rel="stylesheet">

                <!-- Custom styles for this template-->
                <link href="css/sb-admin-2.min.css" rel="stylesheet">

            </head>

            <body class="bg-gradient-primary">

            <div class="container">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Almost there...</h1>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user"
                                                       name="password" id="exampleInputPassword" placeholder="Password">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user"
                                                       name="repeat-password" id="exampleRepeatPassword"
                                                       placeholder="Repeat Password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="account-type">You are a:</label>
                                            <select class="form-control form-control-user" id="account-type"
                                                    name="account-type"/>
                                            <option value="0">Student</option>
                                            <option value="1">Trainer</option>
                                            <option value="2">Admin</option>
                                            </select>
                                        </div>
                                        <a href="login.html" class="btn btn-primary btn-user btn-block">
                                            Register Account
                                        </a>
                                    </form>
                                    <hr>
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
            $email = $user_info['email'];
            $name = $user_info['name'];
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}
?>