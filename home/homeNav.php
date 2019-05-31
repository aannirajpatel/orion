<?php
/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 31/5/19 10:49 AM.
 */

/**
 * Written by Aan (aancodes@gmail.com) at Global BizConnect on 31/5/19 10:29 AM.
 */
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
                    <span><a href=\"courses.php\">Courses</a></span>
                </li>
                
                <li>
                    <span><a href=\"#0\" class=\"search-overlay-menu-btn\">Search</a></span>
                </li>
              
                <li><span><a href=\"about.php\">About</a></span>
                </li>
                <li>
                    <span><a href=\"contact.php\">Contact Us</a></span>
                </li>
                <li><span><a href=\"../login/\">Login</a></span></li>
                <li><span><a href=\"../login/register.php\">Register</a></span></li>
            </ul>
        </nav>
        <!-- Search Menu -->
        <div class=\"search-overlay-menu\">
            <span class=\"search-overlay-close\"><span class=\"closebt\"><i class=\"ti-close\"></i></span></span>
            <form role=\"search\" action='courses.php' id=\"searchform\" method=\"get\">
                <input value=\"\" name=\"q\" type=\"search\" placeholder=\"Search...\"/>
                <button type=\"submit\"><i class=\"icon_search\"></i>
                </button>
            </form>
        </div><!-- End Search Menu -->
    </header>";