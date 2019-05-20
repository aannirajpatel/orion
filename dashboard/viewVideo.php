<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 17/5/19 2:43 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Video</title>
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
        if (!isResource($con, $rid, RES_VIDEO)) {
            die("Warning: Wrong request issued - resource type mismatch.");
        }
        $getResQuery = "SELECT rtext, rdate, raddr FROM cresources WHERE rid=$rid";
        $getResResult = mysqli_query($con, $getResQuery) or die($getResQuery);
        if (mysqli_num_rows($getResResult) > 0) {
            $getResData = mysqli_fetch_array($getResResult);
            echo "<h3>Video: " . $getResData['rtext'] . "</h3><hr>";
            $raddr = $getResData['raddr'];
            $addressArray = preg_split("/res/",$raddr);
            $vaddr = "./res".$addressArray[1];
            ?>

            <video width="320" height="240" controls>
                <source src="<?php echo $vaddr;?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
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
