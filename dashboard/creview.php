<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 22/5/19 4:04 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
if(isset($_POST['uid']) && isThisStudentsCourse($con, $_POST['cid'])){
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);
    $rating = $_POST['rating'];
    $reviewQuery = "INSERT INTO creviews(cid, uid, rtitle, rdesc, rating) VALUES ($cid, $uid, '$title', '$desc', $rating) ON DUPLICATE KEY UPDATE rtitle='$title', rdesc='$desc', rating=$rating";
    $reviewExecute = mysqli_query($con, $reviewQuery) or die(mysqli_error($con));
    header("location: viewcourse.php?cid=".$cid);
}
else{
    header("location:404.html");
}

?>

