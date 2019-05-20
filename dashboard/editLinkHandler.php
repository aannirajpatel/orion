<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 3:58 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_POST['title'])) {
    $rid = $_POST['rid'];

    if(!authToEditResource($con,$rid) || !isResource($con, $rid, RES_LINK)){
        header("location:404.html");
    }

    $title = $_POST['title'];
    $linkaddress = $_POST['linkaddress'];

    $insertLinkQuery = "UPDATE cresources SET rtext='$title', raddr='".mysqli_real_escape_string($con,$linkaddress)."' WHERE rid=$rid";
    $insertLinkResult = mysqli_query($con, $insertLinkQuery) or die(mysqli_error($con));
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Link</title>
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
        <h3>Link Successfully Updated!</h3>
        <hr>
        <a class="btn btn-success" href='coursedit.php?<?php echo "cid=".getCidFromRid($con, $rid);?>'>Go back to course editor</a>
    </div>
    </body>
    </html>
    <?php
} else {
    die("not ok");
}
?>