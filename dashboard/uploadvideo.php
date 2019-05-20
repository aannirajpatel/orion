<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 5:39 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');

if (isset($_POST['vname'])) {
    $cid = $_POST['cid'];
    if(!isThisUsersCourse($con,$cid)) {
        header("location:404.html");
    }
    $sectionNumber = $_POST['section'];
    $rtype = $_POST['rtype'];
    $vname = $_POST['vname'];
    if ($_FILES['vupload']['error'] != 0 || !isThisUsersCourse($con, $cid)) {
        echo "<br>" . $_FILES['vupload']['error'] . " " . isThisUsersCourse($con, $cid) . "<br>";
        echo "Error uploading. Please try adding the video again. Click <a href='addResourceVideo.php?cid=$cid&rtype=$rtype&section=$sectionNumber'> here to go back.</a>";
    }

    $startTransactionExecuteQuery = mysqli_query($con, "START TRANSACTION") or die(mysqli_error($con));
    $getLatestRIDQueryResult = mysqli_query($con, "SELECT MAX(rid) as maxrid FROM cresources") or die(mysqli_error($con));
    $getLatesrRIDQueryData = mysqli_fetch_array($getLatestRIDQueryResult);
    $rid = $getLatesrRIDQueryData['maxrid'] + 1;

    $extensionSplitter = preg_split("/\./", basename($_FILES['vupload']['name']), 2);
    if (count($extensionSplitter) == 2) {
        if ($vname == "") {
            $vname = $extensionSplitter[0];
        }
        $extension = $extensionSplitter[1];
    } else {
        if ($vname == "") {
            $vname = basename($_FILES['vupload']['name']);
        }
        $extension = "mp4";
    }
    $vaddr = "./res/$cid/";
    if (!is_dir($vaddr)) {
        mkdir($vaddr, 0755, true);
    }
    $vaddr = $vaddr . "$sectionNumber-$rid." . $extension;
//echo $vaddr;
    move_uploaded_file($_FILES['vupload']['tmp_name'], $vaddr) or die("Error uploading. Please try adding the video again. Click <a href='addResourceVideo.php?cid=$cid&rtype=$rtype&section=$sectionNumber'> here to go back.");

    $insertVideoQuery = "INSERT INTO cresources(cid,`section`,rtype,rtext,raddr) VALUES ($cid,$sectionNumber,$rtype,'$vname','$vaddr')";
    $insertVideoResult = mysqli_query($con, $insertVideoQuery) or die(mysqli_error($con));
    $commitExecuteQuery = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Upload Video</title>
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
        <h3>Video Successfully Added!</h3>
        <hr>
        <a class="btn btn-success" href='coursedit.php?<?php echo "cid=$cid";?>'>Go back to course editor</a>
        <br>
        <a class="btn btn-success" href='createresource.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>'>Add another resource to
        selected section.</a>
        <br>
        <a class="btn btn-success" href='addResourceVideo.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>'>Add another video to
        selected section.</a>
    </div>
    </body>
    </html>
    <?php
} else {
    die("not ok");
}
?>

