<?php

session_start();

if( (!isset($_SESSION['email'])) || (isset($_SESSION['access_token_expiry']) && $_SESSION['access_token_expiry'] < time())){
	session_destroy();

	//Uncomment line below to debug auths
	//die(print_r($_SESSION));
    if(isset($_GET[cid])){
        $fwLink = "viewcourse.php?cid=".$_GET['cid'];
    }
	header('location: ../login?fwLink='.$fwLink);
}

define("STUDENT",0);
define("TRAINER",1);
define("ADMIN",2);

function getUserType($con, $uid){
    $userTypeQuery = "SELECT type FROM user WHERE uid=".$uid;
    $userTypeResult = mysqli_query($con, $userTypeQuery);
    $userTypeData = mysqli_fetch_array($userTypeResult);
    return $userTypeData['type'];
}
//echo $_SESSION['access_token_expiry'];
?>