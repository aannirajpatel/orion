<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 4:21 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit YouTube Link</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrp/3.4.0/js/bootstrap.min.js"></script>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    </head>
    <body>
    <div class="container">
        <?php
        if (isset($_GET['rid'])) {
            $rid = $_GET['rid'];
            if (!authToEditResource($con, $rid)) {
                die("You don't have authorization for editing this resource. Please contact admin.");
            }
            if (!isResource($con, $rid, RES_YOUTUBE)) {
                die("Warning: Wrong request issued - resource type mismatch.");
            }
            $getResQuery = "SELECT cid,rtext, rdata,raddr,rdate FROM cresources WHERE rid=$rid";
            $getResResult = mysqli_query($con, $getResQuery) or die($getResQuery);
            if (mysqli_num_rows($getResResult) > 0) {
                $getResData = mysqli_fetch_array($getResResult);
                $cid = $getResData['cid'];
                echo "<h3>Editing youtube link</h3><hr>";
                ?>

                <div class="container">
                    <form class="form-horizontal" method="post" action="editYoutubeLinkHandler.php">
                        <fieldset>
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="title">YouTube Link Title</label>
                                <div class="col-md-6">
                                    <input id="title" name="title" type="text"
                                           value="<?php echo $getResData['rtext']; ?>"
                                           placeholder="Edit youtube link title" class="form-control input-md"
                                           required="">
                                    <input name="rid" value="<?php echo $rid; ?>" type="hidden">
                                </div>
                            </div>

                            <!-- Textarea -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="linkaddress">YouTube Link Address</label>
                                <div class="col-md-4">
                                    <input class="form-control" type="text" id="linkaddress" name="linkaddress" value="<?php echo $getResData['raddr']; ?>">
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