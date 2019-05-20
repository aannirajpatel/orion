<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 15/5/19 2:39 PM.
 */
define("RES_NOTE", 0);
define("RES_VIDEO", 1);
define("RES_FILE", 2);
define("RES_YOUTUBE", 3);
define("RES_LINK", 4);

function resViewFile($rtype){
    switch($rtype){
        case RES_NOTE:
            return "viewNote.php";
        case RES_VIDEO:
            return "viewVideo.php";
        case RES_FILE:
            return "viewFile.php";
        case RES_YOUTUBE:
            return "viewYoutubeLink.php";
        case RES_LINK:
            return "viewLink.php";
    }
}

function resEditFile($rtype){
    switch($rtype){
        case RES_NOTE:
            return "editNote.php";
        case RES_VIDEO:
            return "editVideo.php";
        case RES_FILE:
            return "editFile.php";
        case RES_YOUTUBE:
            return "editYoutubeLink.php";
        case RES_LINK:
            return "editLink.php";
    }
}

function getCidFromRid($con, $rid){
    $getCidQuery = "SELECT cid FROM cresources WHERE rid=$rid";
    $getCidResult = mysqli_query($con, $getCidQuery) or die(mysqli_error($con));
    $getCidData = mysqli_fetch_array($getCidResult);
    return $getCidData['cid'];
}

function getSectionFromRid($con, $rid){
    $getSectionQuery = "SELECT section FROM cresources WHERE rid=$rid";
    $getSectionResult = mysqli_query($con, $getSectionQuery) or die(mysqli_error($con));
    $getSectionData = mysqli_fetch_array($getSectionResult);
    return $getSectionData['section'];
}

function getRaddrFromRid($con, $rid){
    $getRaddrQuery = "SELECT raddr FROM cresources WHERE rid=$rid";
    $getRaddrResult = mysqli_query($con, $getRaddrQuery) or die(mysqli_error($con));
    $getRaddrData = mysqli_fetch_array($getRaddrResult);
    return $getRaddrData['raddr'];
}

function getRtextFromRid($con, $rid){
    $getRtextQuery = "SELECT rtext FROM cresources WHERE rid=$rid";
    $getRtextResult = mysqli_query($con, $getRtextQuery) or die(mysqli_error($con));
    $getRtextData = mysqli_fetch_array($getRtextResult);
    return $getRtextData['rtext'];
}

?>