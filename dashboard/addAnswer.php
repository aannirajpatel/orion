<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 11:59 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
$uid = $_SESSION['uid'];

if(!isset($_POST['atext'])){
    $message = "Error - an invalid answer request has been issued. Please visit the help section if you feel there is some problem with this website.";
    header("location:displayMessage.php?message=".$message);
}

$cid = $_POST['cid'];

if(!isAuditing($con, $cid, $uid) && !isThisStudentsCourse($con, $cid) && !isThisUsersCourse($con, $cid)){
    $message = "Error - an invalid answer request has been issued. Please visit the help section if you feel there is some problem with this website.";
    header("location:displayMessage.php?message=".$message);
}

$questionId = $_POST['questionid'];
$atext = mysqli_real_escape_string($con, $_POST['atext']);
$insertAnswerQuery = "INSERT INTO answer(questionid, uid, atext) VALUES($questionId, $uid, '$atext')";
$insertAnswerResult = mysqli_query($con, $insertAnswerQuery) or die(mysqli_error($con));
header("location:qna.php?cid=".$cid);
?>