<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 3:31 PM.
 */
function isThisUsersCourse($con, $cid)
{
    $uid = $_SESSION['uid'];

    $isThisUsersCourseQuery = "SELECT cid,uid FROM ctrainers WHERE uid=$uid AND cid=$cid";

    $isThisUsersCourseResult = mysqli_query($con, $isThisUsersCourseQuery) or die(mysqli_error($con));

    if (mysqli_num_rows($isThisUsersCourseResult) > 0) {
        return 1;
    } else {
        return 0;
    }
}

function isThisStudentsCourse($con, $cid){
    $uid = $_SESSION['uid'];
    $isThisStudentsCourseQuery = "SELECT cid, uid FROM cstudents WHERE uid=$uid AND cid=$cid";
    $isThisUsersCourseResult = mysqli_query($con, $isThisStudentsCourseQuery) or die(mysqli_error($con));

    $temp_var_1 = 0;

    if(mysqli_num_rows($isThisUsersCourseResult)>0){
        $temp_var_1 = 1;
    }

    if($temp_var_1 == 1){
        $checkCoursePublishedQuery = "SELECT published FROM course WHERE cid=$cid";
        $checkCoursePublishedResult = mysqli_query($con, $checkCoursePublishedQuery) or die(mysqli_error($con));
        $checkCoursePublishedData = mysqli_fetch_array($checkCoursePublishedResult);
        $checkCoursePublished = $checkCoursePublishedData['published'];
        if($checkCoursePublished == 1){
            return 1;
        } else{
            return 0;
        }
    }
}

function authToViewResource($con, $rid){
    $getCIDQuery = "SELECT cid FROM cresources WHERE rid = $rid";
    $getCIDResult = mysqli_query($con,$getCIDQuery) or die(mysqli_error($con));
    if(mysqli_num_rows($getCIDResult)>0){
        $getCIDData = mysqli_fetch_array($getCIDResult);
        $cid = $getCIDData['cid'];
        if(isThisStudentsCourse($con, $cid)||isThisUsersCourse($con,$cid)){
            return 1;
        } else {
            return 0;
        }
    } else{
        return 0;
    }
}

function authToEditResource($con, $rid){
    $getCIDQuery = "SELECT cid FROM cresources WHERE rid = $rid";
    $getCIDResult = mysqli_query($con,$getCIDQuery) or die(mysqli_error($con));
    if(mysqli_num_rows($getCIDResult)>0){
        $getCIDData = mysqli_fetch_array($getCIDResult);
        $cid = $getCIDData['cid'];
        if(isThisUsersCourse($con,$cid)){
            return 1;
        } else {
            return 0;
        }
    } else{
        return 0;
    }
}

function isResource($con, $rid, $rtype){
    $rtypeResult = mysqli_query($con, "SELECT rtype FROM cresources WHERE rid=".$rid);
    if(mysqli_num_rows($rtypeResult)>0){
        $rtypeData = mysqli_fetch_array($rtypeResult);
        $actualrtype = $rtypeData['rtype'];
        if($actualrtype == $rtype){
            return 1;
        } else{
            return 0;
        }
    } else{
        return 0;
    }
}

function isCoursePublished($con, $cid){
    $checkCoursePublishedQuery = "SELECT published FROM course WHERE cid=$cid";
    $checkCoursePublishedResult = mysqli_query($con, $checkCoursePublishedQuery) or die(mysqli_error($con));
    $checkCoursePublishedData = mysqli_fetch_array($checkCoursePublishedResult);
    $checkCoursePublished = $checkCoursePublishedData['published'];
    if($checkCoursePublished == 1){
        return 1;
    } else{
        return 0;
    }
}

function isAuditing($con, $cid, $uid){
    $isAuditingQuery = "SELECT cid FROM audit WHERE uid=$uid AND cid=$cid";
    $isAuditingResult = mysqli_query($con, $isAuditingQuery) or die(mysqli_error($con));
    if(mysqli_num_rows($isAuditingResult)==1){
        return 1;
    } else{
        return 0;
    }
}
?>