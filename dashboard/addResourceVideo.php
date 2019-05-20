<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 3:28 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_GET['cid']) && isset($_GET['section']) && isset($_GET['rtype'])) {
    $cid = $_GET['cid'];

    $sectionNumber = $_GET['section'];

    $rtype = $_GET['rtype'];

    if (!isThisUsersCourse($con, $cid) || $rtype != RES_VIDEO) {
        header("location:404.html");
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Add Video</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <br>
    <br>
    <div class="container">
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="uploadvideo.php">
            <fieldset>

                <!-- Form Name -->
                <legend>Add A Video</legend>

                <!-- Input video name to show-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="vname">Video Name</label>
                    <div class="col-md-6">
                        <input id="vname" name="vname" type="text" placeholder="Give a name for your video"
                               class="form-control input-md">
                        <span class="help-block">This will show up as the name of the video for your students. Leave this empty to use the name of your uploaded file.</span>
                        <!-- Send the courseID, resourceType and sectionNumber to the form handler -->
                        <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                        <input type="hidden" name="rtype" value="<?php echo $rtype; ?>">
                        <input type="hidden" name="section" value="<?php echo $sectionNumber; ?>">
                    </div>
                </div>

                <!-- Upload video Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="vupload">Upload Video File</label>
                    <div class="col-md-4">
                        <input id="vupload" name="vupload" class="input-file" type="file">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <a class="btn btn-primary" href="createresource.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>">Cancel</a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    </body>
    </html>

    <?php
}else {
    header("location:404.html");
}