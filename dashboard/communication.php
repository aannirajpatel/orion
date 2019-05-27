<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 27/5/19 1:47 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/resconfig.php');

define("NEW_QUESTIONS_COUNT_INDEX",0);
define("NEW_ANSWERS_COUNT_INDEX",1);
$email = $_SESSION['email'];
$uid = $_SESSION['uid'];
$profileImageFileName = "";
$profileImageFileNameQuery = "SELECT profileImageFileName FROM user WHERE email='$email'";
$profileImageFileNameResult = mysqli_query($con, $profileImageFileNameQuery) or die(mysqli_error($con));
$profileImageFileNameData = mysqli_fetch_array($profileImageFileNameResult);
$profileImageFileName = $profileImageFileNameData['profileImageFileName'];
$profileImageFileAddress = $userProfileImageFolder . $profileImageFileName;

$dashHome = "student.php";
$dashPerformance = "student-achievements.php";
$dashPerformanceText = "Achievements";
$dashHelp = "student-help.php";
$dashCommunication = "student-communication.php";
$back = "student-communication.php";
if (getUserType($con, $uid)==1) {
    $dashHome = "trainer.php";
    $dashPerformance = "performance.php";
    $dashPerformanceText = "Your Performance";
    $dashHelp = "help.php";
    $dashCommunication = "communication.php";
    $back = "communication.php";
}

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $_SESSION['fname'] . "'s" ?> Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
                <!--<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
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

                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <!-- Counter - Alerts -->
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">
                                Notification Center
                            </h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 12, 2019</div>
                                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-donate text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 7, 2019</div>
                                    $290.29 has been deposited into your account!
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 2, 2019</div>
                                    Spending Alert: We've noticed unusually high spending for your account.
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                        </div>
                    </li>

                    <!-- Nav Item - Messages -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-envelope fa-fw"></i>
                            <!-- Counter - Messages -->
                            <span class="badge badge-danger badge-counter">7</span>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">
                                Message Center
                            </h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="<?php echo $profileImageFileAddress; ?>"
                                         alt="">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="font-weight-bold">
                                    <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                        problem I've been having.
                                    </div>
                                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60"
                                         alt="">
                                    <div class="status-indicator"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">I have the photos that you ordered last month, how
                                        would
                                        you like them sent to you?
                                    </div>
                                    <div class="small text-gray-500">Jae Chun · 1d</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60"
                                         alt="">
                                    <div class="status-indicator bg-warning"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Last month's report looks great, I am very happy with
                                        the
                                        progress so far, keep up the good work!
                                    </div>
                                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                         alt="">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                        told
                                        me that people say this to all dogs, even if they aren't good...
                                    </div>
                                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                        </div>
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
                        Click on a course to open its QnA
                    </h1>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>

                <!-- Content Row -->

                <!-- Content Row -->
                <div class="row">
                    <div class="container">
                        <div class="list-group">
                            <?php
                            $courseListQuery = "SELECT cid FROM ctrainers WHERE uid=$uid";
                            $courseListResult = mysqli_query($con, $courseListQuery) or die(mysqli_error($con));
                            while ($courseListQueryData = mysqli_fetch_array($courseListResult)) {
                                $cid = $courseListQueryData['cid'];
                                $courseQuery = "SELECT * FROM course WHERE cid=$cid";
                                $courseResult = mysqli_query($con, $courseQuery) or die(mysqli_error($con));
                                $courseData = mysqli_fetch_array($courseResult) or die(mysqli_error($con));
                                $courseTitle = $courseData['cname'];
                                $courseDesc = $courseData['cdesc'];
                                $courseAuthorsQuery = "SELECT uid FROM ctrainers WHERE cid=$cid";
                                $courseAuthorsResult = mysqli_query($con, $courseAuthorsQuery) or die(mysqli_error($con));
                                $courseAuthors = "";
                                $totalAuthors = mysqli_num_rows($courseAuthorsResult);
                                while ($courseAuthorsData = mysqli_fetch_array($courseAuthorsResult)) {
                                    $courseAuthorNameQuery = "SELECT fname, lname FROM user WHERE uid=" . $courseAuthorsData['uid'];
                                    $courseAuthorNameResult = mysqli_query($con, $courseAuthorNameQuery) or die(mysqli_error($con));
                                    $courseAuthorNameData = mysqli_fetch_array($courseAuthorNameResult);
                                    $courseAuthors .= "<a href='viewprofile.php?uid=" . $courseAuthorsData['uid'] . "'>" . $courseAuthorNameData['fname'] . " " . $courseAuthorNameData['lname'] . "</a>";
                                    $totalAuthors--;
                                    if ($totalAuthors > 0) {
                                        $courseAuthors .= ", ";
                                    }
                                }


                                $commBadge = "";
                                $commBadgeData = newComms($con, $cid, $uid);
                                if ($commBadgeData[NEW_QUESTIONS_COUNT_INDEX]) {
                                    $commBadge = "<span class=\"badge badge-danger\">+".$commBadgeData[NEW_QUESTIONS_COUNT_INDEX]." Questions</span>";
                                }
                                if($commBadgeData[NEW_ANSWERS_COUNT_INDEX]){
                                    $commBadge .= " <span class=\"badge badge-danger\">+".$commBadgeData[NEW_ANSWERS_COUNT_INDEX]." Answers</span>";
                                }
                                $viewLink = "<a href='viewcourse.php?cid=$cid' class='btn btn-primary'>View</a>";
                                ?>
                                <a class="list-group-item list-group-item-action" href="qna.php?cid=<?php echo $cid; ?>">

                                        <?php echo $courseTitle; ?>
                                        <?php echo $commBadge; ?>

                                </a>
                                <?php
                            }
                            ?>
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

</body>
</html>
