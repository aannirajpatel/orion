<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 20/5/19 1:09 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if(!isset($_GET['cid'])){
    header("location:404.php");
} elseif(!isThisUsersCourse($con, $_GET['cid'])){
    die("Error publishing course. Unauthorized access attempt detected. Please contact admin if you think this should not be so.");
}
$cid = $_GET['cid'];
$publishQuery = "UPDATE course SET published=1, dateofpublish=CURRENT_TIMESTAMP WHERE cid=$cid AND published=0";
$publishResult = mysqli_query($con, $publishQuery) or die(mysqli_error($con));
header("location:trainer.php");
?>