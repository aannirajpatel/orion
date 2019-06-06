<?php
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

$uid = $_SESSION['uid'];

if (!isset($_POST['rid'])) {
    header("location:404.php");
}
$rid = $_POST['rid'];
$cid = getCidFromRid($con, $rid);
if (!isThisUsersCourse($con, $cid) && !isAuditing($con, $cid, $uid) && !isThisStudentsCourse($con, $cid)) {
    header("location:404.php");
}
$quizDetailsQuery = "SELECT * FROM cresources WHERE rid=$rid";
$quizDetailsResult = mysqli_query($con, $quizDetailsQuery) or die(mysqli_error($con));
if (mysqli_num_rows($quizDetailsResult) != 1) {
    header("location:404.php");
}
$quizDetailsData = mysqli_fetch_array($quizDetailsResult);
$quizName = $quizDetailsData['rtext'];
$quizMaxAttempts = intval($quizDetailsData['raddr']);

$attemptsQuery = "SELECT count(*) AS attempts FROM quizattempts WHERE uid=$uid AND quizid=$rid";
$attemptsResult = mysqli_query($con, $attemptsQuery) or die(mysqli_error($con));
$attemptsData = mysqli_fetch_array($attemptsResult);
$attempts = $attemptsData['attempts'];
if ($attempts >= $quizMaxAttempts) {
    $message = "You cannot take this test more than $quizMaxAttempts time(s)";
    header("location:displayMessage.php?message=" . $message);
    die();
}

$questionQuery = "SELECT * FROM quizquestion WHERE quizid=$rid";
$questionResult = mysqli_query($con, $questionQuery) or die(mysqli_error($con));
$maxScore = mysqli_num_rows($questionResult);
$score = 0;
while ($questionData = mysqli_fetch_array($questionResult)) {
    $qno = $questionData['qno'];
    $answerQuery = "SELECT * FROM quizanswer WHERE qno=$qno AND quizid=$rid AND marks = 1";
    $answerResult = mysqli_query($con, $answerQuery) or die(mysqli_error($con));
    if (mysqli_num_rows($answerResult) == 0) {
        header("location:404.php");
    }
    $answerData = mysqli_fetch_array($answerResult);
    $answerHash = hash("sha256", $answerData['answertext'], FALSE);
    $akey = "a" . $qno;
    if (isset($_POST[$akey])) {
        if ($answerHash == $_POST[$akey]) {
            $score += 1;
        }
    }
}
if ($maxScore > 0) {
    $percentScore = round($score / $maxScore * 100, 2);
} else {
    header("location:404.php");
}
$insertAttemptQuery = "INSERT INTO quizattempts(quizid,uid,score) VALUES ($rid, $uid,$percentScore)";
$insertAttemptResult = mysqli_query($con, $insertAttemptQuery) or die(mysqli_error($con));
$message = "You scored $percentScore%. You answered $score questions correctly out of a total of $maxScore questions.";
if($percentScore>=40){
    viewedResource($con, $rid, $uid);
    $message = "Congratulations! You have passed the quiz. ".$message;
} else{
    $message = "Sorry, you have failed in this attempt. Try again or contact your trainer. ".$message;
}
unset($_POST);
header("location:displayMessage.php?message=" . $message);
?>