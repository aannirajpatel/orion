<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 11:53 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_POST['title'])) {
    $rid = $_POST['rid'];

    if(!authToEditResource($con,$rid) || !isResource($con, $rid, RES_NOTE)){
        header("location:404.php");
    }

    $title = $_POST['title'];
    $note = $_POST['note'];
    $rtype = RES_NOTE;
    $insertNoteQuery = "UPDATE cresources SET rtext='$title', rdata='".mysqli_real_escape_string($con,$note)."' WHERE rid=$rid";
    $insertVideoResult = mysqli_query($con, $insertNoteQuery) or die(mysqli_error($con));
    $message="<a class='btn btn-success' href='coursedit.php?cid=$cid'>Go back to course editor</a>";
    $_SESSION['message']=$message;
    header_remove();
    header("location:displayMessage.php");
} else {
    die("not ok");
}
?>