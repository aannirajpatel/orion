<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 12:51 PM.
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


if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <title>Add a section</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="Expires" content="0"/>
        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
              rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
        <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
        <link href="css/orion.css" rel="stylesheet">

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
                            Add a section
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
                                    <div class="container">

                                            <form action="" method="post">



                                                    <!-- Section name input-->
                                                    <div class="form-group">
                                                        <label class="control-label" for="sname">Section Name</label>
                                                        <input id="sname" name="sname" type="text"
                                                               placeholder="Give a name to the section"
                                                               class="form-control" required="">
                                                        <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                                    </div>

                                                    <!-- Section description input -->
                                                    <div class="form-group">
                                                        <label class="control-label" for="sdesc">Section
                                                            Description</label>
                                                        <textarea class="form-control" id="sdesc"
                                                                  name="sdesc"></textarea>
                                                    </div>

                                                    <!-- Submit -->
                                                    <div class="form-group">
                                                        <label class="control-label" for=""></label>
                                                        <button type="submit" id="" name="" class="btn btn-success">
                                                            Create Section
                                                        </button>
                                                        <a href="coursedit.php?cid=<?php echo $cid;?>" class="btn btn-primary">Cancel</a>
                                                    </div>
                                            </form>
                                    </div>
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
    <?php require('rotateScreen.php'); ?>
    <?php require('js/communicationsBadge.php'); ?>
    </body>
    </html>
    <?php
}
if (isset($_POST['sname']) && isset($_POST['sdesc']) && isset($_POST['cid'])) {
    $cid = $_POST['cid'];
    $startTransactionExecuteQuery = mysqli_query($con, "START TRANSACTION") or die(mysqli_error($con));
    $getSectionNumberResult = mysqli_query($con, "SELECT MAX(`section`) as sno FROM csections WHERE cid=$cid");
    $sectionNumber = 1;
    if (mysqli_num_rows($getSectionNumberResult) != 0) {
        $getSectionNumberData = mysqli_fetch_array($getSectionNumberResult);
        $sectionNumber = $getSectionNumberData['sno'] + 1;
    }
    $sname = $_POST['sname'];
    $sdesc = mysqli_real_escape_string($con, $_POST['sdesc']);
    $createSectionQuery = mysqli_query($con, "INSERT INTO csections(cid, `section`, sname, sdesc) VALUES($cid,$sectionNumber,'$sname','$sdesc')");
    $commitTransactionExecuteQuery = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
    echo "Section created.";
    header("location:coursedit.php?cid=$cid");
}
if (!isset($_GET['cid']) && !isset($_POST['sname'])) {
    header('404.php');
}

?>