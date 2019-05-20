<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 17/5/19 2:21 PM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_GET['rid'])){
    $rid = $_GET['rid'];
    echo "hi";
    if (!authToEditResource($con, $rid)) {
        header("location:404.html");
    }

    $resToDeleteQuery = "SELECT * FROM cresources WHERE rid=$rid";

    $resToDeleteResult = mysqli_query($con, $resToDeleteQuery) or die(mysqli_error($con));

    $rid = $_GET['rid'];

    if(mysqli_num_rows($resToDeleteResult)) {
        $resToDeleteData = mysqli_fetch_array($resToDeleteResult);

        switch ($resToDeleteData['rtype']) {
            case RES_NOTE:
                $deleteExecuteQuery = mysqli_query($con, "DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
                break;
            case RES_VIDEO:
                $vaddr = $resToDeleteData['raddr'];
                unlink($vaddr) or die("Error deleting a video resource, rid $rid");
                $deleteExecuteQuery = mysqli_query($con, "DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
                break;
            case RES_FILE:
                $faddr = $resToDeleteData['raddr'];
                unlink($faddr) or die("Error deleting a file resource, rid $rid");
                $deleteExecuteQuery = mysqli_query($con, "DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
                break;
            case RES_YOUTUBE:
                $deleteExecuteQuery = mysqli_query($con, "DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
                break;
            case RES_LINK:
                $deleteExecuteQuery = mysqli_query($con, "DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
                break;
            default:
                die("Error: Undefined resource type in database where rid is $rid. Please contact an admin");
                break;
        }

        $cid = $resToDeleteData['cid'];
        header("location:coursedit.php?cid=$cid");
    } else{
        header("location:404.html");
    }
} else {
    header('location:404.html');
}
?>