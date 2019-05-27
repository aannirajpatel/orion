<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 26/5/19 3:51 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/resconfig.php');

if(!isset($_GET['cid'])){
    $message = "You cannot audit in this course.";
    header("location:displayMessage.php?message=".$message);
}

$cid = $_GET['cid'];
$uid = $_SESSION['uid'];

$auditCourseQuery = "INSERT INTO audit(cid,uid) VALUES ($cid,$uid) ON DUPLICATE KEY UPDATE cid=$cid";
$auditCourseResult = mysqli_query($con, $auditCourseQuery) or die(mysqli_error($con));

header("location:viewcourse.php?cid=$cid");
?>