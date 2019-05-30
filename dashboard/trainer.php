<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 14/5/19 7:53 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
$email = $_SESSION['email'];
$uid = $_SESSION['uid'];
$profileImageFileName = "";
$profileImageFileNameQuery = "SELECT profileImageFileName FROM user WHERE email='$email'";
$profileImageFileNameResult = mysqli_query($con, $profileImageFileNameQuery) or die(mysqli_error($con));
$profileImageFileNameData = mysqli_fetch_array($profileImageFileNameResult);
$profileImageFileName = $profileImageFileNameData['profileImageFileName'];
$profileImageFileAddress = $userProfileImageFolder . $profileImageFileName;

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

define("NEW_QUESTIONS_COUNT_INDEX", 0);
define("NEW_ANSWERS_COUNT_INDEX", 1);

$courseListQuery = "SELECT cid FROM ctrainers WHERE uid=$uid";
$courseListResult = mysqli_query($con, $courseListQuery) or die(mysqli_error($con));
while ($courseListQueryData = mysqli_fetch_array($courseListResult)) {
    $cid = $courseListQueryData['cid'];
    $cid = $courseListQueryData['cid'];
    $commBadgeData = newComms($con, $cid, $uid);
    $totalNewComms += $commBadgeData[NEW_QUESTIONS_COUNT_INDEX];
    $totalNewComms += $commBadgeData[NEW_ANSWERS_COUNT_INDEX];
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
    <link href="css/orion.css" rel="stylesheet">
    <link rel="icon" href="../favicon.ico">

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon">
                <i class="fas fa-atom"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Orion</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="trainer.php">
                <i class="fas fa-fw fa-chalkboard-teacher"></i>
                <span>Courses</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="communication.php">
                <i class="fas fa-fw fa-comment-alt"></i>
                <span>Communication</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="performance.php">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Your Performance</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="help.php">
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" id="topBar">

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
                        Courses
                    </h1>
                    <a href="createcourse.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create A Course
                    </a>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>

                <!-- Content Row -->

                <!-- Content Row -->
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td>Course Name</td>
                                        <td>Status</td>
                                        <td>Course Cost</td>
                                        <td>Enrolls</td>
                                        <td>Avg. Rating</td>
                                        <td>Sections</td>
                                        <td>Resources</td>
                                        <td>Options</td>

                                    </tr>
                                    </thead>
                                    <?php
                                    $courseListQuery = "SELECT cid FROM ctrainers WHERE uid=$uid";
                                    $courseListResult = mysqli_query($con, $courseListQuery) or die(mysqli_error($con));
                                    while ($courseListQueryData = mysqli_fetch_array($courseListResult)) {
                                        $cid = $courseListQueryData['cid'];

                                        $courseQuery = "SELECT * FROM course WHERE cid=$cid";
                                        $courseResult = mysqli_query($con, $courseQuery) or die(mysqli_error($con));
                                        $courseData = mysqli_fetch_array($courseResult) or die(mysqli_error($con));
                                        $courseTitle = $courseData['cname'];
                                        $courseCost = $courseData['cost'];
                                        $coursePublished = $courseData['published'];
                                        $enrollsQuery = "SELECT count(*) AS enrolls FROM cstudents WHERE cid=$cid";
                                        $enrollsResult = mysqli_query($con, $enrollsQuery) or die(mysqli_error($con));
                                        $enrollsData = mysqli_fetch_array($enrollsResult);
                                        $enrolls = $enrollsData['enrolls'];

                                        $courseRatingExistsQuery = "SELECT count(crid) AS crating FROM creviews WHERE cid=$cid";
                                        $courseRatingExistsResult = mysqli_query($con, $courseRatingExistsQuery) or die(mysqli_error($con));
                                        $courseRatingExistsData = mysqli_fetch_array($courseRatingExistsResult);
                                        $avgCourseRating = 0;
                                        if ($courseRatingExistsData['crating'] > 0) {
                                            $avgCourseRatingQuery = "SELECT (sum(rating)/count(crid)) AS avgcourserating FROM creviews WHERE cid=$cid";
                                            $avgCourseRatingResult = mysqli_query($con, $avgCourseRatingQuery) or die(mysqli_error($con));
                                            $avgCourseRatingData = mysqli_fetch_array($avgCourseRatingResult);
                                            $avgCourseRating = $avgCourseRatingData['avgcourserating'];
                                        }

                                        $numSectionsQuery = "SELECT COUNT(section) AS numsections FROM csections WHERE cid=$cid";
                                        $numSectionsResult = mysqli_query($con, $numSectionsQuery) or die(mysqli_error($con));
                                        $numSectionsData = mysqli_fetch_array($numSectionsResult);
                                        $numSections = $numSectionsData['numsections'];

                                        $totalResourcesQuery = "SELECT count(*) AS totalresources FROM cresources WHERE cid=$cid";
                                        $totalResourcesResult = mysqli_query($con, $totalResourcesQuery) or die(mysqli_error($con));
                                        $totalResourcesData = mysqli_fetch_array($totalResourcesResult);
                                        $totalResources = $totalResourcesData['totalresources'];
                                        if ($coursePublished == 0) {
                                            $publishLink = "<a class='btn btn-danger text-white' href='publishcourse.php?cid=$cid' data-toggle='tooltip' title='View'><span class='far fa-paper-plane'></span></a>";
                                            $publishData = "Unpublished";
                                            $previewLink = "<a href='coursepreview.php?cid=$cid' class='btn btn-primary' data-toggle='tooltip' title='Preview'><span class='far fa-eye'></span></a>";
                                        } else {
                                            $publishLink = "<a class='btn btn-danger text-white' href='withdrawcourse.php?cid=$cid' data-toggle='tooltip' title='Withdraw'><span class='fas fa-minus-circle'></span></a>";
                                            $publishData = "Published";
                                            $previewLink = "<a href='viewcourse.php?cid=$cid' class='btn btn-primary' data-toggle='tooltip' title='View'><span class='far fa-eye'></span></a>";
                                        }

                                        $editLink = "<a href='coursedit.php?cid=$cid' class='btn btn-success' data-toggle='tooltip' title='Edit'><span class='fas fa-pen'></span></a>";
                                        ?>
                                        <tr>
                                            <td><?php echo $courseTitle; ?></td>
                                            <td><?php echo $publishData; ?></td>
                                            <td>₹ <?php echo $courseCost; ?></td>
                                            <td><?php echo $enrolls; ?></td>
                                            <td><?php echo $avgCourseRating; ?>%</td>
                                            <td><?php echo $numSections; ?></td>
                                            <td><?php echo $totalResources; ?></td>
                                            <td><?php echo $previewLink; ?>&nbsp;<?php echo $editLink; ?>&nbsp;<?php echo $publishLink; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                </table>
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
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('#dataTable').DataTable();
    });
</script>

<script src="vendor/chart.js/Chart.min.js"></script>
<?php require('js/communicationsBadge.php'); ?>
</body>
</html>