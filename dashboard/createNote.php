<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 17/5/19 1:28 PM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_POST['title'])) {

    $cid = $_POST['cid'];

    if(!isThisUsersCourse($con,$cid)){
        header("location:404.php");
    }

    $sectionNumber = $_POST['section'];
    $title = $_POST['title'];
    $note = $_POST['note'];
    $rtype = RES_NOTE;
    $insertNoteQuery = "INSERT INTO cresources(cid,`section`,rtype,rtext,rdata) VALUES ($cid,$sectionNumber,$rtype,'$title','".mysqli_real_escape_string($con,$note)."')";
    $insertVideoResult = mysqli_query($con, $insertNoteQuery) or die(mysqli_error($con));
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Add a Note</title>
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
        <h3>Note Successfully Added!</h3>
        <hr>
        <a class="btn btn-success" href='coursedit.php?<?php echo "cid=$cid";?>'>Go back to course editor</a>
        <br><br>
        <a class="btn btn-success" href='createresource.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>'>Add another resource to
            selected section.</a>
        <br><br>
        <a class="btn btn-success" href='addResourceNote.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>'>Add another note to
            selected section.</a>
    </div>
    <?php require('rotateScreen.php');?>
    </body>
    </html>
    <?php
} else {
    die("not ok");
}
?>