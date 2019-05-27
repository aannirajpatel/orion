<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 12:24 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
$uid = $_SESSION['uid'];

if(!isset($_POST['qtext'])){
    $message = "Error - an invalid question request has been issued. Please visit the help section if you feel there is some problem with this website.";
    header("location:displayMessage.php?message=".$message);
}

$cid = $_POST['cid'];

if(!isAuditing($con, $cid, $uid) && !isThisStudentsCourse($con, $cid) && !isThisUsersCourse($con, $cid)){
    $message = "Error - an invalid question request has been issued. Please visit the help section if you feel there is some problem with this website.";
    header("location:displayMessage.php?message=".$message);
}

$qtext = mysqli_real_escape_string($con, $_POST['qtext']);
$insertQuestionQuery = "INSERT INTO question(cid,uid, qtext) VALUES($cid, $uid,'$qtext')";
$insertQuestionResult = mysqli_query($con, $insertQuestionQuery) or die(mysqli_error($con));

header("location:qna.php?cid=".$cid);

?>