<?php

/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 1:12 PM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Video Resource</title>
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
    <div class="container">
        <?php
        if (isset($_GET['rid'])) {
            $rid = $_GET['rid'];
            if (!authToEditResource($con, $rid)) {
                die("You don't have authorization for editing this resource. Please contact admin.");
            }
            if (!isResource($con, $rid, RES_VIDEO)) {
                die("Warning: Wrong request issued - resource type mismatch.");
            }
            $getResQuery = "SELECT cid,rtext, rdata, rdate FROM cresources WHERE rid=$rid";
            $getResResult = mysqli_query($con, $getResQuery) or die($getResQuery);
            if (mysqli_num_rows($getResResult) > 0) {
                $getResData = mysqli_fetch_array($getResResult);
                $cid = $getResData['cid'];
                echo "<h3>Editing Video Resource</h3><hr>";
                ?>

                <div class="container">
                    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="editVideoHandler.php">
                        <fieldset>


                            <!-- Input video name to show-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="vname">Video Name</label>
                                <div class="col-md-6">
                                    <input id="vname" name="vname" type="text" placeholder="Give a name for your video"
                                           class="form-control input-md" value="<?php echo $getResData['rtext'];?>">
                                    <span class="help-block">This will show up as the name of the video for your students. Leave this empty to use the name of your uploaded file.</span>
                                    <!-- Send the courseID, resourceType and sectionNumber to the form handler -->
                                    <input type="hidden" name="rid" value="<?php echo $rid; ?>">
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
                                    <button type="submit" class="btn btn-success">Confirm</button>
                                    <a class="btn btn-primary"
                                       href="coursedit.php?<?php echo "cid=$cid";?>Cancel</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <?php
            } else {
                header("location:404.html");
            }
        } else {
            header("location:404.html");
        }
        ?>
    </div>
    </body>
    </html>
<?php
if (!isset($_GET['rid'])) {
    header("location:404.html");
}
