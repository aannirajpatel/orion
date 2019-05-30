<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 20/5/19 3:22 PM.
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

if (!isset($_GET['uid']) || $_GET['uid'] <= 0) {
    die("Error loading profile - no profile ID provided to viewer. Please contact an admin.");
}

$profileUid = $_GET['uid'];

$dashHome = "student.php";
$dashPerformance = "student-achievements.php";
$dashPerformanceText = "Achievements";
$dashHelp = "student-help.php";
$dashCommunication = "student-communication.php";
$back = "student.php";
if (getUserType($con, $_SESSION['uid']) == 1) {
    $dashHome = "trainer.php";
    $dashPerformance = "performance.php";
    $dashPerformanceText = "Your Performance";
    $dashHelp = "help.php";
    $dashCommunication = "communication.php";
    $back = "trainer.php";
}

function loadRecentCoursesTable($con, $uid)
{
    echo "
<thead>
    <tr>
    <th>Name</th>
    <th>Date Published</th>
    </tr>
    </thead>
    <tbody>
    ";
    $resourceQuery = "SELECT * FROM course INNER JOIN ctrainers ON (course.cid = ctrainers.cid AND ctrainers.uid=$uid AND course.published=1) ORDER BY dateofpublish";
    $resourceResult = mysqli_query($con, $resourceQuery) or die(mysqli_error($con));
    $resourceNumber = 0;
    $totalResources = mysqli_num_rows($resourceResult);
    if ($totalResources == 0) {
        echo "<tr><td colspan='2'>No courses published by this user yet.</td></tr>";
    } else {
        while ($totalResources > 0) {
            $resourceData = mysqli_fetch_array($resourceResult);
            $resourceNumber++;
            $totalResources--;
            $cid = $resourceData['cid'];
            ?>
            <tr>
                <td><a href="viewcourse.php?cid=<?php echo $cid; ?>"><?php echo $resourceData['cname']; ?></a></td>
                <td><?php echo $resourceData['dateofpublish']; ?></td>
            </tr>
            <?php
        }
        echo "</tbody>";

    }
}

$bioQuery = "SELECT * FROM user WHERE uid=$profileUid";
$bioResult = mysqli_query($con, $bioQuery) or die(mysqli_error($con));
$bioData = mysqli_fetch_array($bioResult);
$bio = $bioData['bio'];
$userImageAddress = "./res/userimages/" . $bioData['profileImageFileName'];
$fname = $bioData['fname'];
$lname = $bioData['lname'];
$profileEditable = 0;
if ($uid == $profileUid) {
    $profileEditable = 1;
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

    <title>Viewing Profile</title>

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
        <?php if (getUserType($con, $_SESSION['uid']) == 0) { ?>
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

                    <!-- Nav Item - Messages -->
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
                                 src="./res/userimages/<?php echo $profileImageFileName; ?>">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="viewprofile.php?uid=<?php echo $uid;?>">
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
                        Viewing Profile
                        <?php
                        if ($profileEditable == 1) {
                            ?>
                            <a href="editProfile.php" class="btn btn-primary">Edit Your Profile</a>
                            <?php
                        }
                        ?>
                    </h1>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>

                <!-- Content Row -->
                    <div class="row">
                        <div class="container col-4">
                            <div class="container">
                                <div class="card" style="width:20vw">
                                    <img class="card-img-top" src="<?php echo $userImageAddress; ?>" alt="Card image"
                                         style="width:100%;padding:10px;">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $fname . " " . $lname; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container col-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Courses
                                        by <?php echo $fname . " " . $lname; ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="table-responsive">
                                            <table class="table" style="display:table;">
                                                <?php loadRecentCoursesTable($con, $profileUid); ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <br>
                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Bio</h6>
                                </div>
                                    <div class="card-body">
                                        <?php echo $bio; ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <br>
                <!-- /.container-fluid -->
            </div>
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
<?php require ('getNewCommsData.php');?>
<?php require('js/communicationsBadge.php');?>

</body>

</html>

