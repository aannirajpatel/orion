<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 17/5/19 11:46 AM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');

if (isset($_GET['cid']) && isset($_GET['section']) && isset($_GET['rtype'])) {
    $cid = $_GET['cid'];

    $sectionNumber = $_GET['section'];

    $rtype = $_GET['rtype'];

    if (!isThisUsersCourse($con, $cid)) {
        header("location:404.html");
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Add Note</title>
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
        <form class="form-horizontal" method="post" action="createLink.php">
            <fieldset>

                <!-- Form Name -->
                <legend>Add A Link</legend>

                <!-- Text input-->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="title">Link Title</label>
                    <div class="col-md-6">
                        <input id="title" name="title" type="text" placeholder="Provide a title for the link"
                               class="form-control input-md" required="">
                        <input name="cid" value="<?php echo $cid; ?>" type="hidden">
                        <input name="section" value="<?php echo $sectionNumber; ?>" type="hidden">
                    </div>
                </div>

                <!-- Textarea -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="linkaddress">Link address</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="linkaddress" name="linkaddress"></input>
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group">
                    <label class="col-md-4 control-label" for="singlebutton"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
    </body>
    </html>

    <?php
}
if (!isset($_GET['rtype']) && !isset($_POST['vname'])) {
    header("location:404.html");
}