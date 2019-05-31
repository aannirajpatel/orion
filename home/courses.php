<?php
require('../includes/db.php');
if(isset($_GET['q'])){
    $searchText = mysqli_real_escape_string($con, $_GET['q']);
    $searchText = strtolower($searchText);
    $searchText = preg_replace('/[^a-z0-9 -]+/', '', $searchText);
    $searchText = str_replace(' ', '-', $searchText);
    $searchText = trim($searchText, '-');
    $searchText = explode("-",$searchText);
    $searchTextWordCount = count($searchText);
    $searchQuery = "";
    if($searchTextWordCount<1){
        echo "<div class='alert alert-dismissible alert-warning'>Invalid Search Query</div>";
    }
    else {
        $searchQuery = "SELECT * FROM course INNER JOIN csyllabus ON (course.cid=csyllabus.cid AND published=1) WHERE ";
        foreach ($searchText as $searchWord) {
            $searchQuery .= "LOWER(cname) LIKE ('%$searchWord%') OR LOWER(cdesc) LIKE('%$searchWord%') OR LOWER(csyllabus) LIKE('%$searchWord%')";
            $searchTextWordCount--;
            if ($searchTextWordCount > 0) {
                $searchQuery .= " OR ";
            }
        }
        $searchQueryResult = mysqli_query($con, $searchQuery) or die($con);
        //Uncomment the line below for DEBUG Purposes
        //echo $searchQuery;
    }
} else{
    $searchQuery = "SELECT course.cid AS courseid, category, cname, cdesc, cost, cimg, count(cstudents.uid) AS enrolls FROM course INNER JOIN cstudents ON cstudents.cid=course.cid GROUP BY course.cid ORDER BY count(cstudents.uid) DESC";
    $searchQueryResult = mysqli_query($con, $searchQuery) or die($con);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Udema a modern educational site template">
    <meta name="author" content="Ansonika">
    <title>Courses | Orion E-Learning</title>

    <!-- Favicons-->
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<link href="css/vendors.css" rel="stylesheet">
	<link href="css/icon_fonts/css/all_icons.min.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">

</head>

<body>
	
	<div id="page">
		
	<?php require 'homeNav.php'; ?>
	<!-- /header -->
	
	<main>
		<section id="hero_in" class="courses">
			<div class="wrapper">
				<div class="container">
					<h1 class="fadeInUp"><span></span>Online courses</h1>
                    <?php
                    if(isset($_GET['q'])){
                        echo "<h1 class='fadeInUp'>RELATED TO <br>'".$_GET['q']."'</h1>";
                    }
                    ?>
				</div>
			</div>
		</section>
		<!--/hero_in-->

		<!-- /filters -->

		<div class="container margin_60_35">
			<div class="row" id="courseResultContainer">
                <?php
                while($courseData = mysqli_fetch_array($searchQueryResult)) {
                    if(isset($courseData['courseid'])){
                        $cid = $courseData['courseid'];
                        $enrolls = $courseData['enrolls'];
                    } else{
                        $cid = $courseData['cid'];
                        $enrollsQuery = "SELECT count(*) AS enrolls FROM cstudents WHERE cid=$cid";
                        $enrollsResult = mysqli_query($con, $enrollsQuery) or die(mysqli_error($con));
                        $enrollsData = mysqli_fetch_array($enrollsResult);
                        $enrolls = $enrollsData['enrolls'];
                    }
                    $cname = $courseData['cname'];
                    $cost = $courseData['cost'];
                    $cimg = $courseData['cimg'];
                    $cdesc = $courseData['cdesc'];
                    $category = $courseData['category'];
                    $totalReviewsQuery = "SELECT count(*) as totalreviews, avg(rating) AS avgrating FROM creviews WHERE cid=$cid";
                    $totalReviewsResult = mysqli_query($con, $totalReviewsQuery) or die(mysqli_error($con));
                    $totalReviewsData = mysqli_fetch_array($totalReviewsResult);
                    $totalReviews = $totalReviewsData['totalreviews'];
                    $avgRating = round($totalReviewsData['avgrating']);
                    ?>
                    <div class="courseCard col-xl-4 col-lg-6 col-md-6">
                        <div class="box_grid wow">
                            <figure class="block-reveal">
                                <div class="block-horizzontal"></div>
                                <a href="#0" class="wish_bt"></a>
                                <a href="../dashboard/viewcourse.php?cid=<?php echo $cid;?>"><img src="<?php echo $cimg; ?>" class="img-fluid"
                                                                  alt=""></a>
                                <div class="price">₹<?php echo $cost;?></div>
                                <div class="preview"><span>View course</span></div>
                            </figure>
                            <div class="wrapper">
                                <small><?php echo $category;?></small>
                                <h3><?php echo $cname;?></h3>
                                <p><?php echo $cdesc;?></p>
                                <div class="rating">
                                    <?php
                                    $temp = $avgRating;
                                    while ($temp > 0) {
                                        ?>
                                        <i class="icon_star voted"></i>
                                        <?php
                                        $temp--;
                                    }
                                    $temp = 5 - $avgRating;
                                    while ($temp > 0) {
                                        ?>
                                        <i class="icon_star"></i>
                                        <?php
                                        $temp--;
                                    }
                                    ?>
                                    <small>(<?php echo $totalReviews; ?> Reviewers)</small>
                                </div>
                            </div>
                            <ul>
                                <!--<li><i class="icon_clock_alt"></i> 1h 30min</li>-->
                                <li><i class="icon_profile"></i>&nbsp;<?php echo $enrolls; ?></li>
                                <li><a href="../dashboard/viewcourse.php?cid=<?php echo $cid; ?>">View</a></li>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
                ?>
				<!-- /box_grid -->
			</div>
			<!-- /row -->
			<p class="text-center"><a href="#0" class="btn_1 rounded add_top_30" onclick="showAllResults()" id="loadMoreBtn">Load more</a></p>
		</div>
		<!-- /container -->
		<div class="bg_color_1">
			<div class="container margin_60_35">
				<div class="row">
					<div class="col-md-4">
						<a href="contact.php" class="boxed_list">
							<i class="pe-7s-help2"></i>
							<h4>Need Help? Contact us</h4>
							<p>We'll try our best to solve your problems.</p>
						</a>
					</div>
					<div class="col-md-4">
						<a href="contact.php" class="boxed_list">
							<i class="pe-7s-wallet"></i>
							<h4>Payments and Refunds</h4>
							<p>No problem! just drop an e-mail to support or visit the contact page.</p>
						</a>
					</div>
					<div class="col-md-4">
						<a href="contact.php" class="boxed_list">
							<i class="pe-7s-note2"></i>
							<h4>Quality Standards</h4>
							<p>Report a quality issue or copyright violation. We will respond at the earliest.</p>
						</a>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /bg_color_1 -->
	</main>
	<!--/main-->
	
	<?php require 'homeFooter.php';?>
	<!--/footer-->
	</div>
	<!-- page -->
	
	<!-- COMMON SCRIPTS -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="js/common_scripts.js"></script>
    <script src="js/main.js"></script>
	<script src="assets/validate.js"></script>
    <script>
       $(document).ready(function(){
           $('.courseCard').hide();
           for(var restrictionInt = 1; restrictionInt<=6; restrictionInt++) {
               var cardToShow = '#courseResultContainer :nth-child(' + restrictionInt.toString() + ')';
               if($(cardToShow).length) {
                   $(cardToShow).show();
               }
           }
       });
       function showAllResults(){
           $('#loadMoreBtn').hide();
           $('.courseCard').show();
       }
    </script>
</body>
</html>