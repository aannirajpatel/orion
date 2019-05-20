<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 17/5/19 11:53 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
if (isset($_POST['csyllabus']) && isset($_SESSION['cid'])) {
    //Update course syllabus
    $cid = $_SESSION['cid'];

    if(!isThisUsersCourse($con,$cid)){
        die("Error updating syllabus: you might not own this course. Please contact an admin.");
    }

    $csyllabus = mysqli_real_escape_string($con,$_POST['csyllabus']);

    $updateCourseSyllabusQuery = "INSERT INTO csyllabus(cid,csyllabus) VALUES ($cid, '$csyllabus') ON DUPLICATE KEY UPDATE csyllabus='$csyllabus'";
    $updateCourseSyllabusResult = mysqli_query($con, $updateCourseSyllabusQuery) or die(mysqli_error($con));

    header("location:coursedit.php?cid=$cid");
}