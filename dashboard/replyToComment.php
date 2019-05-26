<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 25/5/19 11:46 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if(!isset($_POST['replyText'])){
    header("location:displayMessage.php?message=Error Performing Reply Operation: Insufficient Data");
}

$uid = $_SESSION['uid'];
$commentId = $_POST['commentId'];
$replyText = $_POST['replyText'];

//Check if user is authorized to reply
$rid = $_POST['resourceId'];
$cid = getCidFromRid($con, $rid);
if(!isThisStudentsCourse($con, $cid) && !isThisUsersCourse($con, $cid)){
    header("location:displayMessage.php?message=Error Performing Reply Operation: Insufficient Permissions");
}

$insertReplyQuery = "INSERT INTO commentreplies(commentid,uid,replytext) VALUES($commentId, $uid, '$replyText')";
$insertReplyResult = mysqli_query($con, $insertReplyQuery) or die(mysqli_error($con));

$backLocation = resViewFile(getRtypeFromRid($con, $rid))."?rid=$rid";

header("location:".$backLocation);

?>