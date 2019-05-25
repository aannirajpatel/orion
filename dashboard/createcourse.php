<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 15/5/19 2:54 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');
if (isset($_POST['cname'])) {
    $cname = $_POST['cname'];
    $cdesc = $_POST['cdesc'];
    $cdesc = mysqli_real_escape_string($con, $cdesc);
    $uid = $_SESSION['uid'];
    $cost = $_POST['cost'];

    $startTransactionResult = mysqli_query($con, "START TRANSACTION") or die(mysqli_error($con));

    $courseCreateQuery = "INSERT INTO course(cname,cdesc,creatoruid,cost) VALUES ('$cname','$cdesc',$uid,$cost)";
    $courseCreateResult = mysqli_query($con, $courseCreateQuery) or die(mysqli_error($con));

    $cidQuery = "SELECT MAX(cid) AS cidno FROM course";
    $cidResult = mysqli_query($con, $cidQuery);
    $cidData = mysqli_fetch_array($cidResult);
    $commitTransactionResult = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
    $cid = $cidData['cidno'];

    $courseTrainerDesignateQuery = "INSERT INTO ctrainers(cid,uid) VALUES ($cid,$uid)";
    $courseTrainerDesignateResult = mysqli_query($con, $courseTrainerDesignateQuery) or die(mysqli_error($con));

    $emptySyllabusCreateQuery = "INSERT INTO csyllabus(cid, csyllabus) VALUES($cid,'')";
    $emptySyllabusCreateResult = mysqli_query($con, $emptySyllabusCreateQuery) or  die(mysqli_error($con));

    header("location:coursedit.php?cid=$cid");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create a course</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

</head>
<body>
<div class="container">
    <form class="form-horizontal" method="post" action="">
        <fieldset>
            <!-- Form Name -->
            <legend>Create Your Course</legend>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="cname">Course Name</label>
                <div class="col-md-6">
                    <input id="cname" name="cname" type="text" placeholder="Enter the name of your course"
                           class="form-control input-md" required="">
                </div>
            </div>
            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="cdesc">Course Description</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="cdesc" name="cdesc"></textarea>
                </div>
            </div>

            <!-- Prepended text-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="cost">Course Price</label>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-addon">₹</span>
                        <input id="prependedtext" name="cost" class="form-control" placeholder="Enter amount"
                               type="number" required="">
                    </div>
                </div>
            </div>
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for=""></label>
                        <div class="col-md-4">
                            <button type="submit" id="" name="createcourse" class="btn btn-primary">Go!</button>
                        </div>
                    </div>
        </fieldset>
    </form>
</div>
</body>
</html>