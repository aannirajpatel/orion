<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 3:17 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');

if (isset($_GET['cid']) && isset($_GET['section'])) {
    $cid = $_GET['cid'];
    $sectionNumber = $_GET['section'];
    if(!isThisUsersCourse($con, $cid)){
        header("location:404.html");
    }
} else {
    header("location:404.html");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Resource Type</title>
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
    <div class="list-group">
        <a href="addResourceNote.php?rtype=0&cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
           class="list-group-item">Add Note</a>
        <a href="addResourceVideo.php?rtype=1&cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
           class="list-group-item">Add Video</a>
        <a href="addResourceFile.php?rtype=2&cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
           class="list-group-item">Add Other File</a>
        <a href="addResourceYoutubeLink.php?rtype=3&cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
           class="list-group-item">Add YouTube Link</a>
        <a href="addResourceLink.php?rtype=4&cid=<?php echo $cid; ?>&section=<?php echo $sectionNumber; ?>"
           class="list-group-item">Add Other Link</a>
    </div>
    <a class="btn btn-primary" href="coursedit.php?<?php echo "cid=$cid";?>">Cancel</a>
</div>
</body>
</html>