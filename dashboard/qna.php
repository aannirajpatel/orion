<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 11:03 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

$email = $_SESSION['email'];
$uid = $_SESSION['uid'];

$profileImageFileName = "";
$profileImageFileNameQuery = "SELECT profileImageFileName FROM user WHERE email='$email'";
$profileImageFileNameResult = mysqli_query($con, $profileImageFileNameQuery) or die(mysqli_error($con));
$profileImageFileNameData = mysqli_fetch_array($profileImageFileNameResult);
$profileImageFileName = $profileImageFileNameData['profileImageFileName'];
$profileImageFileAddress = $userProfileImageFolder . $profileImageFileName;

if (!isset($_GET['cid'])) {
    die("Error loading course preview - no course ID provided to viewer. Please contact an admin.");
}
/*elseif (!isThisStudentsCourse($con, $_GET['cid']) && !isThisUsersCourse($con, $_GET['cid'])) {
    die("Error loading course - authorization problem. Please contact admin");
}*/
if (!isCoursePublished($con, $_GET['cid'])) {
    $message = "This course is either not published or has been withdrawn.";
    header("location:displayMessage.php?message=" . $message);
}

$cid = $_GET['cid'];

if (!isAuditing($con, $cid, $uid) && !isThisStudentsCourse($con, $cid) && !isThisUsersCourse($con, $cid)) {
    $message = "This course is not Audited or Enrolled by you, or in rare cases, it may be withdrawn. Please add the course to your audit list or enroll (purchase for certificate) by clicking <a href='viewcourse.php?cid=$cid'>here</a>";
    header("location:displayMessage.php?message=" . $message);
}

$dashHome = "student.php";
$dashPerformance = "student-achievements.php";
$dashPerformanceText = "Achievements";
$dashHelp = "student-help.php";
$dashCommunication = "student-communication.php";
$back = "student-communication.php";
if (isThisUsersCourse($con, $cid)) {
    $dashHome = "trainer.php";
    $dashPerformance = "performance.php";
    $dashPerformanceText = "Your Performance";
    $dashHelp = "help.php";
    $dashCommunication = "communication.php";
    $back = "communication.php";
}

$courseQuery = "SELECT * FROM course INNER JOIN csyllabus ON (course.cid = csyllabus.cid AND course.cid=$cid)";
$courseResult = mysqli_query($con, $courseQuery) or die(mysqli_error($con));
$courseData = mysqli_fetch_array($courseResult);
$courseName = $courseData['cname'];

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
    $authors = $authors . "<a href='viewprofile.php?uid=" . $authorData['uid'] . "'>" . $authorData['fname'] . " " . $authorData['lname'] . "</a>";
    $authorNumber++;
    if ($authorNumber < $numAuthors) {
        $authors = $authors . ", ";
    }
}

$updateLastViewTimeQuery = "INSERT INTO lastviewedqna (uid,cid) VALUES($uid,$cid) ON DUPLICATE KEY UPDATE lastviewed=CURRENT_TIMESTAMP";
$updateLastViewTimeResult = mysqli_query($con, $updateLastViewTimeQuery) or die(mysqli_error($con));

//Functions for fetching QnAs

function fetchQuestions($con, $cid)
{
    $questionsQuery = "SELECT * FROM question WHERE cid=$cid";
    $questionsResult = mysqli_query($con, $questionsQuery) or die(mysqli_error($con));
    return $questionsResult;
}

function fetchAnswers($con, $questionId)
{
    $answersQuery = "SELECT * FROM answer WHERE (answer.questionid=$questionId)";
    $answersResult = mysqli_query($con, $answersQuery) or die(mysqli_error($con));
    return $answersResult;
}

function getProfileImageCircleFromUid($con, $uid, $userProfileImageFolder)
{
    $profileImageFileNameQuery = "SELECT profileImageFileName FROM user WHERE uid=$uid";
    $profileImageFileNameResult = mysqli_query($con, $profileImageFileNameQuery) or die(mysqli_error($con));
    $profileImageFileNameData = mysqli_fetch_array($profileImageFileNameResult);
    $profileImageFileName = $profileImageFileNameData['profileImageFileName'];
    $profileImageFileAddress = $userProfileImageFolder . $profileImageFileName;
    $profileImageCircle = "<img style='width: 2rem; height: 2rem;' class=\"rounded-circle\" src=\"$profileImageFileAddress\"/> ";
    return $profileImageCircle;
}

function getNameWithProfileLinkFromUid($con, $uid)
{
    $nameWithProfileLink = "<a href='viewprofile.php?uid=$uid'>";
    $nameQuery = "SELECT fname, lname FROM user WHERE uid=$uid";
    $nameResult = mysqli_query($con, $nameQuery) or die(mysqli_error($con));
    $nameData = mysqli_fetch_array($nameResult);
    $name = $nameData['fname'] . " " . $nameData['lname'];
    $nameWithProfileLink .= $name;
    $nameWithProfileLink .= "</a>";
    return $nameWithProfileLink;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Course Viewer</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/orion.css" rel="stylesheet">
    <link href="../favicon.ico" rel="icon">

    <!--
        Copyright (c) 2007-2008 Brian Kirchoff (http://nicedit.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
        -->
    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

    <style>
        #enrollButton {
            z-index: 1090;
            position: fixed;
            right: 2vw;
        }

        .slider {
            -webkit-appearance: none;
            width: 100%;
            height: 25px;
            background: #d3d3d3;
            outline: none;
            opacity: 0.7;
            border-radius: 5px;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .slider:hover {
            opacity: 1;

        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            border-radius: 5px;
            width: 30px;
            height: 30px;
            background: #0000FF;
            cursor: pointer;
        }

        .slider::-moz-range-thumb {
            width: 25px;
            height: 25px;
            background: #4CAF50;
            cursor: pointer;
        }

    </style>

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon">
                <i class="fas fa-atom"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Orion</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $dashHome; ?>">
                <i class="fas fa-fw fa-chalkboard-teacher"></i>
                <span>Courses</span></a>
        </li>

        <li class="nav-item active">
            <a class="nav-link" href="<?php echo $dashCommunication; ?>">
                <i class="fas fa-fw fa-comment-alt"></i>
                <span>Communication</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo $dashPerformance; ?>">
                <i class="fas fa-fw fa-chart-line"></i>
                <span><?php echo $dashPerformanceText; ?></span></a>
        </li>
        <?php if (getUserType($con, $uid) == 0) { ?>
            <li class="nav-item">
                <a class="nav-link" href="student-purchases.php">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Purchases</span>
                </a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $dashHelp; ?>">
                <i class="fas fa-fw fa-question"></i>
                <span>Help</span></a>
        </li>
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->
                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
                      method="get" action="searchcourse.php">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               name="q"
                               placeholder="Search for a course..."
                               aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                             aria-labelledby="searchDropdown">
                            <form class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small"
                                           placeholder="Search for..." aria-label="Search"
                                           aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>


                    <li class="nav-item no-arrow mx-1" id="communicationsNavLink">
                        <a class="nav-link" href="<?php if(getUserType($con, $uid)==1){echo "communication.php";} if(getUserType($con, $uid)==0){echo "student-communication.php";}?>" role="button">
                            <i class="fas fa-envelope fa-fw"></i>
                            <!-- Counter - Messages -->
                            <span class="badge badge-danger badge-counter" id="communicationsBadge"></span>
                        </a>
                    </li>

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?>
                            </span>
                            <img class="img-profile rounded-circle"
                                 src="<?php echo $profileImageFileAddress; ?>">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="viewprofile.php?uid=<?php echo $uid; ?>">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        QnA: <?php echo $courseName; ?>
                        <a href="<?php echo $back; ?>" class="btn btn-info">Back</a>
                        <?php if (!isAuditing($con, $cid, $uid) && !isThisStudentsCourse($con, $cid) && !isThisUsersCourse($con, $cid)) { ?>
                            <a href="auditCourse.php?cid=<?php echo $cid; ?>" class="btn btn-success"><span
                                        class="fas fa-plus"></span> Add Course to Audit List</a>
                        <?php } ?>
                    </h1>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>
                <!-- Content Row -->

                <div class="row">
                    <div class="container">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Ask a question</h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="addQuestion.php">
                                    <div class="form-group">
                                        <textarea name="qtext" class="form-control"></textarea>
                                        <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control bg-primary text-white" type="submit" value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="container">
                        <?php
                        $questionsResult = fetchQuestions($con, $cid);
                        while ($question = mysqli_fetch_array($questionsResult)) {
                            $askerUid = $question['uid'];
                            $dateofquestion = $question['dateofquestion'];
                            $questionText = $question['qtext'];
                            $questionId = $question['questionid'];
                            ?>
                            <div class="card">
                                <div class="card-header">
                                    <?php
                                    echo getProfileImageCircleFromUid($con, $askerUid, $userProfileImageFolder);
                                    echo getNameWithProfileLinkFromUid($con, $askerUid);
                                    echo " asked on ";
                                    echo $dateofquestion;
                                    ?>
                                </div>
                                <div class="card-body">
                                    <?php
                                    echo $questionText;
                                    ?>

                                    <div class="container">
                                        <?php
                                        $answersResult = fetchAnswers($con, $questionId);
                                        if (mysqli_num_rows($answersResult) > 0) {
                                            ?>
                                            <hr>
                                            <?php
                                        }
                                        while ($answer = mysqli_fetch_array($answersResult)) {
                                            $answererUid = $answer['uid'];
                                            $dateofanswer = $answer['dateofanswer'];
                                            $answerText = $answer['atext']
                                            ?>
                                            <div class="card">
                                                <div class="card-header">
                                                    <?php
                                                    echo getProfileImageCircleFromUid($con, $answererUid, $userProfileImageFolder);
                                                    echo getNameWithProfileLinkFromUid($con, $answererUid);
                                                    echo " Answered On ";
                                                    echo $dateofanswer;
                                                    ?>
                                                </div>
                                                <div class="card-body">
                                                    <?php
                                                    echo $answerText;
                                                    ?>
                                                </div>
                                            </div>
                                            <br>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a class="btn btn-success text-white" id="btnAnswerTo<?php echo $questionId; ?>"
                                       onclick="showAnswerBox('#btnAnswerTo<?php echo $questionId; ?>','#answerTo<?php echo $questionId; ?>')"><span
                                                class="fas fa-plus"></span>Add Answer</a>
                                    <br>
                                    <br>

                                    <div class="card answerBox" id="answerTo<?php echo $questionId; ?>">
                                        <div class="card-body">
                                            <form method="post" action="addAnswer.php">
                                                <div class="form-group">
                                                    <label>Please provide your answer below</label>
                                                    <textarea class="form-control" name="atext"></textarea>
                                                    <input type="hidden" name="questionid"
                                                           value="<?php echo $questionId; ?>">
                                                    <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" type="submit" value="Submit">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="container">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Author<?php echo $authorPlurality; ?> of
                                    this course</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <?php echo $authors; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Global BizConnect 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../logout">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function () {
        $('.answerBox').hide();
    });

    function showAnswerBox(btnId, boxId) {
        $(btnId).attr("onclick", "hideAnswerBox('" + btnId + "','" + boxId + "')");
        $(boxId).show();
    }

    function hideAnswerBox(btnId, boxId) {
        $(btnId).attr("onclick", "showAnswerBox('" + btnId + "','" + boxId + "')");
        $(boxId).hide();
    }
</script>
<?php require('getNewCommsData.php'); ?>
<?php require('js/communicationsBadge.php');?>
</body>

</html>