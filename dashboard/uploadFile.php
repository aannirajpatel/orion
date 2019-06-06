<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 10:57 AM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');

if (isset($_POST['fname'])) {
    $cid = $_POST['cid'];
    if(!isThisUsersCourse($con,$cid)) {
        header("location:404.php");
    }
    $sectionNumber = $_POST['section'];
    $rtype = $_POST['rtype'];
    $fname = $_POST['fname'];
    if ($_FILES['fupload']['error'] != 0 || !isThisUsersCourse($con, $cid)) {
        echo "<br>" . $_FILES['fupload']['error'] . " " . isThisUsersCourse($con, $cid) . "<br>";
        echo "Error uploading. Please try adding the video again. Click <a href='addResourceVideo.php?cid=$cid&rtype=$rtype&section=$sectionNumber'> here to go back.</a>";
    }

    $startTransactionExecuteQuery = mysqli_query($con, "START TRANSACTION") or die(mysqli_error($con));
    $getLatestRIDQueryResult = mysqli_query($con, "SELECT MAX(rid) as maxrid FROM cresources") or die(mysqli_error($con));
    $getLatesrRIDQueryData = mysqli_fetch_array($getLatestRIDQueryResult);
    $rid = $getLatesrRIDQueryData['maxrid'] + 1;

    $extensionSplitter = preg_split("/\./", basename($_FILES['fupload']['name']), 2);
    if (count($extensionSplitter) == 2) {
        if ($fname == "") {
            $fname = basename($_FILES['fupload']['name']);
        }
        $extension = $extensionSplitter[1];
    } else {
        if ($fname == "") {
            $fname = basename($_FILES['fupload']['name']);
        }
        $extension = "pdf";
    }
    $faddr = "./res/$cid/";
    if (!is_dir($faddr)) {
        mkdir($faddr, 0755, true);
    }
    $faddr = $faddr . "$sectionNumber-$rid." . $extension;
//echo $faddr;
    move_uploaded_file($_FILES['fupload']['tmp_name'], $faddr) or die("Error uploading. Please try adding the File again. Click <a href='addResourceFile.php?cid=$cid&rtype=$rtype&section=$sectionNumber'> here to go back.");

    $insertFileQuery = "INSERT INTO cresources(cid,`section`,rtype,rtext,raddr) VALUES ($cid,$sectionNumber,$rtype,'$fname','$faddr')";
    $insertFileResult = mysqli_query($con, $insertFileQuery) or die(mysqli_error($con));
    $commitExecuteQuery = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Upload File</title>
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
        <h3>File Successfully Added!</h3>
        <hr>
        <a class="btn btn-success" href='coursedit.php?<?php echo "cid=$cid";?>'>Go back to course editor</a>
        <br><br>
        <a class="btn btn-success" href='createresource.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>'>Add another resource to
        selected section.</a>
        <br><br>
        <a class="btn btn-success" href='addResourceFile.php?<?php echo "cid=$cid&section=$sectionNumber&rtype=$rtype";?>'>Add another file to
        selected section.</a>
    </div>
    </body>
    </html>
    <?php
} else {
    die("not ok");
}
?>



