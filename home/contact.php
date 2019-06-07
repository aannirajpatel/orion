<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Orion E-Learning, an amazing E-Learning platform">
    <meta name="author" content="Orion Publications">
    <title>Contact Us | Orion E-Learning</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114"
          href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144"
          href="img/apple-touch-icon-144x144-precomposed.png">

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

    <?php require('homeNav.php'); ?>
    <!-- /header -->

    <main>
        <section id="hero_in" class="contacts">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>Contact Orion E-Learning</h1>
                </div>
            </div>
        </section>
        <!--/hero_in-->

        <div class="contact_info">
            <div class="container">
                <ul class="clearfix">
                    <li>
                        <i class="pe-7s-map-marker"></i>
                        <h4>Address</h4>
                        <span>Orion Publication, C-505, Manubhai Towers, Sayajigunj<br>Vadodara, Gujarat, India</span>
                    </li>
                    <li>
                        <i class="pe-7s-mail-open-file"></i>
                        <h4>Email address</h4>
                        <span>info@orionpublications.com<br><small>Monday to Friday 9am - 7pm</small></span>

                    </li>
                    <li>
                        <i class="pe-7s-phone"></i>
                        <h4>Contacts info</h4>
                        <span>+ 91 7202998877<br><small>Monday to Friday 9am - 7pm</small></span>
                    </li>
                </ul>
            </div>
        </div>
        <!--/contact_info-->

        <div class="bg_color_1">
            <div class="container margin_120_95">
                <div class="row justify-content-between">
                    <div class="col-lg-5">
                        <div class="map_contact">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.1922921474347!2d73.18407024989033!3d22.308566248161636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fcf8817c97477%3A0xb4269cecb92f8584!2sGlobal+BizConnect!5e0!3m2!1sen!2sin!4v1550908310980" width="750" height="450" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                        </div>
                        <!-- /map -->
                    </div>
                    <div class="col-lg-6">
                        <h4>Send a message</h4>
                        <p>We will try to respond as quickly as possible</p>
                        <div id="message-contact"></div>
                        <form method="post" action="../login/contactEmail.php" id="contactform" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
									<span class="input">
										<input class="input_field" type="text" id="name_contact" name="name_contact">
										<label class="input_label">
											<span class="input__label-content">Your Name</span>
										</label>
									</span>
                                </div>
                                <div class="col-md-6">
									<span class="input">
										<input class="input_field" type="text" id="lastname_contact"
                                               name="lastname_contact">
										<label class="input_label">
											<span class="input__label-content">Last name</span>
										</label>
									</span>
                                </div>
                            </div>
                            <!-- /row -->
                            <div class="row">
                                <div class="col-md-6">
									<span class="input">
										<input class="input_field" type="email" id="email_contact" name="email_contact">
										<label class="input_label">
											<span class="input__label-content">Your email</span>
										</label>
									</span>
                                </div>
                                <div class="col-md-6">
									<span class="input">
										<input class="input_field" type="text" id="phone_contact" name="phone_contact">
										<label class="input_label">
											<span class="input__label-content">Your telephone</span>
										</label>
									</span>
                                </div>
                            </div>
                            <!-- /row -->
                            <span class="input">
									<textarea class="input_field" id="message_contact" name="message_contact"
                                              style="height:150px;"></textarea>
									<label class="input_label">
										<span class="input__label-content">Your message</span>
									</label>
							</span>
                            <span class="input">
									<input class="input_field" type="text" id="verify_contact" name="verify_contact">
									<label class="input_label">
									<span class="input__label-content">Are you human? 3 + 1 =</span>
									</label>
							</span>
                            <p class="add_top_30"><input type="submit" value="Submit" class="btn_1 rounded" onclick="$('#contactform').hide()"></p>
                        </form>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->
    </main>
    <!--/main-->

    <?php require 'homeFooter.php'; ?>
    <!--/footer-->
</div>
<!-- page -->

<!-- COMMON SCRIPTS -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts.js"></script>
<script src="js/main.js"></script>
<script src="assets/validate.js"></script>

<!-- SPECIFIC SCRIPTS -->
<script type="text/javascript" src="js/mapmarker.jquery.js"></script>
<script type="text/javascript" src="js/mapmarker_func.jquery.js"></script>
</body>
</html>