<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 2:05 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_POST['rid'])) {
    $rid = $_POST['rid'];
    $cid = getCidFromRid($con, $rid);
    $sectionNumber = getSectionFromRid($con, $rid);
    $fname = $_POST['fname'];
    $rtype = RES_FILE;
    $faddr = getRaddrFromRid($con, $rid);
    if (!isThisUsersCourse($con, $cid) || !isResource($con, $rid, $rtype)) {
        header("location:404.php");
    }
    if ($_FILES['fupload']['error'] != 4) {

        if ($_FILES['fupload']['error'] != 0 || !isThisUsersCourse($con, $cid)) {
            echo "<br>" . $_FILES['fupload']['error'] . " " . isThisUsersCourse($con, $cid) . "<br>";
            echo "Error uploading. Please try adding the file again. Click <a href='editFile.php?rid=$rid'> here to go back.</a>";
        }
        if (file_exists($faddr)) {
            unlink($faddr);
        }
        $extensionSplitter = preg_split("/\./", basename($_FILES['fupload']['name']), 2);
        if (count($extensionSplitter) == 2) {
            if ($fname == "") {
                $fname = basename($_FILES['fupload']['name']);
            }
            $extension = $extensionSplitter[1];
        } else {
            if ($fname == "") {
                $fname = basename($_FILES['fupload']['name']);
            }
            $extension = "mp4";
        }
        $faddr = "./res/$cid/";
        if (!is_dir($faddr)) {
            mkdir($faddr, 0755, true);
        }
        $faddr = $faddr . "$sectionNumber-$rid." . $extension;
//echo $faddr;
        move_uploaded_file($_FILES['fupload']['tmp_name'], $faddr) or die("Error uploading. Please try adding the file again. Click <a href='editFile.php?rid=$rid'> here to go back.");
    } else {

        $insertFileQuery = "UPDATE cresources SET rtext='$fname',raddr='$faddr' WHERE rid=$rid";
        $insertFileResult = mysqli_query($con, $insertFileQuery) or die(mysqli_error($con));
    }
    $message="<a class='btn btn-success' href='coursedit.php?cid=$cid'>Go back to course editor</a>";
    $_SESSION['message']=$message;
    header_remove();
    header("location:displayMessage.php");
} else {
    die("not ok");
}
?>