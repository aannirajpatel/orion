<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 22/5/19 12:32 PM.
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
$dashHome = "student.php";
$dashPerformance = "student-achievements.php";
$dashPerformanceText = "Achievements";
$dashHelp = "student-help.php";
$dashCommunication = "student-communication.php";
$back = "student.php";
if (isThisUsersCourse($con, $cid)) {
    $dashHome = "trainer.php";
    $dashPerformance = "performance.php";
    $dashPerformanceText = "Your Performance";
    $dashHelp = "help.php";
    $dashCommunication = "communication.php";
    $back = "trainer.php";
}

$courseCostQuery = "SELECT cost FROM course WHERE cid=$cid";
$courseCostResult = mysqli_query($con, $courseCostQuery) or die($con);
$courseCostData = mysqli_fetch_array($courseCostResult);
$courseCost = $courseCostData['cost'];

$courseRatingQuery = "SELECT avg(rating) AS avgrating,count(*) as reviews FROM creviews WHERE cid=$cid";
$courseRatingResult = mysqli_query($con, $courseRatingQuery) or die(mysqli_error($con));
$courseRatingData = mysqli_fetch_array($courseRatingResult);
$courseRating = $courseRatingData['avgrating'];
$courseReviews = $courseRatingData['reviews'];
$courseRatingDistroQuery = "SELECT count(*) AS individuals, rating FROM creviews WHERE cid=$cid GROUP BY rating ORDER BY rating";
$courseRatingDistroResult = mysqli_query($con, $courseRatingDistroQuery);
$courseRatingDistro = array();
while ($courseRatingDistroData = mysqli_fetch_array($courseRatingDistroResult)) {
    $courseRatingDistro[$courseRatingDistroData['rating']] = $courseRatingDistroData['individuals'];
    $courseRatingDistroPercentages[$courseRatingDistroData['rating']] = $courseRatingDistroData['individuals'] / $courseReviews;
}

function printType($rtype)
{
    switch ($rtype) {
        case RES_NOTE:
            return "Note";
        case RES_VIDEO:
            return "Video";
        case RES_FILE:
            return "File";
        case RES_YOUTUBE:
            return "YouTube Video";
        case RES_LINK:
            return "Link";
    }
}

function printGlyph($rtype)
{
    switch ($rtype) {
        case RES_NOTE:
            return "fas fa-file-alt";
        case RES_VIDEO:
            return "fas fa-play";
        case RES_FILE:
            return "fas fa-file-download";
        case RES_YOUTUBE:
            return "fab fa-youtube";
        case RES_LINK:
            return "fas fa-link";
    }
}

function printSectionDescription($con, $sectionNumber, $cid)
{
    $sectionDescriptionQuery = "SELECT sdesc FROM csections WHERE section=$sectionNumber AND cid=$cid";
    $sectionDescriptionResult = mysqli_query($con, $sectionDescriptionQuery) or die(mysqli_error($con));
    if (mysqli_num_rows($sectionDescriptionResult) > 0) {
        $sectionDescriptionData = mysqli_fetch_array($sectionDescriptionResult);
        return $sectionDescriptionData['sdesc'];
    } else {
        return "Nothing to show";
    }
}

function loadSectionTable($con, $sectionNumber, $cid)
{
    echo "
<thead>
    <tr>
    <th>Sr.No.</th>
    <th>Name</th>
    <th>Type</th>
    <th>Completion</th>   
    </tr>
    </thead>
    <tbody>
    ";
    $resourceQuery = "SELECT * FROM cresources WHERE cid=$cid AND section=$sectionNumber";
    $resourceResult = mysqli_query($con, $resourceQuery) or die(mysqli_error($con));
    $resourceNumber = 0;
    $totalResources = mysqli_num_rows($resourceResult);
    if ($totalResources == 0) {
        echo "<tr><td colspan='5'>No resources yet.</td></tr>";
    } else {
        while ($totalResources > 0) {
            $resourceData = mysqli_fetch_array($resourceResult);
            $resourceNumber++;
            $totalResources--;
            $rid = $resourceData['rid'];
            $rtype = $resourceData['rtype'];
            ?>
            <tr>
                <td><?php echo $resourceNumber; ?></td>
                <td><?php echo $resourceData['rtext']; ?></td>
                <td><?php echo printType($rtype); ?></td>
                <!--<td><?php /*echo $resourceData['rdate']; */ ?></td>-->
                <td>
                    <?php if (resHasBeenViewed($con, $rid, $_SESSION['uid'])) { ?>
                        <span class="fas fa-check-circle"></span>
                        <?php
                    } else{
                        ?>
                        <span class="far fa-circle"></span>
                        <?php
                    }
                    ?>
                    <a class="btn btn-primary"
                       href="<?php echo resViewFile($rtype); ?>?rid=<?php echo $rid; ?>">
                        <span class="<?php echo printGlyph($rtype); ?>"></span>
                        View
                    </a>
                </td>
            </tr>
            <?php
        }
        echo "</tbody>";
    }
}

function loadSectionName($con, $sectionNumber, $cid)
{
    $sectionQuery = "SELECT sname FROM csections WHERE cid=$cid AND section=$sectionNumber";
    $sectionResult = mysqli_query($con, $sectionQuery) or die(mysqli_error($con));
    if (mysqli_num_rows($sectionResult) == 0) {
        return "Error loading Section Name";
    }
    $sectionData = mysqli_fetch_array($sectionResult);
    return $sectionData['sname'];
}

$courseQuery = "SELECT * FROM course INNER JOIN csyllabus ON (course.cid = csyllabus.cid AND course.cid=$cid)";
$courseResult = mysqli_query($con, $courseQuery) or die(mysqli_error($con));
$courseData = mysqli_fetch_array($courseResult);
$courseName = $courseData['cname'];
$courseDescription = $courseData['cdesc'];
$courseSyllabus = $courseData['csyllabus'];

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
                                    <img class="rounded-circle"
                                         src="./res/userimages/<?php echo $profileImageFileName; ?>"
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
                                    <div class="text-truncate">I have the photos that you ordered last month, how would
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
                                    <div class="text-truncate">Last month's report looks great, I am very happy with the
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
                                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone told
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
                                 src="./res/userimages/<?php echo $profileImageFileName; ?>">
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
            <?php if (getUserType($con, $_SESSION['uid']) == 0 && !isThisStudentsCourse($con, $cid)) { ?>
                <div class="card shadow-sm" id="enrollButton">

                    <div class="card-body"><a class="btn btn-primary" href="enroll.php?cid=<?php echo $cid; ?>">Unlock Certificate
                            for ₹ <?php echo $courseCost; ?></a></div>
                </div>
            <?php } ?>
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <?php echo $courseName; ?>
                        <a href="<?php echo $back; ?>" class="btn btn-info">Back</a>
                    </h1>
                    <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                </div>

                <!-- Content Row -->
                <?php if(isCourseCompleted($con, $cid, $_SESSION['uid'])){ ?>
                <div class="modal fade" id="completionModal">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Congratulations!</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                You have completed this course. Your certificate can be viewed form your Achievements section as well as by clicking
                                <a target="_blank" href="viewCertificate.php?cid=<?php echo $cid;?>&uid=<?php echo $uid;?>">here</a>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="container">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">About The Course</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <?php echo $courseDescription; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="container">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Syllabus</h6>
                            </div>
                            <div class="card-body">
                                <?php echo $courseSyllabus; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="container">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Contents</h6>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <?php
                                    $numSectionsResult = mysqli_query($con, "SELECT max(`section`) AS numsections FROM csections WHERE cid=$cid") or die(mysqli_error($con));
                                    $numSectionsData = mysqli_fetch_array($numSectionsResult);
                                    $numSections = $numSectionsData['numsections'];
                                    for ($sectionNumber = 1;
                                         $sectionNumber <= $numSections;
                                         $sectionNumber++) {
                                        $sectionName = loadSectionName($con, $sectionNumber, $cid);
                                        ?>
                                        <!-- Section Header - Accordion -->
                                        <div class="row">
                                            <a href="#collapse<?php echo $sectionNumber; ?>" data-toggle="collapse"
                                               data-target="#collapse<?php echo $sectionNumber; ?>">
                                                <h4 class="h4">Section <?php echo $sectionNumber; ?>
                                                    : <?php echo $sectionName; ?></h4>
                                            </a>
                                        </div>
                                        <br>
                                        <!-- Section Content - Collapsible -->
                                        <div class="collapse" id="collapse<?php echo $sectionNumber; ?>">
                                            <div class="container">
                                                <div class="card">
                                                    <div class="card-header">Description</div>
                                                    <div class="card-body"><?php echo printSectionDescription($con, $sectionNumber, $cid); ?></div>
                                                </div>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <?php loadSectionTable($con, $sectionNumber, $cid); ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="container">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Ratings</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-1">
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <h4>Average Rating</h4>

                                        <h2><?php echo round($courseRating, 2); ?>
                                            <small>/5</small>
                                        </h2>
                                        <div>
                                            <a class="btn btn-sm btn-warning d-none d-md-inline"><span
                                                        class="<?php if ($courseRating >= 1) {
                                                            echo "fas";
                                                        } else {
                                                            echo "far";
                                                        } ?> fa-star"></span></a>
                                            <a class="btn btn-sm btn-warning d-none d-md-inline"><span
                                                        class="<?php if ($courseRating >= 2) {
                                                            echo "fas";
                                                        } else {
                                                            echo "far";
                                                        } ?> fa-star"></span></a>
                                            <a class="btn btn-sm btn-warning d-none d-md-inline"><span
                                                        class="<?php if ($courseRating >= 3) {
                                                            echo "fas";
                                                        } else {
                                                            echo "far";
                                                        } ?> fa-star"></span></a>
                                            <a class="btn btn-sm btn-warning d-none d-md-inline"><span
                                                        class="<?php if ($courseRating >= 4) {
                                                            echo "fas";
                                                        } else {
                                                            echo "far";
                                                        } ?> fa-star"></span></a>
                                            <a class="btn btn-sm btn-warning d-none d-md-inline"><span
                                                        class="<?php if ($courseRating == 5) {
                                                            echo "fas";
                                                        } else {
                                                            echo "far";
                                                        } ?> fa-star"></span></a>
                                        </div>
                                    </div>
                                    <div class="col-4 d-none d-sm-block">
                                        <h4>Rating Distribution</h4>
                                        <?php
                                        for ($temp_var = 5; $temp_var > 0; $temp_var--) {
                                            if (!isset($courseRatingDistroPercentages[$temp_var])) {
                                                $courseRatingDistroPercentages[$temp_var] = 0;
                                            }
                                            if (!isset($courseRatingDistro[$temp_var])) {
                                                $courseRatingDistro[$temp_var] = 0;
                                            }
                                        }
                                        $beTheFirstToReview = 0;
                                        if ($courseReviews == 0) {
                                            $beTheFirstToReview = 1;
                                            $courseReviews = 1;
                                        }
                                        ?>

                                        <div class="progress mb-1 text-center">
                                            <div class="progress-bar bg-success"
                                                 style="width:<?php echo $courseRatingDistro[5] * 100 / $courseReviews; ?>%">
                                                <span class="text-white">5 Stars - <?php echo round($courseRatingDistro[5] * 100 / $courseReviews); ?>%</span>
                                            </div>
                                        </div>
                                        <div class="progress mb-1 text-center">
                                            <div class="progress-bar bg-success"
                                                 style="width:<?php echo $courseRatingDistro[4] * 100 / $courseReviews; ?>%">
                                                <span class="text-white">4 Stars - <?php echo round($courseRatingDistro[4] * 100 / $courseReviews); ?>%</span>
                                            </div>
                                        </div>
                                        <div class="progress mb-1 text-center">
                                            <div class="progress-bar bg-warning"
                                                 style="width:<?php echo $courseRatingDistro[3] * 100 / $courseReviews; ?>%">
                                                <span class="text-white">3 Stars - <?php echo round($courseRatingDistro[3] * 100 / $courseReviews); ?>%</span>
                                            </div>
                                        </div>
                                        <div class="progress mb-1 text-center">
                                            <div class="progress-bar bg-warning"
                                                 style="width:<?php echo $courseRatingDistro[2] * 100 / $courseReviews; ?>%">
                                                <span class="text-white">2 Stars - <?php echo round($courseRatingDistro[2] * 100 / $courseReviews); ?>%</span>
                                            </div>
                                        </div>
                                        <div class="progress mb-1 text-center">
                                            <div class="progress-bar bg-danger"
                                                 style="width:<?php echo $courseRatingDistro[1] * 100 / $courseReviews; ?>%">
                                                <span class="text-white">1 Star - <?php echo round($courseRatingDistro[1] * 100 / $courseReviews); ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <?php if (isThisStudentsCourse($con, $cid)) { ?>
                                    <div class="container">
                                        <form method="post" action="creview.php">
                                            <legend><?php if ($beTheFirstToReview == 1) {
                                                    echo "Be the First to ";
                                                } ?>Leave a review!
                                            </legend>
                                            <div class="form-group">
                                                <label class="col-form-label">Review Title</label>
                                                <input name="title" type="text" class="form-control form-control-lg"
                                                       placeholder="Provide a title for your review">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Description</label>
                                                <textarea name="desc" class="form-control"
                                                          placeholder="Add some details to your review. This helps the trainers a lot!"></textarea>
                                                <input type="hidden" name="uid" value="<?php echo $_SESSION['uid']; ?>">
                                                <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Rating</label>
                                                <input type="range" class="form-control-range slider" min="1" max="5"
                                                       name="rating" required="true"
                                                       onchange="updateTextInput(this.value)">
                                                <span id="textInput"></span><span>/5&nbsp;<span
                                                            class="far fa-star"></span> </span>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control btn btn-primary" type="submit"
                                                       value="Submit Review">
                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>
                                <br>
                                <div class="container p-1">
                                    <?php
                                    $reviewQuery = "SELECT * FROM creviews WHERE cid=$cid ORDER BY dateofreview";
                                    $reviewResult = mysqli_query($con, $reviewQuery) or die(mysqli_error($con));
                                    while ($reviewData = mysqli_fetch_array($reviewResult)) {
                                        $reviewTitle = $reviewData['rtitle'];
                                        $reviewDesc = $reviewData['rdesc'];
                                        $reviewRating = $reviewData['rating'];
                                        $reviewDate = $reviewData['dateofreview'];
                                        $reviewerNameQuery = "SELECT fname, lname FROM user WHERE uid=" . $reviewData['uid'];
                                        $reviewerNameResult = mysqli_query($con, $reviewerNameQuery) or die(mysqli_error($con));
                                        $reviewerNameData = mysqli_fetch_array($reviewerNameResult);
                                        $reviewerName = $reviewerNameData['fname'] . " " . $reviewerNameData['lname'];
                                        $deleteReviewButton = "";
                                        if ($reviewData['uid'] == $_SESSION['uid']) {
                                            $deleteReviewButton = "<a class='btn btn-danger' href='deleteReview.php?crid=" . $reviewData['crid'] . "'><span class='fas fa-trash'></span></a>";
                                        }
                                        ?>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-1">
                                                    <img class="img-profile rounded d-none d-lg-inline"
                                                         src="https://dummyimage.com/60x60/000/fff">
                                                    <br>
                                                    <a href="viewprofile.php?uid=<?php echo $reviewData['uid']; ?>"><?php echo $reviewerName ?></a>
                                                </div>
                                                <div class="col-md-10 col-sm-8">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-5"><h5
                                                                    class="d-inline"><?php echo $reviewTitle; ?></h5>
                                                        </div>
                                                        <div class="col-md-2 col-sm-3 text-right text-primary">
                                                            <h6><?php echo $reviewRating; ?>&nbsp;<span
                                                                        class="far fa-star"></span></h6></div>
                                                        <div class="col-md-3 d-none d-lg-inline text-right text-primary">
                                                            <h6>
                                                                <?php echo $reviewDate; ?>
                                                            </h6>
                                                        </div>
                                                        <div class="col-1 p-1 text-right text-primary"><?php echo $deleteReviewButton; ?></div>
                                                    </div>
                                                    <div class="d-none d-lg-block">
                                                        <?php echo $reviewDesc; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <hr>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Author<?php echo $authorPlurality; ?></h6>
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
        updateTextInput(3);
        if($('#completionModal').length){
            $('#completionModal').modal('show');
        }
    });

    function updateTextInput(val) {
        if (document.getElementById('textInput')) {
            document.getElementById('textInput').innerHTML = val;
        }
    }
</script>
</body>

</html>