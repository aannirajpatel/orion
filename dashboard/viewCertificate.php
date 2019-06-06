<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 26/5/19 1:02 PM.
 */
require('../includes/db.php');
require('../includes/resconfig.php');
require('../includes/purchases.php');

function isThisStudentsCourse($con, $cid, $uid)
{
    $isThisStudentsCourseQuery = "SELECT cid, uid FROM cstudents WHERE uid=$uid AND cid=$cid";
    $isThisUsersCourseResult = mysqli_query($con, $isThisStudentsCourseQuery) or die(mysqli_error($con));

    $temp_var_1 = 0;

    if (mysqli_num_rows($isThisUsersCourseResult) > 0) {
        $temp_var_1 = 1;
    }

    if ($temp_var_1 == 1) {
        $checkCoursePublishedQuery = "SELECT published FROM course WHERE cid=$cid";
        $checkCoursePublishedResult = mysqli_query($con, $checkCoursePublishedQuery) or die(mysqli_error($con));
        $checkCoursePublishedData = mysqli_fetch_array($checkCoursePublishedResult);
        $checkCoursePublished = $checkCoursePublishedData['published'];
        if ($checkCoursePublished == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!isset($_GET['cid']) || !isset($_GET['uid'])) {
    die("Incomplete details for fetching the certificate. Please check URL.");
}
if (!isCourseCompleted($con, $_GET['cid'], $_GET['uid'])) {
    die("You have not completed this course.");
}

$uid = $_GET['uid'];
$cid = $_GET['cid'];

if (!isThisStudentsCourse($con, $cid, $uid)) {
    die("Please purchase the course from the course page. Click <a href='viewcourse.php?cid=$cid'>here</a> to visit the course page.");
}

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
if ($getCompletionData['score'] != -1) {
    $score = $getCompletionData['score'];
    $scoreMessage = "with a grade of $score%";
}
$certificatePermanentLink = "http://localhost/orion/dashboard/viewCertificate.php?cid=$cid&uid=$uid";

$authorQuery = "SELECT user.uid, fname, lname FROM user INNER JOIN ctrainers ON (ctrainers.cid=$cid AND user.uid=ctrainers.uid) INNER JOIN course ON (course.cid=ctrainers.cid)";
$authorResult = mysqli_query($con, $authorQuery) or die(mysqli_error($con));
if (mysqli_num_rows($authorResult) > 1) {
    $authorPlurality = "s";
} else {
    $authorPlurality = "";
}
$authors = "";
$numAuthors = mysqli_num_rows($authorResult);
$authorNumber = 1;
while ($authorData = mysqli_fetch_array($authorResult)) {
    $authors = $authors . $authorData['fname'] . " " . $authorData['lname'];
    $authorNumber++;
    if ($authorNumber < $numAuthors - 1) {
        $authors = $authors . ", ";
    }
    if ($authorNumber == $numAuthors - 1) {
        $authors = $authors . " and ";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <script
            src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <link rel="stylesheet" href="./css/certificateStyle.css" type="text/css" charset="utf-8"/>
    <style>
        #warning-message {
            background-color: dodgerblue;
            color:white;
            display: none;
        }

        @media only screen and (orientation: portrait) {
            #wrapper {
                display: none;
            }
            #printBtn{
                display: none;
            }

            #warning-message {
                display: block;
                height: 100vh;
            }
            #rotateIcon{
                font-size: 15rem;
            }
        }

        @media only screen and (orientation: landscape) {
            #warning-message {
                display: none;
            }
        }
    </style>
</head>
<body>

<div style="width:29.7cm; height:20cm; padding:20px; text-align:center; border: 10px solid #787878" id="wrapper">
    <div style="width:28cm; height:18.6cm; padding:20px; text-align:center; border: 5px solid #787878">
        <span style="font-size:90px; font-weight:bold" id="certificateHeader">Certificate of Completion</span>
        <br><br>
        <span style="font-size:30px"><i>This is to certify that</i></span>
        <br><br>
        <span style="font-size:40px"><b><?php echo $fullName; ?></b></span><br/><br/>
        <span style="font-size:30px"><i>has completed the course</i></span> <br/><br/>
        <span style="font-size:40px"><?php echo $cname; ?></span>
        <br><br>
        <?php if (!empty($scoreMessage)) { ?>
            <span style="font-size:20px"><?php echo $scoreMessage; ?></span><br>
        <?php } else {
            ?>
            <br>
            <?php
        } ?>
        <br>
        <span style="font-size:20px">by</span><br>
        <br/>
        <span style="font-size:30px"><?php echo $authors; ?></span>
        <br/><br>
        <span style="font-size:20px">via</span><br>
        <br/>
        <img style="height:80px" src="./img/orionLogo.png"><br/><br/>
        <!--<br/><br/><br/><br/>-->
        <span style="font-size:20px"><i>dated</i></span><br><br>
        <span style="font-size: 30px"><?php echo $dateofcomplete; ?></span>
        <br>
        <div style="font-size: 15px; position: absolute; left:2cm; top:10cm">
            <a href="<?php echo $certificatePermanentLink; ?>" style="text-decoration:none">CLICK OR SCAN TO VERIFY
            <br>
            <div id="qrPermalink"></div>
            </a>
        </div>
    </div>
</div>
<a href="" id="printBtn" onclick="printCertificate();">Save as PDF</a>
<div id="warning-message">Rotate your device for proper viewing<span id="rotateIcon"></span></div>
</body>
<script>
    $(document).ready(function () {
        $('#qrPermalink').qrcode({text: "<?php echo $certificatePermanentLink;?>"});
    });

    function printCertificate() {
        $('#printBtn').hide();
        window.print();
    }
</script>
</html>