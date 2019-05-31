<?php
require('../includes/db.php');
$courseQuery = "SELECT course.cid AS courseid, category, cname, cdesc, cost, cimg, count(cstudents.uid) AS enrolls FROM course INNER JOIN cstudents ON cstudents.cid=course.cid GROUP BY course.cid ORDER BY count(cstudents.uid) LIMIT 6";
$courseResult = mysqli_query($con, $courseQuery) or die(mysqli_error($con));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Orion E-Learning, an amazing E-Learning platform">
    <meta name="author" content="Orion Publications">
    <title>Home | Orion E-Learning</title>

    <!-- Favicons-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

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
        <section class="hero_single">
            <div class="wrapper">
                <div class="container">
                    <h3><strong>Top courses</strong><br>on Orion E-Learning</h3>
                    <p>Be it science, engineering or technology, you will find <strong>everything</strong> you need to learn, here at Orion.</p>
                </div>
                <a href="#first_section" class="btn_explore hidden_tablet"><i class="ti-arrow-down"></i></a>
            </div>
        </section>
        <!-- /hero_single -->

        <div class="features clearfix">
            <div class="container">
                <ul>
                    <li><i class="pe-7s-study"></i>
                        <h4>200+ courses</h4><span>Explore a variety of fresh topics</span>
                    </li>
                    <li><i class="pe-7s-cup"></i>
                        <h4>Expert teachers</h4><span>Find the right instructor for you</span>
                    </li>
                    <li><i class="pe-7s-target"></i>
                        <h4>Focus on target</h4><span>Increase your personal expertise</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /features -->

        <div class="container-fluid margin_120_0" id="first_section">
            <div class="main_title_2">
                <span><em></em></span>
                <h2>Popular Courses</h2>
                <p>Most sought-after courses on Orion E-Learning</p>
            </div>
            <div id="reccomended" class="owl-carousel owl-theme">
                <?php
                while($courseData = mysqli_fetch_array($courseResult)) {
                    $cid = $courseData['courseid'];
                    $cname = $courseData['cname'];
                    $cost = $courseData['cost'];
                    $cimg = $courseData['cimg'];
                    $cdesc = $courseData['cdesc'];
                    $enrolls = $courseData['enrolls'];
                    $category = $courseData['category'];
                    $totalReviewsQuery = "SELECT count(*) as totalreviews FROM creviews WHERE cid=$cid";
                    $totalReviewsResult = mysqli_query($con, $totalReviewsQuery) or die(mysqli_error($con));
                    $totalReviewsData = mysqli_fetch_array($totalReviewsResult);
                    $totalReviews = $totalReviewsData['totalreviews'];
                    ?>
                    <div class="item">
                        <div class="box_grid">
                            <figure>
                                <!--<a href="#0" class="wish_bt"></a>-->
                                <a href="../dashboard/viewcourse.php?cid=<?php echo $cid; ?>"><img
                                            src="<?php echo $cimg;?>"
                                            class="img-fluid" alt=""></a>
                                <div class="price"><?php echo $cost;?></div>
                                <div class="preview"><span>View course</span></div>
                            </figure>
                            <div class="wrapper">
                                <small><?php echo $category;?></small>
                                <h3><?php echo $cname;?></h3>
                                <p><?php echo $cdesc;?></p>
                                <div class="rating"><i class="icon_star voted"></i><i class="icon_star voted"></i><i
                                            class="icon_star voted"></i><i class="icon_star"></i><i
                                            class="icon_star"></i>
                                    <small><?php echo $totalReviews;?></small>
                                </div>
                            </div>
                            <ul>
                                <!--<li><i class="icon_clock_alt"></i> 1h 30min</li>-->
                                <li><i class="icon_profile"></i><?php echo $enrolls;?></li>
                                <li><a href="../dashboard/viewcourse.php?cid=<?php echo $cid;?>">View</a></li>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- /carousel -->
            <div class="container">
                <p class="btn_home_align"><a href="courses-grid.html" class="btn_1 rounded">View all courses</a></p>
            </div>
            <!-- /container -->
            <hr>
        </div>
        <!-- /container -->

        <div class="container margin_30_95">
            <div class="main_title_2">
                <span><em></em></span>
                <h2>Course Categories</h2>
                <p>Pick your passion.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 wow" data-wow-offset="150">
                    <a href="#0" class="grid_item">
                        <figure class="block-reveal">
                            <div class="block-horizzontal"></div>
                            <img src="http://via.placeholder.com/800x533/ccc/fff/course_1.jpg" class="img-fluid" alt="">
                            <div class="info">
                                <small><i class="ti-layers"></i>15 Programmes</small>
                                <h3>Pure Sciences</h3>
                            </div>
                        </figure>
                    </a>
                </div>
                <!-- /grid_item -->
                <div class="col-lg-4 col-md-6 wow" data-wow-offset="150">
                    <a href="#0" class="grid_item">
                        <figure class="block-reveal">
                            <div class="block-horizzontal"></div>
                            <img src="http://via.placeholder.com/800x533/ccc/fff/course_2.jpg" class="img-fluid" alt="">
                            <div class="info">
                                <small><i class="ti-layers"></i>23 Programmes</small>
                                <h3>Mechanical Engineering</h3>
                            </div>
                        </figure>
                    </a>
                </div>
                <!-- /grid_item -->
                <div class="col-lg-4 col-md-6 wow" data-wow-offset="150">
                    <a href="#0" class="grid_item">
                        <figure class="block-reveal">
                            <div class="block-horizzontal"></div>
                            <img src="http://via.placeholder.com/800x533/ccc/fff/course_3.jpg" class="img-fluid" alt="">
                            <div class="info">
                                <small><i class="ti-layers"></i>23 Programmes</small>
                                <h3>Web Development</h3>
                            </div>
                        </figure>
                    </a>
                </div>
                <!-- /grid_item -->
                <div class="col-lg-4 col-md-6 wow" data-wow-offset="150">
                    <a href="#0" class="grid_item">
                        <figure class="block-reveal">
                            <div class="block-horizzontal"></div>
                            <img src="http://via.placeholder.com/800x533/ccc/fff/course_4.jpg" class="img-fluid" alt="">
                            <div class="info">
                                <small><i class="ti-layers"></i>23 Programmes</small>
                                <h3>Android Development</h3>
                            </div>
                        </figure>
                    </a>
                </div>
                <!-- /grid_item -->
                <div class="col-lg-4 col-md-6 wow" data-wow-offset="150">
                    <a href="#0" class="grid_item">
                        <figure class="block-reveal">
                            <div class="block-horizzontal"></div>
                            <img src="http://via.placeholder.com/800x533/ccc/fff/course_5.jpg" class="img-fluid" alt="">
                            <div class="info">
                                <small><i class="ti-layers"></i>23 Programmes</small>
                                <h3>iOS Development</h3>
                            </div>
                        </figure>
                    </a>
                </div>
                <!-- /grid_item -->
                <div class="col-lg-4 col-md-6 wow" data-wow-offset="150">
                    <a href="#0" class="grid_item">
                        <figure class="block-reveal">
                            <div class="block-horizzontal"></div>
                            <img src="http://via.placeholder.com/800x533/ccc/fff/course_6.jpg" class="img-fluid" alt="">
                            <div class="info">
                                <small><i class="ti-layers"></i>23 Programmes</small>
                                <h3>Desktop App Development</h3>
                            </div>
                        </figure>
                    </a>
                </div>
                <!-- /grid_item -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->


        <!-- /bg_color_1 -->

        <div class="call_section">
            <div class="container clearfix">
                <div class="col-lg-5 col-md-6 float-right wow" data-wow-offset="250">
                    <div class="block-reveal">
                        <div class="block-vertical"></div>
                        <div class="box_1">
                            <h3>Enjoy a great student community</h3>
                            <p>Every course is equipped with its own QnA forum so you can get all your doubts cleared and help the course community grow.</p>
                            <!--<a href="#0" class="btn_1 rounded">Read more</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/call_section-->
    </main>
    <!-- /main -->

    <?php require 'homeFooter.php';?>
    <!--/footer-->
</div>
<!-- page -->

<!-- COMMON SCRIPTS -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts.js"></script>
<script src="js/main.js"></script>
<script src="assets/validate.js"></script>

</body>
</html>