<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 24/5/19 12:21 PM.
 */
function getOrderId($uid, $cid)
{
    $orderId = "ORDER" . str_pad($uid . "C" . $cid . "T" . time(), 45, '0', STR_PAD_LEFT);
    if (strlen($orderId) != 50) {
        die("Order ID Cannot anything else than a 50 character string. Please check getOrderId params");
    }
    return $orderId;
}

function getCustId($uid)
{
    $custId = "CUST" . str_pad($uid, 60, '0', STR_PAD_LEFT);
    if (strlen($custId) != 64) {
        die("Cust ID Cannot anything else than a 64 character string. Please check getCustId params");
    }
    return $custId;
}

function enrollInCourse($cid, $uid, $cost, $custid, $oid, $con)
{
    //start transaction
    $startTransaction = mysqli_query($con, "START TRANSACTION") or die(mysqli_error($con));
    //cpurchases entry
    $coursePurchaseQuery = "INSERT INTO cpurchases(cid,uid,cost,cust_id,order_id) VALUES($cid,$uid,$cost,'$custid','$oid')";
    $coursePurchaseResult = mysqli_query($con, $coursePurchaseQuery) or die(mysqli_error($con));
    //cstudents entry
    $courseEnrollQuery = "INSERT INTO cstudents(cid,uid) VALUES($cid,$uid) ON DUPLICATE KEY UPDATE cid=$cid, uid=$uid";
    $courseEnrollResult = mysqli_query($con, $courseEnrollQuery) or die(mysqli_error($con));
    //commit transaction
    $commitTransaction = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
}

function getCourseCost($cid, $con)
{
    $getCourseCostQuery = "SELECT cost FROM course WHERE cid=$cid";
    $getCourseCostResult = mysqli_query($con, $getCourseCostQuery) or die("");
    if (mysqli_num_rows($getCourseCostResult) == 0) {
        die("Error fetching course cost. Please contact admin.");
    }
    $getCourseCostData = mysqli_fetch_array($getCourseCostResult);
    $cost = $getCourseCostData['cost'];
    return $cost;
}

function getTxnAmount($floatAmt)
{
    return $floatAmt;
    $txnAmt = str_pad(number_format((float)$floatAmt, 2, '.', ''), 10, '0', STR_PAD_LEFT);
    return $txnAmt;
}

function getCourseName($cid, $con)
{
    $getCourseNameQuery = "SELECT cname FROM course WHERE cid=$cid";
    $getCourseNameResult = mysqli_query($con, $getCourseNameQuery) or die("");
    if (mysqli_num_rows($getCourseNameResult) == 0) {
        die("Error fetching course cname. Please contact admin.");
    }
    $getCourseNameData = mysqli_fetch_array($getCourseNameResult);
    return $getCourseNameData['cname'];
}

function enrollInFreeCourse($cid, $uid, $custid, $oid, $con)
{
    //start transaction
    $startTransaction = mysqli_query($con, "START TRANSACTION") or die(mysqli_error($con));
    //cpurchases entry
    $coursePurchaseFreeQuery = "INSERT INTO cpurchases(cid,uid,cost,cust_id,order_id) VALUES($cid,$uid,0,'$custid','$oid')";
    $coursePurchaseFreeResult = mysqli_query($con, $coursePurchaseFreeQuery) or die(mysqli_error($con));
    //cstudents entry
    $courseEnrollFreeQuery = "INSERT INTO cstudents(cid,uid) VALUES($cid,$uid) ON DUPLICATE KEY UPDATE cid=$cid, uid=$uid";
    $courseEnrollFreeResult = mysqli_query($con, $courseEnrollFreeQuery) or die(mysqli_error($con));
    //commit transaction
    $commitTransaction = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
}

function getCourseIdFromOrderId($orderId)
{
    $custIdArray = preg_split("/C/", $orderId);
    $orderId = $custIdArray[1];
    $custIdArray = preg_split("/T/", $orderId);
    $custId = $custIdArray[0];
    return (int)$custId;
}

function getPid($pid)
{
    return "P" . str_pad($pid, 9, '0', STR_PAD_LEFT);
}

function purchased($con, $cid, $uid)
{
    $purchasedQuery = "SELECT pid FROM cpurchases WHERE cid=$cid AND uid=$uid";
    $purchasedResult = mysqli_query($con, $purchasedQuery) or die(mysqli_error($con));
    if (mysqli_num_rows($purchasedResult) != 0) {
        return 1;
    } else {
        return 0;
    }
}