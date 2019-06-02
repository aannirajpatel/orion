<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 31/5/19 10:49 AM.
 */
if (!isset($_SESSION['email'])) {
    echo "<header class=\"header menu_2\">
        <div id=\"preloader\">
            <div data-loader=\"circle-side\"></div>
        </div><!-- /Preload -->
        <div id=\"logo\">
            <a href=\"index.html\"><img src=\"img/logo.png\" width=\"149\" height=\"42\" data-retina=\"true\" alt=\"\"></a>
        </div>
        <!-- /top_menu -->
        <a href=\"#menu\" class=\"btn_mobile\">
            <div class=\"hamburger hamburger--spin\" id=\"hamburger\">
                <div class=\"hamburger-box\">
                    <div class=\"hamburger-inner\"></div>
                </div>
            </div>
        </a>
        <nav id=\"menu\" class=\"main-menu\">
            <ul>
                <li>
                    <span><a href=\"../index.php\">Home</a></span>
                </li>
                <li>
                    <span><a href=\"#0\" class=\"search-overlay-menu-btn\">Search</a></span>
                </li>

                <li><span><a href=\"../home/about.php\">About</a></span></li>
                <li><span><a href=\"../home/contact.php\">Contact Us</a></span></li>
                              
                
                <li><span><a href=\"../login/\">Login</a></span></li>
                <li><span><a href=\"../login/register.php\">Register</a></span></li>
            </ul>
        </nav>
        <!-- Search Menu -->
        <div class=\"search-overlay-menu\">
            <span class=\"search-overlay-close\"><span class=\"closebt\"><i class=\"ti-close\"></i></span></span>
            <form role=\"search\" action='../home/courses.php' id=\"searchform\" method=\"get\">
                <input value=\"\" name=\"q\" type=\"search\" placeholder=\"Search for anything...\"/>
                <button type=\"submit\"><i class=\"icon_search\"></i>
                </button>
            </form>
        </div><!-- End Search Menu -->
    </header>";
}

else {
    function getUserType($con, $uid){
        $userTypeQuery = "SELECT type FROM user WHERE uid=".$uid;
        $userTypeResult = mysqli_query($con, $userTypeQuery);
        $userTypeData = mysqli_fetch_array($userTypeResult);
        return $userTypeData['type'];
    }
    if(getUserType($con, $_SESSION['uid'])==0) {
        echo "<header class=\"header menu_2\">
        <div id=\"preloader\">
            <div data-loader=\"circle-side\"></div>
        </div><!-- /Preload -->
        <div id=\"logo\">
            <a href=\"index.html\"><img src=\"img/logo.png\" width=\"149\" height=\"42\" data-retina=\"true\" alt=\"\"></a>
        </div>
        <!-- /top_menu -->
        <a href=\"#menu\" class=\"btn_mobile\">
            <div class=\"hamburger hamburger--spin\" id=\"hamburger\">
                <div class=\"hamburger-box\">
                    <div class=\"hamburger-inner\"></div>
                </div>
            </div>
        </a>
        <nav id=\"menu\" class=\"main-menu\">
            <ul>
                <li>
                    <span><a href=\"index.php\">Home</a></span>
                </li>
                <li>
                    <span><a href=\"../dashboard/\">Dashboard</a></span>
                </li>
                
                <li>
                    <span><a href=\"#0\" class=\"search-overlay-menu-btn\">Search</a></span>
                </li>
                
                <li><span><a href=\"../home/about.php\">About</a></span></li>   
                <li><span><a href=\"../home/contact.php\">Contact Us</a></span></li>     
                
                <li>
                    <span><a href='../logout'>Logout</a></span>                
                </li>
            </ul>
        </nav>
        <!-- Search Menu -->
        <div class=\"search-overlay-menu\">
            <span class=\"search-overlay-close\"><span class=\"closebt\"><i class=\"ti-close\"></i></span></span>
            <form role=\"search\" action='../home/courses.php' id=\"searchform\" method=\"get\">
                <input value=\"\" name=\"q\" type=\"search\" placeholder=\"Search...\"/>
                <button type=\"submit\"><i class=\"icon_search\"></i>
                </button>
            </form>
        </div><!-- End Search Menu -->
    </header>";
    }
    if(getUserType($con, $_SESSION['uid'])==1){
        echo "<header class=\"header menu_2\">
        <div id=\"preloader\">
            <div data-loader=\"circle-side\"></div>
        </div><!-- /Preload -->
        <div id=\"logo\">
            <a href=\"index.html\"><img src=\"img/logo.png\" width=\"149\" height=\"42\" data-retina=\"true\" alt=\"\"></a>
        </div>
        <!-- /top_menu -->
        <a href=\"#menu\" class=\"btn_mobile\">
            <div class=\"hamburger hamburger--spin\" id=\"hamburger\">
                <div class=\"hamburger-box\">
                    <div class=\"hamburger-inner\"></div>
                </div>
            </div>
        </a>
        <nav id=\"menu\" class=\"main-menu\">
            <ul>
                <li>
                    <span><a href=\"index.php\">Home</a></span>
                </li>
                <li>
                    <span><a href=\"../dashboard/\">Dashboard</a></span>
                </li>
                
                <li>
                    <span><a href=\"#0\" class=\"search-overlay-menu-btn\">Search</a></span>
                </li>
                
                <li><span><a href=\"../home/about.php\">About</a></span></li>
                <li><span><a href=\"../home/contact.php\">Contact Us</a></span></li>     
                
                <li>
                    <span><a href='../logout'>Logout</a></span>                
                </li>
            </ul>
        </nav>
        <!-- Search Menu -->
        <div class=\"search-overlay-menu\">
            <span class=\"search-overlay-close\"><span class=\"closebt\"><i class=\"ti-close\"></i></span></span>
            <form role=\"search\" action='../home/courses.php' id=\"searchform\" method=\"get\">
                <input value=\"\" name=\"q\" type=\"search\" placeholder=\"Search...\"/>
                <button type=\"submit\"><i class=\"icon_search\"></i>
                </button>
            </form>
        </div><!-- End Search Menu -->
    </header>";
    }
}
?>
<!--<li><span><a href="about.php">About</a></span>
            </li>
            <li>
                <span><a href="contact.php">Contact Us</a></span>
            </li>-->