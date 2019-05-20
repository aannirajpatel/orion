<?php
require('../includes/auth.php');
require('../includes/db.php');

$email = $_SESSION['email'];

$retrieveUserTypeQuery = "SELECT type FROM user WHERE email = '" . $email . "'";

$retrieveUserTypeResult = mysqli_query($con, $retrieveUserTypeQuery);

if ($retrieveUserTypeResult != null) {
    $userTypeData = mysqli_fetch_array($retrieveUserTypeResult);

    $type = $userTypeData['type'];

    $_SESSION['type'] = $type;

    if ($type == 0) {
        header('location: student.php');
    } elseif ($type == 1) {
        header('location: trainer.php');
    } elseif ($type = 2) {
        header('location: admin.php');
    }
}
?>