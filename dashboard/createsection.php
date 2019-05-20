<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 16/5/19 12:51 PM.
 */
require('../includes/auth.php');
require('../includes/db.php');

if (isset($_GET['cid'])) {
    $cid=$_GET['cid'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit your course</title>
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
                    <!-- Form Name -->
                    <legend>Create a section</legend>

                    <!-- Section name input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="sname">Section Name</label>
                        <div class="col-md-8">
                            <input id="sname" name="sname" type="text" placeholder="Give a name to the section"
                                   class="form-control input-md" required="">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                        </div>
                    </div>

                    <!-- Section description input -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="sdesc">Section Description</label>
                        <div class="col-md-4">
                            <textarea class="form-control" id="sdesc" name="sdesc"></textarea>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for=""></label>
                        <div class="col-md-4">
                            <button type="submit" id="" name="" class="btn btn-success">Create Section</button>
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
if(isset($_POST['sname'])&&isset($_POST['sdesc'])&&isset($_POST['cid'])){
    $cid = $_POST['cid'];
    $startTransactionExecuteQuery = mysqli_query($con,"START TRANSACTION") or die(mysqli_error($con));
    $getSectionNumberResult= mysqli_query($con, "SELECT MAX(`section`) as sno FROM csections WHERE cid=$cid");
    $sectionNumber = 1;
    if(mysqli_num_rows($getSectionNumberResult)!=0) {
        $getSectionNumberData = mysqli_fetch_array($getSectionNumberResult);
        $sectionNumber = $getSectionNumberData['sno']+1;
    }
    $sname = $_POST['sname'];
    $sdesc = mysqli_real_escape_string($con,$_POST['sdesc']);
    $createSectionQuery = mysqli_query($con, "INSERT INTO csections(cid, `section`, sname, sdesc) VALUES($cid,$sectionNumber,'$sname','$sdesc')");
    $commitTransactionExecuteQuery = mysqli_query($con, "COMMIT") or die(mysqli_error($con));
    echo "Section created.";
    header("location:coursedit.php?cid=$cid");
} if(!isset($_GET['cid'])&&!isset($_POST['sname'])) {
    header('404.html');
}

?>