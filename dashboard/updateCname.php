<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 5:34 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

$cid = $_POST['cid'];
$cname = mysqli_real_escape_string($con, $_POST['cname']);
if(!isThisUsersCourse($con, $cid)){
    $message = "Error: unauthorized attempt to add collaborator.";
    header("location:displayMessage.php?message=".$message);
}

if(!isset($_POST['cname'])) {
    $message = "Error: no course name provided for updation.";
    header("location:displayMessage.php?message=".$message);
}

if(!isset($_POST['cid'])){
    $message = "Error: no course ID provided for updation.";
    header("location:displayMessage.php?message=".$message);
}


$nameUpdateQuery = "UPDATE course SET cname='$cname' WHERE cid=$cid";
$nameUpdateResult = mysqli_query($con, $nameUpdateQuery) or die(mysqli_error($con));
header("location:coursedit.php?cid=".$cid);