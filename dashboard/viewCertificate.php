<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 26/5/19 1:02 PM.
 */
require('../includes/db.php');
require('../includes/resconfig.php');

if (!isset($_GET['cid']) || !isset($_GET['uid'])) {
    die("Incomplete details for fetching the certificate. Please check URL.");
}
if (!isCourseCompleted($con, $_GET['cid'], $_GET['uid'])) {
    die("You have not completed this course.");
}
$uid = $_GET['uid'];
$cid = $_GET['cid'];

$getFullNameQuery = "SELECT fname, lname FROM user WHERE uid=$uid";
$getFullNameResult = mysqli_query($con, $getFullNameQuery) or die(mysqli_error($con));
$getFullNameData = mysqli_fetch_array($getFullNameResult);
$fullName = $getFullNameData['fname'] . " " . $getFullNameData['lname'];

$getCourseNameQuery = "SELECT cname FROM course WHERE cid=$cid";
$getCourseNameResult = mysqli_query($con, $getCourseNameQuery) or die(mysqli_error($con));
$getCourseNameData = mysqli_fetch_array($getCourseNameResult);
$cname = $getCourseNameData['cname'];

$getCompletionQuery = "SELECT * FROM completedcourses WHERE cid=$cid AND uid=$uid";
$getCompletionResult = mysqli_query($con, $getCompletionQuery) or die(mysqli_error($con));
$getCompletionData = mysqli_fetch_array($getCompletionResult);
$dateofcomplete = $getCompletionData['dateofcomplete'];
$dateofcomplete = date("M d, Y", strtotime($dateofcomplete));

$certificatePermanentLink = "http://localhost/orion/dashboard/viewCertificate.php?cid=$cid&uid=$uid";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <script
            src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/certificateStyle.css" type="text/css" charset="utf-8"/>
</head>
<body>

    <div style="width:29.7cm; height:20cm; padding:20px; text-align:center; border: 10px solid #787878">
        <div style="width:28cm; height:18.6cm; padding:20px; text-align:center; border: 5px solid #787878">
            <span style="font-size:100px; font-weight:bold" id="certificateHeader">Certificate of Completion</span>
            <br><br>
            <span style="font-size:50px"><i>This is to certify that</i></span>
            <br><br>
            <span style="font-size:60px"><b><?php echo $fullName; ?></b></span><br/><br/>
            <span style="font-size:50px"><i>has completed the course</i></span> <br/><br/>
            <span style="font-size:60px"><?php echo $cname; ?></span> <br/><br/>
            <br/><br/><br/><br/>
            <span style="font-size:50px"><i>dated</i></span><br>
            <span style="font-size:60px"><?php echo $dateofcomplete; ?></span>
            <br><br>
            Verify by going to <a
                    href="<?php echo $certificatePermanentLink; ?>"><?php echo $certificatePermanentLink ?></a>
        </div>
    </div>
<a href="" id="printBtn" onclick="printCertificate();">Save as PDF</a>
</body>
</html>
<script>
    function printCertificate() {
        $('#printBtn').hide();
        window.print();
    }
</script>