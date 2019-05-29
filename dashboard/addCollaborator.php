<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 3:53 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if(!isThisUsersCourse($con, $cid)){
    $message = "Unauthorized attempt to add collaborator.";
    header("location:displayMessage.php?message=".$message);
}

if(!isset($_POST['collabemail'])) {
    header("location:trainer.php");
}

if(!filter_var($_POST['collabemail'],FILTER_VALIDATE_EMAIL)){
    $message = "Please enter a valid e-mail ID for collaborating";
    header("location:displayMessage.php?message=".$message);
}

$email = $_POST['collabemail'];

$userQuery = "SELECT uid FROM user WHERE email='$email'";
$userResult = mysqli_query($con, $userQuery) or die(mysqli_error($con));
$userData = mysqli_fetch_array($con, $userResult);
$user = $userData['uid'];

$insertCollaboratorQuery = "INSERT INTO ctraieners(cid, uid) VALUES ($cid, $user)";
$insertCollaboratorResult = mysqli_query($con, $insertCollaboratorQuery) or die(mysqli_error($con));

header("location:coursedit.php?cid=".$cid);

?>