<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 31/5/19 5:13 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
if (isset($_POST['cid'])) {
    if (isset($_POST['category'])) {
        //Update course description
        $cid = $_POST['cid'];
        if (!isThisUsersCourse($con, $cid)) {
            die("Error updating category: you might not own this course. Please contact an admin.");
        }
        $category = mysqli_real_escape_string($con, $_POST['category']);
        if (in_array($category, array("Engineering", "Business", "Programming"))) {
            $updateCategoryQuery = "UPDATE course SET category='$category' WHERE cid=$cid";
            $updateCategoryResult = mysqli_query($con, $updateCategoryQuery) or die(mysqli_error($con));
        }
    }
    if ($_FILES['courseImg']['error']==0) {
        if (!isThisUsersCourse($con, $cid)) {
            die("Error updating course image: you might not own this course. Please contact an admin.");
        }
        $checkExistsQuery = "SELECT cimg FROM course WHERE cid=$cid";
        $checkExistsResult = mysqli_query($con, $checkExistsQuery) or die(mysqli_error($con));
        $checkExistsData = mysqli_fetch_array($checkExistsResult);
        $fileName = $checkExistsData['cimg'];
        if(file_exists($fileName)){
            unlink($fileName);
        }
        $cimg = "./res/$cid/courseImg/";

        $extensionSplitter = preg_split("/\./", basename($_FILES['courseImg']['name']), 2);
        if (count($extensionSplitter) == 2) {
            $extension = $extensionSplitter[1];
        } else {
            $extension = "jpg";
        }
        $cimg = "./res/courseImgs";
        if (!is_dir($cimg)) {
            mkdir($cimg, 0755, true);
        }
        $cimg = $cimg."/$cid.".$extension;
//echo $cimg;
        move_uploaded_file($_FILES['courseImg']['tmp_name'], $cimg) or die("Error uploading. Please try adding the file again. Click <a href='coursedit.php?cid=$cid'> here to go back.");
        $updateImgQuery = "UPDATE course SET cimg = '$cimg' WHERE cid=$cid";
        $updateImgResult = mysqli_query($con, $updateImgQuery) or die(mysqli_error($con));
    }
    header("location:coursedit.php?cid=$cid");
} else{
    header("location:404.php");
}
?>