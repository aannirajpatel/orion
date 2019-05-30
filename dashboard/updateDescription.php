<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 17/5/19 11:51 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
if (isset($_POST['cdesc']) && isset($_POST['cid'])) {
    //Update course description
    $cid = $_POST['cid'];
    if(!isThisUsersCourse($con,$cid)){
        die("Error updating description: you might not own this course. Please contact an admin.");
    }
    $cdesc = mysqli_real_escape_string($con,$_POST['cdesc']);
    $updateCourseDescriptionQuery = "UPDATE course SET cdesc='$cdesc' WHERE cid=$cid";
    $updateCourseDescriptionResult = mysqli_query($con, $updateCourseDescriptionQuery) or die(mysqli_error($con));
    header("location:coursedit.php?cid=$cid");
}
?>