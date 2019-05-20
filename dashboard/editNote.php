<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 11:27 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Note</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrp/3.4.0/js/bootstrap.min.js"></script>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


        <!--
        Copyright (c) 2007-2008 Brian Kirchoff (http://nicedit.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
        -->
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
            if (!isResource($con, $rid, RES_NOTE)) {
                die("Warning: Wrong request issued - resource type mismatch.");
            }
            $getResQuery = "SELECT cid,rtext, rdata, rdate FROM cresources WHERE rid=$rid";
            $getResResult = mysqli_query($con, $getResQuery) or die($getResQuery);
            if (mysqli_num_rows($getResResult) > 0) {
                $getResData = mysqli_fetch_array($getResResult);
                $cid = $getResData['cid'];
                echo "<h3>Editing Note</h3><hr>";
                ?>

                <div class="container">
                    <form class="form-horizontal" method="post" action="editNoteHandler.php">
                        <fieldset>
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="title">Note Title</label>
                                <div class="col-md-6">
                                    <input id="title" name="title" type="text"
                                           value="<?php echo $getResData['rtext']; ?>"
                                           placeholder="Edit note title" class="form-control input-md"
                                           required="">
                                    <input name="rid" value="<?php echo $rid; ?>" type="hidden">
                                </div>
                            </div>

                            <!-- Textarea -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="note">Note</label>
                                <div class="col-md-4">
                        <textarea class="form-control" id="note" name="note">
                            <?php echo $getResData['rdata']; ?>
                        </textarea>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="singlebutton"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success">Confirm</button>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                    <br>
                    <a class="btn btn-primary" href='coursedit.php?<?php echo "cid=$cid"; ?>'>Cancel</a>
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
?>