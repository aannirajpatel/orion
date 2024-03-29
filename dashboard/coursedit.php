<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 15/5/19 4:45 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/files.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
$uid = $_SESSION['uid'];
$email = $_SESSION['email'];
$profileImageFileNameQuery = "SELECT profileImageFileName FROM user WHERE email='$email'";
$profileImageFileNameResult = mysqli_query($con, $profileImageFileNameQuery) or die(mysqli_error($con));
$profileImageFileNameData = mysqli_fetch_array($profileImageFileNameResult);
$profileImageFileName = $profileImageFileNameData['profileImageFileName'];
$profileImageFileAddress = $userProfileImageFolder . $profileImageFileName;

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
        case RES_QUIZ:
            return "Quiz";
    }
}

function printGlyph($rtype)
{
    switch ($rtype) {
        case RES_NOTE:
            return "far fa-file";
        case RES_VIDEO:
            return "far fa-play-circle";
        case RES_FILE:
            return "fas fa-file-download";
        case RES_YOUTUBE:
            return "fab fa-youtube";
        case RES_LINK:
            return "fas fa-link";
        case RES_QUIZ:
            return "fas fa-scroll";
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
    <th>SNo.</th>
    <th>Name</th>
    <th>Type</th>
    <th>Date created</th>
    <th>Options</th>
    </tr>
    </thead>
    <tbody>
    ";
    $resourceQuery = "SELECT * FROM cresources WHERE cid=$cid AND section=$sectionNumber";
    $resourceResult = mysqli_query($con, $resourceQuery) or die(mysqli_error($con));
    $resourceNumber = 0;
    $totalResources = mysqli_num_rows($resourceResult);
    if ($totalResources == 0) {
        echo "<tr><td colspan='5'>No resources yet. Add resources using the + button.</td></tr>";
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
                <td><?php echo $resourceData['rdate']; ?></td>
                <td>
                    <a class="btn btn-primary"
                       href="<?php echo resViewFile($rtype); ?>?rid=<?php echo $rid; ?>">
                        <span class="<?php echo printGlyph($rtype); ?>"></span>
                        <span class="d-none d-md-inline">View</span>
                    </a>
                    <a class="btn btn-warning"
                       href="<?php echo resEditFile($rtype); ?>?rid=<?php echo $rid; ?>">
                        <span class="fas fa-pen"></span>
                        <span class="d-none d-md-inline">Edit</span>
                    </a>
                    <a class="btn btn-danger" href="deleteResource.php?rid=<?php echo $rid; ?>">
                        <span class="fas fa-trash"></span>
                        <span class="d-none d-md-inline">Delete</span>
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

if (isset($_GET['cid']) && isThisUsersCourse($con, $_GET['cid'])) {

    $cid = $_GET['cid'];

    $cdescQuery = "SELECT * FROM course WHERE cid=$cid";
    $cdescResult = mysqli_query($con, $cdescQuery) or die(mysqli_error($con));
    $cdesc = "";
    $cname = "";
    if (mysqli_num_rows($cdescResult) > 0) {
        $cdescData = mysqli_fetch_array($cdescResult);
        $cdesc = $cdescData['cdesc'];
        $cname = $cdescData['cname'];
        $cimg = $cdescData['cimg'];
        $selectedCategory = $cdescData['category'];
    }

    $csyllabusQuery = "SELECT csyllabus FROM csyllabus WHERE cid=$cid";
    $csyllabusResult = mysqli_query($con, $csyllabusQuery);
    $csyllabus = "";
    if (mysqli_num_rows($csyllabusResult) > 0) {
        $csyllabusData = mysqli_fetch_array($csyllabusResult);
        $csyllabus = $csyllabusData['csyllabus'];
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit your course</title>
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
                <div class="container-fluid" style="color: #000;">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            Edit Course: <?php echo $cname; ?>
                            <a href="trainer.php" class="btn btn-info">Back</a>
                        </h1>
                        <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                    </div>


                    <!--Template for creating cards is provided below. Made by Aan Patel. Enjoy :) -->
                    <!--                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Course Specifics</h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="updateCategoryAndImg.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="category">Update course category:</label>
                                            <select class="form-control" name="category">
                                                <?php if ($selectedCategory != "") { ?>
                                                    <option value="<?php echo $selectedCategory; ?>"><?php echo $selectedCategory; ?></option>
                                                <?php } ?>
                                                <?php
                                                $categories = array("Engineering", "Business", "Programming");
                                                foreach ($categories as $category) {
                                                    if ($category != $selectedCategory)
                                                        ?>
                                                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="cid" value="<?php echo $cid;?>">
                                            <br>
                                            <?php
                                            if ($cimg != "") {
                                                ?>
                                                Course image:<br>
                                                <img class="img-fluid" src="<?php echo $cimg; ?>">
                                                <br>
                                                <?php
                                            }
                                            ?>
                                            <label for="courseImg">Update course image:</label>
                                            <input class="form-control-file" type="file" name="courseImg">
                                            <span class="help-block font-italic">Don't put any file here if you want the course image to stay the same</span>
                                            <br><br>
                                            <input class="form-control btn btn-primary text-white" type="submit" value="Update">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Course Name</h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="updateCname.php?cid=<?php echo $cid; ?>">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="cname"
                                                   value="<?php echo $cname; ?>">
                                            <br>
                                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                            <input type="submit" class="form-control bg-primary text-white"
                                                   value="Update">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Trainers for this course</h6>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <?php
                                        $collabListQuery = "SELECT uid FROM ctrainers WHERE cid=$cid";
                                        $collabListResult = mysqli_query($con, $collabListQuery) or die(mysqli_error($con));
                                        while ($collabListData = mysqli_fetch_array($collabListResult)) {
                                            $nameQuery = "SELECT fname, lname FROM user WHERE uid=" . $collabListData['uid'];
                                            $nameResult = mysqli_query($con, $nameQuery);
                                            $nameData = mysqli_fetch_array($nameResult);
                                            ?>
                                            <a target="_blank" class="list-group-item"
                                               href="viewprofile.php?uid=<?php echo $collabListData['uid']; ?>"><?php echo $nameData['fname'] . " " . $nameData['lname']; ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Add a collaborator for your
                                        course</h6>
                                </div>
                                <div class="card-body">
                                    <form action="addCollaborator.php" method="post">
                                        <div class="form-group">
                                            <input type="text" id="collaboratorLiveSearch" name="collab"
                                                   class="form-control" onkeyup="showResult(this.value)">
                                            <div id="collaboratorLiveSearchResult" class="list-group"></div>
                                            <br>
                                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                            <input type="submit" class="form-control text-white bg-primary"
                                                   value="Add Collaborator">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Course Editor-->
                    <br>
                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Course Description</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="updateDescription.php">
                                        <div class="form-group">
                                    <textarea class="form-control" id="cdesc"
                                              name="cdesc"><?php echo $cdesc; ?></textarea>
                                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="updatecdesc" value="true"
                                                    class="btn btn-primary form-control">Update
                                                Course
                                                Description
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="container">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Course Syllabus</h6>
                                </div>
                                <div class="card-body">

                                    <form method="POST" action="updateSyllabus.php">
                                        <!-- Textarea to input new course syllabus text-->
                                        <div class="form-group">
                                            <textarea class="form-control" id="csyllabus"
                                                      name="csyllabus"><?php echo $csyllabus; ?></textarea>
                                            <!-- Button to update course syllabus-->
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="updatecsyllabus"
                                                    class="form-control btn btn-primary">Update
                                                Course
                                                Syllabus
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="container d-sm-flex align-items-center justify-content-between mb-4">
                            <h4 style="display: inline">Sections</h4>
                            <a href="createsection.php?cid=<?php echo $cid; ?>"
                               class="btn btn-success">
                    <span class="icon text-white">
                      <i class="fas fa-plus"></i>
                         Add Section
                    </span>
                            </a>

                        </div>
                        <br><br>
                        <div class="container">
                            <?php
                            $numSectionsResult = mysqli_query($con, "SELECT max(`section`) AS numsections FROM csections WHERE cid=$cid") or die(mysqli_error($con));
                            $numSectionsData = mysqli_fetch_array($numSectionsResult);
                            $numSections = $numSectionsData['numsections'];
                            for ($sectionNumber = 1; $sectionNumber <= $numSections; $sectionNumber++) {
                                $sectionName = loadSectionName($con, $sectionNumber, $cid);
                                ?>
                                <!-- Section Header - Accordion -->
                                <div class="container d-sm-flex align-items-center justify-content-between mb-4">
                                    <a href="#collapse<?php echo $sectionNumber; ?>" data-toggle="collapse"
                                       data-target="#collapse<?php echo $sectionNumber; ?>"
                                       style=":hover{ text-underline: none;}">
                                        <h4>Section <?php echo $sectionNumber; ?><?php echo ": " . $sectionName; ?></h4>
                                        <div>
                                            <a href="createresource.php?cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
                                               class="btn btn-info btn-icon-split">
                            <span class="icon text-white-25">
                                <i class="fas fa-plus"></i>
                                <span class="d-none d-sm-inline">Add Resources</span>
                            </span>
                                            </a>
                                            <a href="editSection.php?cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
                                               class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-25">
                                <i class="fas fa-pen"></i>
                                <span class="d-none d-sm-inline">Edit Section</span>
                            </span>
                                            </a>
                                            <a href="deletesection.php?cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>&numsections=<?php echo $numSections; ?>"
                                               class="btn btn-danger btn-icon-split">
                            <span class="icon text-white-25">
                                <i class="fas fa-trash"></i>
                                <span class="d-none d-sm-inline">Delete Section</span>
                            </span>
                                            </a>
                                        </div>
                                    </a>
                                </div>
                                <div class="container">
                                    <!-- Section Content - Collapsible -->
                                    <div class="collapse" id="collapse<?php echo $sectionNumber; ?>" style="">
                                        <div class="container">
                                            <div class="card shadow mb-4">
                                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                    <h6 class="m-0 font-weight-bold text-primary">Description</h6>
                                                </div>
                                                <div class="card-body"><?php echo printSectionDescription($con, $sectionNumber, $cid); ?></div>
                                            </div>
                                            <br>
                                            <div class="card shadow mb-4">
                                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                    <h6 class="m-0 font-weight-bold text-primary">Resources</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <?php loadSectionTable($con, $sectionNumber, $cid); ?>
                                                        </table>
                                                    </div>
                                                </div>
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

    <?php require('js/communicationsBadge.php'); ?>

    <script>

        function showResult(str) {
            $('#collaboratorLiveSearchResult').show();
            if (str.length == 0) {
                document.getElementById("collaboratorLiveSearchResult").innerHTML = "";
                document.getElementById("collaboratorLiveSearchResult").style.border = "0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("collaboratorLiveSearchResult").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "collaboratorSearch.php?q=" + str, true);
            xmlhttp.send();
        }

        function addThisEmail(str) {
            $("#collaboratorLiveSearch").val(str);
            $("#collaboratorLiveSearchResult").hide();
        }
    </script>
    <?php require('rotateScreen.php'); ?>
    </body>

    </html>


    <?php
}
if (!isset($_GET['cid'])) {
    header('404.php');
}
?>