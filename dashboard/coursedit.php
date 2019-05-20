<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 15/5/19 4:45 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

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
            return "glyphicon-file";
        case RES_VIDEO:
            return "glyphicon-film";
        case RES_FILE:
            return "glyphicon-save-file";
        case RES_YOUTUBE:
            return "glyphicon-play-circle";
        case RES_LINK:
            return "glyphicon-link";
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
                        <span class="glyphicon <?php echo printGlyph($rtype); ?>"></span>
                        View
                    </a>
                    <a class="btn btn-warning"
                       href="<?php echo resEditFile($rtype); ?>?rid=<?php echo $rid; ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a class="btn btn-danger" href="deleteResource.php?rid=<?php echo $rid; ?>">
                        <span class="glyphicon glyphicon-trash"></span>
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

if (isset($_GET['cid']) && isThisUsersCourse($con, $_GET['cid'])) {
    $_SESSION['cid'] = $_GET['cid'];

    $cid = $_SESSION['cid'];

    $cdescQuery = "SELECT cname,cdesc FROM course WHERE cid=$cid";
    $cdescResult = mysqli_query($con, $cdescQuery) or die(mysqli_error($con));
    $cdesc = "";
    $cname = "";
    if (mysqli_num_rows($cdescResult) > 0) {
        $cdescData = mysqli_fetch_array($cdescResult);
        $cdesc = $cdescData['cdesc'];
        $cname = $cdescData['cname'];
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

        <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
        <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

    </head>
    <body>
    <!--Course Editor-->
    <div class="container">
        <div class="row">
            <h3>Course Editor
                <a class="btn btn-primary" href="viewcourse.php?cid=<?php echo $cid; ?>">Preview
                    Course</a>
            </h3>

        </div>
        <div class="row">
            <h4>Editing the course: <?php echo $cname; ?></h4><br>
        </div>
        <div class="row">
            <form method="POST" action="updateDescription.php">
                <div class="container">
                    <label class="control-label" for="cdesc">Course Description</label>
                    <textarea class="form-control" id="cdesc" name="cdesc"><?php echo $cdesc; ?></textarea>
                    <br>
                    <button type="submit" name="updatecdesc" value="true" class="btn btn-success">Update Course
                        Description
                    </button>
                </div>
            </form>
            <br>
            <form class="form-horizontal" method="POST" action="updateSyllabus.php">
                <!-- Textarea to input new course syllabus text-->
                <div class="container">
                    <label class="control-label" for="csyllabus">Course Syllabus</label>
                    <textarea class="form-control" id="csyllabus" name="csyllabus"><?php echo $csyllabus; ?></textarea>
                    <!-- Button to update course syllabus-->
                    <br>
                    <button type="submit" name="updatecsyllabus" class="btn btn-success">Update Course Syllabus</button>
                </div>

            </form>
        </div>
        <br><br><br>
        <div class="row">
            <div class="col-md-9">
                <h3 style="display: inline">Sections</h3>
            </div>
            <div class="col-md-3">
                <a href="createsection.php?cid=<?php echo $cid; ?>" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Section</span>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
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
                        <div class="col-md-6">
                            <h4 style="display: inline">Section <?php echo $sectionNumber; ?>
                                : <?php echo $sectionName; ?></h4>
                        </div>

                        <div class="col-md-6">
                            <a href="createresource.php?cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
                               class="btn btn-info btn-icon-split">
                            <span class="icon text-white-25">
                                <i class="fas fa-plus"></i>
                                Add Resources
                            </span>
                            </a>
                            <a href="editSection.php?cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
                               class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-25">
                                <i class="fas fa-pencil"></i>
                                Edit Section
                            </span>
                            </a>
                            <a href="deletesection.php?cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>&numsections=<?php echo $numSections; ?>"
                               class="btn btn-danger btn-icon-split">
                            <span class="icon text-white-25">
                                <i class="fas fa-trash"></i>
                                Delete Section
                            </span>
                            </a>
                        </div>
                    </a>
                </div>
                <br>
                <div class="row">
                    <!-- Section Content - Collapsible -->
                    <div class="collapse" id="collapse<?php echo $sectionNumber; ?>" style="">
                        <div class="container">
                            <div class="container">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Description</div>
                                    <div class="panel-body"><?php echo printSectionDescription($con, $sectionNumber, $cid); ?></div>
                                </div>
                            </div>
                            <div class="container">
                                <table class="table table-responsive table-bordered">
                                    <?php loadSectionTable($con, $sectionNumber, $cid); ?>
                                </table>
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
    </body>
    </html>
    <?php
}
if (!isset($_GET['cid'])) {
    header('404.html');
}
?>
