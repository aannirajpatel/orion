<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 25/5/19 12:05 PM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if(!isset($_POST['commentText'])){
    header("location:displayMessage.php?message=Error Performing Comment Operation: Insufficient Data");
}

$uid = $_SESSION['uid'];
$commentText = $_POST['commentText'];
//Check if user is authorized to comment
$rid = $_POST['resourceId'];
$cid = getCidFromRid($con, $rid);
if(!isThisStudentsCourse($con, $cid) && !isThisUsersCourse($con, $cid)){
    header("location:displayMessage.php?message=Error Performing Comment Operation: Insufficient Permissions");
}

$insertCommentQuery = "INSERT INTO rescomments(rid,uid,commtext) VALUES($rid, $uid, '$commentText')";
$insertCommentResult = mysqli_query($con, $insertCommentQuery) or die(mysqli_error($con));

$backLocation = resViewFile(getRtypeFromRid($con, $rid))."?rid=$rid";

header("location:".$backLocation);

?>