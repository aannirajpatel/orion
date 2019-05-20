<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 18/5/19 3:03 PM.
 */

require('../includes/auth.php');
require('../includes/db.php');
require('../includes/courseownershipauth.php');
require('../includes/resconfig.php');

if (isset($_GET['cid']) && isset($_GET['section'])) {
    $cid=$_GET['cid'];
    $sectionNumber = $_GET['section'];
    if(!isThisUsersCourse($con, $cid)){
        header("location:404.html");
    }
    $getSectionNameQuery = "SELECT sname,sdesc FROM csections WHERE section=$sectionNumber AND cid=$cid";
    $getSectionNameResult = mysqli_query($con, $getSectionNameQuery) or die(mysqli_error($con));
    $sname = "";
    if(mysqli_num_rows($getSectionNameResult)>0){
        $getSectionNameData = mysqli_fetch_array($getSectionNameResult);
        $sname = $getSectionNameData['sname'];
        $sdesc = $getSectionNameData['sdesc'];
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Section</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

        <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
        <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

    </head>
    <body>
    <!--Section Create Form-->
    <div class="container">
        <div class="row">
            <form class="form-horizontal" action="" method="post">
                <fieldset>
                    <legend>Edit Section</legend>
                    <!-- Section name input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="sname">Section Name</label>
                        <div class="col-md-8">
                            <input id="sname" name="sname" type="text" value="<?php echo $sname;?>" placeholder="Give a name to the section"
                                   class="form-control input-md" required="">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="section" value="<?php echo $sectionNumber; ?>">
                        </div>
                    </div>

                    <!-- Section description input -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="sdesc">Section Description</label>
                        <div class="col-md-4">
                            <textarea class="form-control" id="sdesc" name="sdesc">
                                <?php echo $sdesc;?>
                            </textarea>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for=""></label>
                        <div class="col-md-4">
                            <button type="submit" id="" name="" class="btn btn-success">Confirm</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    </body>
    </html>
    <?php
}

if(isset($_POST['section'])&&isset($_POST['cid'])){
    $cid = $_POST['cid'];
    $sectionNumber = $_POST['section'];
    $sname = mysqli_real_escape_string($con,$_POST['sname']);
    $sdesc = mysqli_real_escape_string($con,$_POST['sdesc']);
    $createSectionQuery = mysqli_query($con, "UPDATE csections SET sname='$sname', sdesc='$sdesc' WHERE cid=$cid AND section=$sectionNumber");
    header("location:coursedit.php?cid=$cid");
}

if(!isset($_GET['cid']) && !isset($_POST['sname'])) {
    header('404.html');
}

?>