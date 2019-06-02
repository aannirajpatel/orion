<?php
session_start();
//Include the DB connection script
require('../includes/db.php');
// Holds the Google application Client Id, Client Secret and Redirect Url
require_once('settings.php');

// Holds the various APIs functions
require_once('google-login-api.php');

// Google passes a parameter 'code' in the Redirect Url
if (isset($_GET['code'])) {
    try {
        // Get the access token
        $data = GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);

        // Access Token
        $access_token = $data['access_token'];

        // Get user information
        $user_info = GetUserProfileInfo($access_token);

        $email = $user_info['email'];

        $userExistsQuery = "SELECT uid, fname, lname FROM user WHERE email = '$email'";

        $userExistsResult = mysqli_query($con, $userExistsQuery);

        if (mysqli_num_rows($userExistsResult) > 0) {

            $userExistsData = mysqli_fetch_array($userExistsResult);

            $_SESSION['uid'] = $userExistsData['uid'];

            $_SESSION['fname'] = $userExistsData['fname'];

            $_SESSION['lname'] = $userExistsData['lname'];

            $_SESSION['email'] = $email;
            // Refresh Token
            if (array_key_exists('refresh_token', $data))
                $refresh_token = $data['refresh_token'];

            // Save the access token expiry timestamp
            $_SESSION['access_token_expiry'] = time() + $data['expires_in'];

            $_SESSION['access_token'] = $data['access_token'];
            $fwLink = "";
            if(isset($_SESSION['fwLink'])){
                $fwLink = "/".$_SESSION['fwLink'];
                unset($_SESSION['fwLink']);
            }
            header('location:../dashboard'.$fwLink);
        } else {
            die("Error logging you in via google. Try normal login <a href='login.php'>here</a>");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}

?>