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

if (!isset($_REQUEST['rid'])) {
    header("location:404.php");
}

$rid = $_REQUEST['rid'];
$cid = getCidFromRid($con, $rid);

if (!isThisUsersCourse($con, $cid)) {
    header("location:404.php");
}
if (isset($_POST['updateQuiz'])) {
    $n = $_POST['maxQno'];
    $newQuizName = mysqli_real_escape_string($con, $_POST['quizname']);
    $maxAttempts = $_POST['maxAttempts'];
    $quizNameUpdateQuery = "UPDATE cresources SET rtext = '$newQuizName', raddr='$maxAttempts' WHERE rid=$rid";
    $quizNameUpdateResult = mysqli_query($con, $quizNameUpdateQuery) or die(mysqli_error($con));

    $deleteAnswersQuery = "DELETE FROM quizanswer WHERE quizid=$rid";
    $deleteAnswersResult = mysqli_query($con, $deleteAnswersQuery) or die(mysqli_error($con));

    $deleteQuestionsQuery = "DELETE FROM quizquestion WHERE quizid=$rid";
    $deleteQuestionsResult = mysqli_query($con, $deleteQuestionsQuery) or die(mysqli_error($con));
    $i = 0;
    for ($j = 1; $j <= $n; $j++) {
        $qkey = 'q' . $j;
        $akey = 'a' . $j;
        if (!isset($_POST[$qkey]) || !isset($_POST[$qkey])) {
            continue;
        }
        $quizadata = $_POST[$akey];
        $quizqtext = mysqli_real_escape_string($con, $_POST[$qkey]);
        if (count($quizadata) != 4) {
            header("location:404.php");
        }
        if ($quizqtext == "") {
            continue;
        }
        $i++;
        $correctAnswer = $quizadata[0];
        $incorrectAnswer1 = $quizadata[1];
        $incorrectAnswer2 = $quizadata[2];
        $incorrectAnswer3 = $quizadata[3];

        $insertQuestionQuery = "INSERT INTO quizquestion(quizid,qno,quizqtext) VALUES ($rid,$i,'$quizqtext')";
        $insertQuestionResult = mysqli_query($con, $insertQuestionQuery) or die(mysqli_error($con));

        $insertAnswerQuery = "INSERT INTO quizanswer(quizid, qno, answertext, marks) VALUES ($rid, $i, '$correctAnswer', 1)";
        $insertAnswerResult = mysqli_query($con, $insertAnswerQuery) or die(mysqli_error($con));
        if ($incorrectAnswer1 != "") {
            $insertAnswerQuery = "INSERT INTO quizanswer(quizid, qno, answertext, marks) VALUES ($rid, $i, '$incorrectAnswer1', 0)";
            $insertAnswerResult = mysqli_query($con, $insertAnswerQuery) or die(mysqli_error($con));
        }
        if ($incorrectAnswer2 != "") {
            $insertAnswerQuery = "INSERT INTO quizanswer(quizid, qno, answertext, marks) VALUES ($rid, $i, '$incorrectAnswer2', 0)";
            $insertAnswerResult = mysqli_query($con, $insertAnswerQuery) or die(mysqli_error($con));
        }
        if ($incorrectAnswer3 != "") {
            $insertAnswerQuery = "INSERT INTO quizanswer(quizid, qno, answertext, marks) VALUES ($rid, $i, '$incorrectAnswer3', 0)";
            $insertAnswerResult = mysqli_query($con, $insertAnswerQuery) or die(mysqli_error($con));
        }
    }
}
$quizDetailsQuery = "SELECT * FROM cresources WHERE rid = $rid";
$quizDetailsResult = mysqli_query($con, $quizDetailsQuery) or die(mysqli_error($con));
if (mysqli_num_rows($quizDetailsResult) != 1) {
    header("location:404.php");
}
$quizDetailsData = mysqli_fetch_array($quizDetailsResult);
$quizname = $quizDetailsData['rtext'];
$quizMaxAttempts = intval($quizDetailsData['raddr']);

$questionsQuery = "SELECT * FROM quizquestion WHERE quizid = $rid ORDER BY qno";
$questionsResult = mysqli_query($con, $questionsQuery) or die(mysqli_error($con));
$qno = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Builder</title>
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
                        Quiz Builder <a href="coursedit.php?cid=<?php echo $cid;?>" class="btn btn-primary">Back</a>
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

                                <form method="post" action="">
                                    <?php
                                    $questionSr = 0;
                                    ?>
                                    <div class="form-group">
                                        <label for="quizname" class="text-info"><h3>Quiz Name</h3></label>
                                        <input type="text" name="quizname" value="<?php echo $quizname; ?>"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="maxAttempts">Set Max Attempts: [Keep 0 to prevent students from accessing while you are building the quiz]</label>
                                        <select name="maxAttempts" class="form-control">
                                            <?php for ($i = 0; $i <= 10; $i++) {
                                                ?>
                                                <option value="<?php echo $i; ?>"
                                                        <?php if ($i == $quizMaxAttempts){ ?>selected=""<?php } ?>><?php echo $i; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php
                                    while ($questionsData = mysqli_fetch_array($questionsResult)) {
                                        $qno = $questionsData['qno'];
                                        ?>
                                        <a onclick="undoRemoveQuestion(<?php echo $qno; ?>);"
                                           id='undoBtn<?php echo $qno; ?>' class="undoBtn btn btn-success text-white">Undo
                                            Deletion of Question <?php echo $qno; ?></a>
                                        <div class="form-group" id="remove<?php echo $qno; ?>">
                                            <h3 class="text-primary">Question <?php echo $qno;
                                                $questionSr++; ?>
                                                <a onclick="removeQuestion(<?php echo $qno; ?>);"
                                                   id='deleteBtn<?php echo $qno; ?>' class="btn btn-danger text-white">Delete</a>
                                            </h3>
                                            <label for="q<?php echo $qno;?>" class="text-info">Question</label>
                                            <input type="text" name="q<?php echo $qno; ?>" id="q<?php echo $qno; ?>"
                                                   value="<?php echo $questionsData['quizqtext']; ?>"
                                                   class="form-control">
                                            <br>
                                            <?php
                                            $answerQuery = "SELECT * FROM quizanswer WHERE qno = $qno AND quizid = $rid AND marks=1";
                                            $answerResult = mysqli_query($con, $answerQuery) or die(mysqli_error($con));
                                            $optionCount = 0;
                                            ?>
                                            <label for="a<?php echo $qno; ?>[]" class="text-success">Correct
                                                answer</label>
                                            <?php
                                            while ($answerData = mysqli_fetch_array($answerResult)) {
                                                $optionCount += 1;
                                                $answerText = $answerData['answertext'];
                                                ?>
                                                <input type="text" name="a<?php echo $qno; ?>[]"
                                                       value="<?php echo $answerText; ?>"
                                                       class="form-control">
                                                <?php
                                            }
                                            $incorrectAnswerQuery = "SELECT * FROM quizanswer WHERE (qno = $qno AND quizid = $rid AND marks = 0)";
                                            $incorrectAnswerResult = mysqli_query($con, $incorrectAnswerQuery) or die(mysqli_error($con));
                                            while ($incorrectAnswerData = mysqli_fetch_array($incorrectAnswerResult)) {
                                                $optionCount += 1;
                                                $incorrectAnswerText = $incorrectAnswerData['answertext'];
                                                ?>
                                                <label for="a<?php echo $qno; ?>[]" class="text-danger">Incorrect
                                                    answer</label>
                                                <input type="text" name="a<?php echo $qno; ?>[]"
                                                       value="<?php echo $incorrectAnswerText; ?>"
                                                       class="form-control">
                                                <?php
                                            }
                                            while ($optionCount < 4) {
                                                $optionCount++;
                                                ?>
                                                <input type="text" name="a<?php echo $qno; ?>[]" value=""
                                                       class="form-control">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    if (isset($_POST['moreQuestions'])) {
                                        $moreQuestionsCount = $_POST['moreQuestionsCount'];
                                        for ($i = 1; $i <= $moreQuestionsCount && $qno <= 100; $i++) {
                                            $qno++;
                                            ?>
                                            <a onclick="undoRemoveQuestion(<?php echo $qno; ?>);"
                                               id='undoBtn<?php echo $qno; ?>'
                                               class="undoBtn btn btn-success text-white">Undo Deletion of
                                                Question <?php echo $qno; ?></a>
                                            <div class="form-group" id="remove<?php echo $qno; ?>">
                                                <h3 class="text-primary">Question <?php
                                                    echo $qno;
                                                    $questionSr++; ?>
                                                    <a onclick="removeQuestion(<?php echo $qno; ?>);"
                                                       id='deleteBtn<?php echo $qno; ?>'
                                                       class="btn btn-danger text-white">Delete</a>
                                                </h3>
                                                <label for="q<?php echo $qno;?>" class="text-info">Question</label>
                                                <input type="text" name="q<?php echo $qno; ?>" id="q<?php echo $qno; ?>"
                                                       class="form-control"><?php echo $questionsData['quizqtext']; ?>
                                                <br>
                                                <label for="a<?php echo $qno; ?>[]" class="text-success">Correct
                                                    answer</label>
                                                <input type="text" name="a<?php echo $qno; ?>[]" value=""
                                                       class="form-control">
                                                <?php
                                                for ($j = 1; $j <= 3; $j++) {
                                                    ?>
                                                    <label for="a<?php echo $qno; ?>[]" class="text-danger">Incorrect
                                                        answer</label>
                                                    <input type="text" name="a<?php echo $qno; ?>[]" value=""
                                                           class="form-control">
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <br><br>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <div class="form-group">
                                        <input type="hidden" name="rid" value="<?php echo $rid; ?>">
                                        <input type="hidden" name="maxQno" value="<?php echo $questionSr; ?>">
                                        <input type="submit" name="updateQuiz" value="Update"
                                               class="form-control-md btn btn-success">
                                        <input type="reset" value="Reset" class="form-control-md btn btn-danger">
                                        <a href="viewQuiz.php?rid=<?php echo $rid; ?>" class="btn btn-primary">View
                                            Quiz</a>
                                    </div>
                                </form>
                                <!--Dont move this form from here. The $qno variable is sensitive to placement as it stores the maximum question number value when program execution reaches this line-->
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label for="moreQuestionsCount">
                                            Select number of questions to add*:
                                        </label>
                                        <select name="moreQuestionsCount" class="form-control-sm">
                                            <?php
                                            for ($i = 1; $i <= 20; $i++) {
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" name="rid" value="<?php echo $rid; ?>">
                                        <input type="submit" name="moreQuestions" value="Go"
                                               class="form-control-md btn btn-success">
                                    </div>
                                    <p>
                                        <small>
                                            *IF YOU HAVE ADDED MORE QUESTIONS ONCE AND NOT CLICKED ON UPDATE, THEN
                                            PREVIOUSLY ADDED QUESTIONS WILL BE OVERWRITTEN
                                            WITH BLANKS
                                        </small>
                                    </p>
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
<script>
    $(document).ready(function () {
        $('.undoBtn').hide();
    });

    function removeQuestion(qno) {
        $('#q' + qno).attr("hiddenvalue", $('#q' + qno).val());
        $('#q' + qno).attr("value", "");
        $('#remove' + qno).hide();
        $('#removeBtn' + qno).hide();
        $('#undoBtn' + qno).show();
    }

    function undoRemoveQuestion(qno) {
        $('#q' + qno).attr("value", $('#q' + qno).attr("hiddenvalue"));
        $('#remove' + qno).show();
        $('#undoBtn' + qno).hide();
        $('#removeBtn' + qno).show();
    }
</script>
<?php require('getNewCommsData.php'); ?>
<?php require('js/communicationsBadge.php'); ?>
</body>
</html>