<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 3:58 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_POST['title'])) {
    $rid = $_POST['rid'];

    if(!authToEditResource($con,$rid) || !isResource($con, $rid, RES_LINK)){
        header("location:404.php");
    }

    $title = $_POST['title'];
    $linkaddress = $_POST['linkaddress'];

    $insertLinkQuery = "UPDATE cresources SET rtext='$title', raddr='".mysqli_real_escape_string($con,$linkaddress)."' WHERE rid=$rid";
    $insertLinkResult = mysqli_query($con, $insertLinkQuery) or die(mysqli_error($con));
    $message="<a class='btn btn-success' href='coursedit.php?cid=$cid'>Go back to course editor</a>";
    $_SESSION['message']=$message;
    header_remove();
    header("location:displayMessage.php");
} else {
    die("not ok");
}
?>