<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 15/5/19 2:39 PM.
 */
define("RES_NOTE", 0);
define("RES_VIDEO", 1);
define("RES_FILE", 2);
define("RES_YOUTUBE", 3);
define("RES_LINK", 4);
define("RES_QUIZ",5);

function resViewFile($rtype)
{
    switch ($rtype) {
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
        case RES_QUIZ:
            return "viewQuiz.php";
    }
}

function resEditFile($rtype)
{
    switch ($rtype) {
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
        case RES_QUIZ:
            return "editQuiz.php";
    }
}

function getCidFromRid($con, $rid)
{
    $getCidQuery = "SELECT cid FROM cresources WHERE rid=$rid";
    $getCidResult = mysqli_query($con, $getCidQuery) or die(mysqli_error($con));
    $getCidData = mysqli_fetch_array($getCidResult);
    return $getCidData['cid'];
}

function getSectionFromRid($con, $rid)
{
    $getSectionQuery = "SELECT section FROM cresources WHERE rid=$rid";
    $getSectionResult = mysqli_query($con, $getSectionQuery) or die(mysqli_error($con));
    $getSectionData = mysqli_fetch_array($getSectionResult);
    return $getSectionData['section'];
}

function getRaddrFromRid($con, $rid)
{
    $getRaddrQuery = "SELECT raddr FROM cresources WHERE rid=$rid";
    $getRaddrResult = mysqli_query($con, $getRaddrQuery) or die(mysqli_error($con));
    $getRaddrData = mysqli_fetch_array($getRaddrResult);
    return $getRaddrData['raddr'];
}

function getRtextFromRid($con, $rid)
{
    $getRtextQuery = "SELECT rtext FROM cresources WHERE rid=$rid";
    $getRtextResult = mysqli_query($con, $getRtextQuery) or die(mysqli_error($con));
    $getRtextData = mysqli_fetch_array($getRtextResult);
    return $getRtextData['rtext'];
}

function getRtypeFromRid($con, $rid)
{
    $getRtypeQuery = "SELECT rtype FROM cresources WHERE rid=$rid";
    $getRtypeResult = mysqli_query($con, $getRtypeQuery) or die(mysqli_error($con));
    $getRtypeData = mysqli_fetch_array($getRtypeResult);
    return $getRtypeData['rtype'];
}

function viewedResource($con, $rid, $uid)
{
    $viewedResourceQuery = "INSERT INTO viewresource(rid, uid) VALUES ($rid, $uid) ON DUPLICATE KEY UPDATE rid=$rid";
    $viewedResourceResult = mysqli_query($con, $viewedResourceQuery) or die(mysqli_error($con));
    //Check for completion every time a resource is viewed
    isCourseCompleted($con, getCidFromRid($con, $rid), $uid);
}

function resHasBeenViewed($con, $rid, $uid)
{
    $resHasBeenViewedQuery = "SELECT * FROM viewresource WHERE rid=$rid AND uid=$uid";
    $resHasBeenViewedResult = mysqli_query($con, $resHasBeenViewedQuery) or die(mysqli_error($con));
    if (mysqli_num_rows($resHasBeenViewedResult) == 0) {
        return 0;
    } else {
        return 1;
    }
}

function makeEntryInCompletedCourses($con, $cid, $uid)
{
    $quizRtype = RES_QUIZ;
    $numQuizQuery = "SELECT count(*) AS numquiz FROM cresources WHERE cid=$cid AND rtype=$quizRtype";
    $numQuizResult = mysqli_query($con, $numQuizQuery);
    $numQuiz= mysqli_num_rows($numQuizResult);
    if($numQuiz==0){
        $completionEntryQuery = "INSERT INTO completedcourses(cid, uid,score) VALUES($cid, $uid,-1) ON DUPLICATE KEY UPDATE cid=$cid;";
        $completionEntryResult = mysqli_query($con, $completionEntryQuery) or die(mysqli_error($con));
    } else{
        $quizQuery = "SELECT rid FROM cresources WHERE cid=$cid AND rtype=$quizRtype";
        $quizResult = mysqli_query($con, $quizQuery) or die(mysqli_query($con));
        $score = 0;
        while($quizData = mysqli_fetch_array($quizResult)){
            $quizRid = $quizData['rid'];
            $scoreQuery = "SELECT max(score) AS bestscore FROM quizattempts WHERE uid=$uid AND quizid=$quizRid";
            $scoreResult = mysqli_query($con, $scoreQuery) or die(mysqli_error($con));
            $scoreData = mysqli_fetch_array($scoreResult);
            $score+= $scoreData['bestscore'];
        }
        $score = $score/$numQuiz;
        $completionEntryQuery = "INSERT INTO completedcourses(cid, uid,score) VALUES($cid, $uid, $score) ON DUPLICATE KEY UPDATE cid=$cid;";
        $completionEntryResult = mysqli_query($con, $completionEntryQuery) or die(mysqli_error($con));
    }

}

function isEntryInCompletedCourses($con, $cid, $uid)
{
    $completedEntryQuery = "SELECT * FROM completedcourses WHERE cid=$cid AND uid=$uid";
    $completedEntryResult = mysqli_query($con, $completedEntryQuery) or die($con);
    if (mysqli_num_rows($completedEntryResult) == 1) {
        return 1;
    } else {
        return 0;
    }
}

function isCourseCompleted($con, $cid, $uid)
{
    $courseCompletionQuery = "SELECT cresources.rid FROM course INNER JOIN viewresource INNER JOIN cresources ON (course.cid=$cid AND course.cid = cresources.cid AND cresources.rid=viewresource.rid AND viewresource.uid=$uid)";
    $courseCompletionResult = mysqli_query($con, $courseCompletionQuery) or die(mysqli_error($con));

    $resCountQuery = "SELECT rid FROM cresources WHERE cid=$cid";
    $resCountResult = mysqli_query($con, $resCountQuery) or die(mysqli_error($con));

    if (mysqli_num_rows($courseCompletionResult) == mysqli_num_rows($resCountResult) && mysqli_num_rows($courseCompletionResult) > 0) {
        makeEntryInCompletedCourses($con, $cid, $uid);
        return 1;
    } else {
        if (isEntryInCompletedCourses($con, $cid, $uid) == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}
?>