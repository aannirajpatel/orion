<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 23/5/19 1:37 PM.
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
if(!isset($_GET['q'])){
    header("location:student.php");
}
$searchText = mysqli_real_escape_string($con, $_GET['q']);
$searchText = strtolower($searchText);
$searchText = preg_replace('/[^a-z0-9 -]+/', '', $searchText);
$searchText = str_replace(' ', '-', $searchText);
$searchText = trim($searchText, '-');
$searchText = explode("-",$searchText);
$searchTextWordCount = count($searchText);
$searchQuery = "";
if($searchTextWordCount<1){
    echo "<div class='alert alert-dismissible alert-warning'>Invalid Search Query</div>";
}
else {
    $searchQuery = "SELECT * FROM course INNER JOIN csyllabus ON (course.cid=csyllabus.cid AND published=1) WHERE ";
    foreach ($searchText as $searchWord) {
        $searchQuery .= "LOWER(cname) LIKE ('%$searchWord%') OR LOWER(cdesc) LIKE('%$searchWord%') OR LOWER(csyllabus) LIKE('%$searchWord%')";
        $searchTextWordCount--;
        if ($searchTextWordCount > 0) {
            $searchQuery .= " OR ";
        }
    }
    $searchQueryResult = mysqli_query($con, $searchQuery) or die($con);
    //Uncomment the line below for DEBUG Purposes
    //echo $searchQuery;
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
            <a class="nav-link" href="student.php">
                <i class="fas fa-fw fa-chalkboard-teacher"></i>
                <span>Courses</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="student-communication.php">
                <i class="fas fa-fw fa-comment-alt"></i>
                <span>Communication</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="student-achievements.php">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Achievements</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="student-purchases.php">
                <i class="fas fa-money-check-alt"></i>
                <span>Purchases</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="student-help.php">
                <i class="fas fa-fw fa-question"></i>
                <span>Help</span></a>
        </li>

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
                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="get" action="searchcourse.php">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               name="q"
                               value="<?php echo $_GET['q'];?>"
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
                            <a class="dropdown-item" href="#">
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
                        Found <?php echo mysqli_num_rows($searchQueryResult);?> Search Results
                    </h1>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>

                <!-- Content Row -->

                <!-- Content Row -->
                <div class="row">
                    <div class="card-columns">
                        <?php
                        while ($courseListQueryData = mysqli_fetch_array($searchQueryResult)) {
                            $cid = $courseListQueryData['cid'];

                            $courseQuery = "SELECT * FROM course WHERE cid=$cid";
                            $courseResult = mysqli_query($con, $courseQuery) or die(mysqli_error($con));
                            $courseData = mysqli_fetch_array($courseResult) or die(mysqli_error($con));
                            $courseTitle = $courseData['cname'];
                            $courseDesc = $courseData['cdesc'];

                            $courseRatingsExistQuery = "SELECT count(*) AS reviews FROM creviews WHERE cid=$cid";
                            $courseRatingsExistResult = mysqli_query($con, $courseRatingsExistQuery) or die(mysqli_error($con));
                            $courseRatingsExistData = mysqli_fetch_array($courseRatingsExistResult);
                            $avgCourseRating = 0;
                            if($courseRatingsExistData['reviews']>0){
                                $courseRatingQuery = "SELECT AVG(rating) as avgrating FROM creviews WHERE cid=$cid";
                                $courseRatingResult = mysqli_query($con, $courseRatingQuery) or die(mysqli_error($con));
                                $courseRatingData = mysqli_fetch_array($courseRatingResult);
                                $avgCourseRating = $courseRatingData['avgrating'];
                            }

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

                            $viewLink = "<a href='viewcourse.php?cid=$cid' class='btn btn-primary'>View</a>";
                            ?>
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <?php echo $courseTitle; ?>
                                    <span class="far fa-star"></span>
                                    <?php echo round($avgCourseRating,1);?>/5
                                </div>
                                <div class="card-body">
                                    <p>
                                        <?php echo $courseDesc; ?>
                                    </p>
                                    By:&nbsp;<?php echo $courseAuthors; ?>
                                </div>
                                <div class="card-footer">
                                    <?php echo $viewLink; ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

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
<?php require('getNewCommsData.php');?>
<?php require('js/communicationsBadge.php');?>
</body>
</html>