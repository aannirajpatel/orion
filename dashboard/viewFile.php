<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 11:05 AM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>View File</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <?php
    if (isset($_GET['rid'])) {
        $rid = $_GET['rid'];
        if (!authToViewResource($con, $rid)) {
            die("You don't have authorization for viewing this resource. Please contact admin.");
        }
        if (!isResource($con, $rid, RES_FILE)) {
            die("Warning: Wrong request issued - resource type mismatch.");
        }
        $getResQuery = "SELECT rtext, rdate, raddr FROM cresources WHERE rid=$rid";
        $getResResult = mysqli_query($con, $getResQuery) or die($getResQuery);
        if (mysqli_num_rows($getResResult) > 0) {
            $getResData = mysqli_fetch_array($getResResult);
            echo "<h3>File: " . $getResData['rtext'] . "</h3><hr>";
            $raddr = $getResData['raddr'];
            $addressArray = preg_split("/res/",$raddr);
            $faddr = "./res".$addressArray[1];
            ?>
            <a href="<?php echo $faddr;?>" class="btn btn-primary" target="_blank" download="<?php echo $getResData['rtext'];?>">Get File</a>
            <?php
        } else {
            echo "(This note is empty.)";
        }
    } else {
        header("location:404.html");
    }
    ?>
</div>
</body>
</html>
