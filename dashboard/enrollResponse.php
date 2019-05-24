<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 24/5/19 1:16 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/purchases.php');
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
// following files need to be included
require_once("../includes/config_paytm.php");
require_once("../includes/encdec_paytm.php");
$uid = $_SESSION['uid'];
$custId = getCustId($uid);

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

if($isValidChecksum == "TRUE") {
    //UNCOMMENT BELOW LINE for DEBUGGING
    print_r($_POST);

    if ($_POST["STATUS"] == "TXN_SUCCESS") {
        echo "<b>Transaction successfull</b>" . "<br/>";
        echo "You will be redirected to your dashboard shortly...";
        $cid = getCourseIdFromOrderId($_POST['ORDERID']);
        enrollInCourse($cid, $uid, $_POST['TXNAMOUNT'],$custId,$_POST['ORDERID'],$con);
        //Process your transaction here as success transaction.
        //Verify amount & order id received from Payment gateway with your application's order id and amount.
        sleep(2);
        header("location:student.php");
    }
    else {
        echo "<b>Transaction failed. You will be redirected to your dashboard shortly</b>" . "<br/>";
        sleep(5);
        header("location:student.php");
    }
}
else {
    echo "<b>Possible tampering prevented. Transaction failed. Please contact help if you think this is an issue. You will be shortly redirected to your dashboard.</b>";
    //Process transaction as suspicious.
    sleep(5);
    header("location:student.php");
}

?>