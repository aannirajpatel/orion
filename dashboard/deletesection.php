<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 2:43 PM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_GET['cid']) && isset($_GET['section']) && isset($_GET['numsections'])) {
    $cid = $_GET['cid'];

    if(!isThisUsersCourse($con, $cid)){
        header("location:404.html");
    }

    $sectionNumber = $_GET['section'];

    $resToDeleteQuery = "SELECT * FROM cresources WHERE cid=$cid AND section=$sectionNumber";
    $resToDeleteResult = mysqli_query($con, $resToDeleteQuery);

    while($resToDeleteData = mysqli_fetch_array($resToDeleteResult)){
        $rid = $resToDeleteData['rid'];
        switch($resToDeleteData['rtype']){
            case RES_NOTE:
                $deleteExecuteQuery = mysqli_query($con,"DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
                break;
            case RES_VIDEO:
                $vaddr = $resToDeleteData['raddr'];
                unlink($vaddr) or die("Error deleting a video resource, rid $rid");
                $deleteExecuteQuery = mysqli_query($con,"DELETE FROM cresources WHERE rid=$rid") or die(mysqli_error($con));
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
    }

    $numSections = $_GET['numsections'];

    $deleteSectionExecuteQuery = mysqli_query($con, "DELETE FROM csections WHERE cid=".$cid." AND `section`=$sectionNumber") or die(mysqli_error($con));

    $shiftUpExecuteQuery = mysqli_query($con, "UPDATE csections SET section=section-1 WHERE section>$sectionNumber AND cid=".$cid) or die(mysqli_error($con));

    echo "<script>alert('Deleted the section')</script>";

    header("location:coursedit.php?cid=$cid");
}
else{
    header('location:404.html');
}
?>