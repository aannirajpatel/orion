<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 1:21 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_POST['rid'])) {
    $rid = $_POST['rid'];
    $cid = getCidFromRid($con, $rid);
    $sectionNumber = getSectionFromRid($con, $rid);
    $vname = $_POST['vname'];
    $rtype = RES_VIDEO;
    $vaddr = getRaddrFromRid($con, $rid);
    if (!isThisUsersCourse($con, $cid) || !isResource($con, $rid, $rtype)) {
        header("location:404.php");
    }
    if ($_FILES['vupload']['error']!=4) {

        if ($_FILES['vupload']['error'] != 0 || !isThisUsersCourse($con, $cid)) {
            echo "<br>" . $_FILES['vupload']['error'] . " " . isThisUsersCourse($con, $cid) . "<br>";
            echo "Error uploading. Please try adding the video again. Click <a href='editVideo.php?rid=$rid'> here to go back.</a>";
        }
        if (file_exists($vaddr)) {
            unlink($vaddr);
        }
        $extensionSplitter = preg_split("/\./", basename($_FILES['vupload']['name']), 2);
        if (count($extensionSplitter) == 2) {
            if ($vname == "") {
                $vname = basename($_FILES['vupload']['name']);
            }
            $extension = $extensionSplitter[1];
        } else {
            if ($vname == "") {
                $vname = basename($_FILES['vupload']['name']);
            }
            $extension = "mp4";
        }
        $vaddr = "./res/$cid/";
        if (!is_dir($vaddr)) {
            mkdir($vaddr, 0755, true);
        }
        $vaddr = $vaddr . "$sectionNumber-$rid." . $extension;
//echo $vaddr;
        move_uploaded_file($_FILES['vupload']['tmp_name'], $vaddr) or die("Error uploading. Please try adding the video again. Click <a href='editVideo.php?rid=$rid'> here to go back.");
        $insertVideoQuery = "UPDATE cresources SET rtext='$vname',raddr='$vaddr' WHERE rid=$rid";
        $insertVideoResult = mysqli_query($con, $insertVideoQuery) or die(mysqli_error($con));
    }
    else{
        $insertVideoQuery = "UPDATE cresources SET rtext='$vname',raddr='$vaddr' WHERE rid=$rid";
        $insertVideoResult = mysqli_query($con, $insertVideoQuery) or die(mysqli_error($con));
    }

        $message="<a class='btn btn-success' href='coursedit.php?cid=$cid'>Go back to course editor</a>";
        $_SESSION['message']=$message;
        header_remove();
        header("location:displayMessage.php");
    } else {
    die("not ok");
}
?>