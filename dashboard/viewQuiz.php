<?php
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

$uid = $_SESSION['uid'];
$email = $_SESSION['email'];
$profileImageFileName = "";
$profileImageFileNameQuery = "SELECT profileImageFileName FROM user WHERE email='$email'";
$profileImageFileNameResult = mysqli_query($con, $profileImageFileNameQuery) or die(mysqli_error($con));
$profileImageFileNameData = mysqli_fetch_array($profileImageFileNameResult);
$profileImageFileName = $profileImageFileNameData['profileImageFileName'];
$profileImageFileAddress = $userProfileImageFolder . $profileImageFileName;

if (!isset($_GET['rid'])) {
    header("location:404.php");
}
$rid = $_GET['rid'];
$cid = getCidFromRid($con, $rid);
if (!isThisUsersCourse($con, $cid) && !isAuditing($con, $cid, $uid) && !isThisStudentsCourse($con, $cid)) {
    header("location:404.php");
}
$quizDetailsQuery = "SELECT * FROM cresources WHERE rid = $rid";
$quizDetailsResult = mysqli_query($con, $quizDetailsQuery) or die(mysqli_error($con));
if (mysqli_num_rows($quizDetailsResult) != 1) {
    header("location:404.php");
}
$quizDetailsData = mysqli_fetch_array($quizDetailsResult);
$quizName = $quizDetailsData['rtext'];
$quizMaxAttempts = intval($quizDetailsData['raddr']);
$attemptsQuery = "SELECT count(*) as attempts, max(dateofattempt) as doa, max(score) as bestscore FROM quizattempts WHERE uid=$uid AND quizid=$rid";
$attemptsResult = mysqli_query($con, $attemptsQuery) or die(mysqli_error($con));

if(mysqli_num_rows($attemptsResult)==1){
    $attemptsData = mysqli_fetch_array($attemptsResult);
    $attempts = $attemptsData['attempts'];
    $doa = $attemptsData['doa'];
    $bestscore = $attemptsData['bestscore'];
    if(empty($doa)||empty($bestscore)){
        $attemptsMessage = "(Min. 40% needed to pass.) <br>Attempts: $attempts/$quizMaxAttempts";
    } else {
        $attemptsMessage = "(Min. 40% needed to pass.) <br>Attempts: $attempts/$quizMaxAttempts <br> Best Score: $bestscore% <br> Last Attempt: $doa";
    }
}

$questionsQuery = "SELECT * FROM quizquestion WHERE quizid = $rid ORDER BY RAND()";
$questionsResult = mysqli_query($con, $questionsQuery) or die(mysqli_error($con));

$dashHome = "student.php";
$dashPerformance = "student-achievements.php";
$dashPerformanceText = "Achievements";
$dashHelp = "student-help.php";
$dashCommunication = "student-communication.php";
$back = "student.php";
if (getUserType($con, $uid) == 1) {
    $dashHome = "trainer.php";
    $dashPerformance = "performance.php";
    $dashPerformanceText = "Your Performance";
    $dashHelp = "help.php";
    $dashCommunication = "communication.php";
    $back = "trainer.php";
}$dashHome = "student.php";
$dashPerformance = "student-achievements.php";
$dashPerformanceText = "Achievements";
$dashHelp = "student-help.php";
$dashCommunication = "student-communication.php";
$back = "student.php";
if (getUserType($con, $uid) == 1) {
    $dashHome = "trainer.php";
    $dashPerformance = "performance.php";
    $dashPerformanceText = "Your Performance";
    $dashHelp = "help.php";
    $dashCommunication = "communication.php";
    $back = "trainer.php";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View quiz</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/orion.css" rel="stylesheet">

    <!--
        Copyright (c) 2007-2008 Brian Kirchoff (http://nicedit.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
        -->
    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

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
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo $dashHome; ?>">
                <i class="fas fa-fw fa-chalkboard-teacher"></i>
                <span>Courses</span></a>
        </li>

        <li class="nav-item">
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
                <!--<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               placeholder="Search for a course..."
                               aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>-->

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
                        <a class="nav-link" href="communication.php" role="button">
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
                        Quiz: <?php echo $quizName;?>
                        <small><?php echo $attemptsMessage;?></small>
                    </h1>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>

                <!-- Content Row -->

                <!-- Content Row -->
                <div class="row">
                    <div class="container">
                        <div class="card">
                            <div class="card-body">

                                <form method="post" action="viewResult.php">
                                    <?php
                                    $questionSr = 1;
                                    while ($questionsData = mysqli_fetch_array($questionsResult)) {
                                        ?>
                                        <div class="form-group">
                                            <h3 class="text-primary">Question <?php echo $questionSr;
                                                $questionSr++; ?></h3>
                                            <p><?php echo $questionsData['quizqtext']; ?></p>
                                            <?php
                                            $qno = $questionsData['qno'];
                                            $answersQuery = "SELECT * FROM quizanswer WHERE qno = $qno AND quizid = $rid ORDER BY RAND()";
                                            $answersResult = mysqli_query($con, $answersQuery) or die(mysqli_error($con));
                                            while ($answersData = mysqli_fetch_array($answersResult)) {
                                                $answerText = $answersData['answertext'];
                                                ?>
                                                <input type="radio" name="a<?php echo $qno; ?>"
                                                       value="<?php echo hash("sha256", $answerText, FALSE); ?>">
                                                <?php echo $answerText; ?>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="form-group">
                                        <input type="hidden" name="rid" value="<?php echo $rid; ?>">
                                        <input type="submit" value="Submit Quiz" class="form-control-sm btn btn-primary">
                                        <!--<input type="reset" value="Clear all answers" class="form-control-sm btn btn-danger">-->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

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
<?php require('getNewCommsData.php'); ?>
<?php require('js/communicationsBadge.php'); ?>
</body>
</html>