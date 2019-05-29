<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 29/5/19 12:22 PM.
 */

$totalNewComms = 0;

define("NEW_QUESTIONS_COUNT_INDEX",0);
define("NEW_ANSWERS_COUNT_INDEX",1);

if(getUserType($con, $uid)==1){
    function newComms($con, $cid, $uid){
        $lastViewedTimeQuery = "SELECT * FROM lastviewedqna WHERE cid=$cid and uid=$uid";
        $lastViewedTimeResult = mysqli_query($con, $lastViewedTimeQuery) or die(mysqli_error($con));
        if(mysqli_num_rows($lastViewedTimeResult) == 1){
            $lastViewedTimeData = mysqli_fetch_array($lastViewedTimeResult);
            $lastViewedTime = $lastViewedTimeData['lastviewed'];
        } else{
            $lastViewedTime = "0000-00-00 00:00:00";
        }

        $newQuestionsQuery = "SELECT questionid FROM question, user WHERE(user.uid=$uid AND question.cid=$cid AND question.dateofquestion>'$lastViewedTime')";
        $newQuestionsResult = mysqli_query($con, $newQuestionsQuery) or die(mysqli_error($con));
        $newQuestions = mysqli_num_rows($newQuestionsResult);

        $newAnswersQuery = "SELECT answerid FROM answer, user, question WHERE(answer.questionid = question.questionid AND question.cid=$cid AND user.uid = $uid AND answer.dateofanswer > '$lastViewedTime')";
        $newAnswersResult = mysqli_query($con, $newAnswersQuery) or die(mysqli_error($con));
        $newAnswers = mysqli_num_rows($newAnswersResult);

        return array($newQuestions, $newAnswers);
    }

    $courseListQuery = "SELECT cid FROM ctrainers WHERE uid=$uid";

    $courseListResult = mysqli_query($con, $courseListQuery) or die(mysqli_error($con));
    while ($courseListQueryData = mysqli_fetch_array($courseListResult)) {
        $cid = $courseListQueryData['cid'];
        $cid = $courseListQueryData['cid'];
        $commBadgeData = newComms($con, $cid, $uid);
        $totalNewComms += $commBadgeData[NEW_QUESTIONS_COUNT_INDEX];
        $totalNewComms += $commBadgeData[NEW_ANSWERS_COUNT_INDEX];
    }
}

if(getUserType($con, $uid)==0){
    function newComms($con, $cid, $uid)
    {

        $lastViewedTimeQuery = "SELECT * FROM lastviewedqna WHERE cid=$cid and uid=$uid";
        $lastViewedTimeResult = mysqli_query($con, $lastViewedTimeQuery) or die(mysqli_error($con));
        if (mysqli_num_rows($lastViewedTimeResult) == 1) {
            $lastViewedTimeData = mysqli_fetch_array($lastViewedTimeResult);
            $lastViewedTime = $lastViewedTimeData['lastviewed'];
        } else {
            $lastViewedTime = "0000-00-00 00:00:00";
        }

        $newQuestionsQuery = "SELECT questionid FROM question, user WHERE(user.uid=$uid AND question.cid=$cid AND question.dateofquestion>'$lastViewedTime')";
        $newQuestionsResult = mysqli_query($con, $newQuestionsQuery) or die(mysqli_error($con));
        $newQuestions = mysqli_num_rows($newQuestionsResult);

        $newAnswersQuery = "SELECT answerid FROM answer, user, question WHERE(answer.questionid = question.questionid AND question.cid=$cid AND user.uid = $uid AND answer.dateofanswer > '$lastViewedTime')";
        $newAnswersResult = mysqli_query($con, $newAnswersQuery) or die(mysqli_error($con));
        $newAnswers = mysqli_num_rows($newAnswersResult);

        return array($newQuestions, $newAnswers);
    }

    $totalNewComms = 0;

    define("NEW_QUESTIONS_COUNT_INDEX",0);
    define("NEW_ANSWERS_COUNT_INDEX",1);

    $courseListQuery = "SELECT cid FROM audit WHERE uid=$uid UNION SELECT cid FROM cstudents WHERE uid=$uid ORDER BY cid desc";
    $courseListResult = mysqli_query($con, $courseListQuery) or die(mysqli_error($con));

    while ($courseListQueryData = mysqli_fetch_array($courseListResult)) {
        $cid = $courseListQueryData['cid'];
        $commBadgeData = newComms($con, $cid, $uid);
        $totalNewComms += $commBadgeData[NEW_QUESTIONS_COUNT_INDEX];
        $totalNewComms += $commBadgeData[NEW_ANSWERS_COUNT_INDEX];
    }
}