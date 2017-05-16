<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $web_title ?> | FSNS</title>
        <base href="<?php echo base_url() ?>"/>
        <meta name="description" content="FSNS Thailand"/>
        <meta name="keywords" content="">

        <meta name="author" content="Food Service and Solution Co.,Ltd"/>
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width"/>
        <link rel="shortcut icon" href="./img/favicon.ico" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />  
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=yes">
        <link rel="author" href="https://plus.google.com/114326356306018262958/about"/>


        <!-- stylesheets -->
        <link rel="stylesheet" href="./css/style.css" />  
        <link rel="stylesheet" href="./css/blue.css" />
        <link rel="stylesheet" href="./css/override.css" />  
        <!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&amp;subset=latin,greek-ext,greek,vietnamese,latin-ext,cyrillic' rel='stylesheet' type='text/css'/>-->
        <link rel="stylesheet" href="./pixons/style.css" />
        <link rel="stylesheet" href="./css/prettyPhoto.css" media="screen" />
        <link rel="stylesheet" href="./service-icons/style.css"/>
        <!-- REVOLUTION BANNER CSS SETTINGS -->
        <link rel="stylesheet" type="text/css" href="./rs-plugin/css/settings.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="./rs-plugin/css/revolution.css" media="screen" />

        <!--[if lt IE 9]>
            <script src="./js/html5shiv.js"></script>
        <![endif]-->

        <!--[if IE 8]>
            <link rel="stylesheet" href="./css/ie8.css" media="screen" />
        <![endif]-->

        <!-- scripts -->
        <script  src="./js/jquery-1.8.3.js"></script> <!-- jQuery library -->  
        <script  src="./js/jquery.placeholder.min.js"></script><!-- jQuery placeholder fix for old browsers -->

        <!-- jQuery REVOLUTION Slider  -->
        <script type="text/javascript" src="./rs-plugin/js/jquery.themepunch.plugins.min.js"></script>
        <script type="text/javascript" src="./rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
        <script  src="./js/include.js"></script> <!-- jQuery custom options -->

        <!-- FB -->
        <meta property="og:title" content="FSNS Thailand" />
        <meta property="og:description" content="FOOD Service & Solution" />
        <meta property="og:image" content="./img/logo.png" />


    </head>

    <body>
        <section id="header-wrapper" class="clearfix">
            <!-- #header start -->
            <header id="header" class="clearfix">

                <!-- #logo start -->
                <section id="logo">
                    <a href="./">
                        <img src="./img/logo.png" alt="logo" />
                    </a>
                </section><!-- #logo end -->

                <!-- #nav-container start -->
                <section id="nav-container">
                    <?php
                    if (!isset($active_menu)) {
                        $active_menu = '';
                    }
                    ?>
                    <!-- #nav start -->
                    <nav id="nav">
                        <ul>
                            <?php foreach ($product_category as $category): ?>
                                <li class="has-sub">
                                    <a href="javascript:void(0)"><?php echo $category['title'] ?></a>
                                    <ul>
                                        <?php foreach ($category['children'] as $subcategory): ?>
                                            <li><a href="./catalog/<?php echo $category['term_id'] ?>/<?php echo url_title($category['title']) ?>/<?php echo $subcategory['term_id'] ?>/<?php echo url_title($subcategory['title']) ?>"><?php echo $subcategory['title'] ?></a></li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                            <?php endforeach ?>

                            <li class="has-sub <?php echo $active_menu === 'services' ? 'current-menu-item' : '' ?>">
                                <a href="javascript:void(0)">Services</a>
                                <ul style="visibility: hidden; display: block;">
                                    <li><a href="./services/food_safety_inspection">Food Safety Inspection</a></li>
                                    <li><a href="./services/basic_food_safety">Basic Food Safety</a></li>
                                    <li><a href="./services/gmp_haccp_awareness">GMP &amp; HACCP Awareness</a></li>
                                </ul>
                            </li>

                            <li class="<?php echo $active_menu === 'news' ? 'current-menu-item' : '' ?>">
                                <a href="./news">News</a>
                            </li>

                            <li class="<?php echo $active_menu === 'contact' ? 'current-menu-item' : '' ?>"><a href="./contact">Contact</a></li>

                        </ul>

                    </nav><!-- #nav end -->

                    <!-- responsive navigation start -->
                    <select id="nav-responsive">
                        <option value="index.php" <?php echo $active_menu === 'home' ? 'selected="selected"' : '' ?>>Home</option>
						
						<?php foreach ($product_category as $category): ?>
						
						<optgroup label="<?php echo $category['title'] ?>">
						<?php foreach ($category['children'] as $subcategory): ?>
						 <option value="./catalog/<?php echo $category['term_id'] ?>/<?php echo url_title($category['title']) ?>/<?php echo $subcategory['term_id'] ?>/<?php echo url_title($subcategory['title']) ?>"><?php echo $subcategory['title'] ?></option>
                                         
                                        <?php endforeach; ?>
						
                           
                        </optgroup>
						
                              
                            <?php endforeach ?>
						
						
                        

                        <option value="./news" <?php echo $active_menu === 'news' ? 'selected="selected"' : '' ?>>News</option>
                        <option value="./contact" <?php echo $active_menu === 'contact' ? 'selected="selected"' : '' ?>>Contact</option>

                    </select><!-- responsive navigation end -->

                </section><!-- #nav-container end -->
            </header><!-- #header end -->
        </section>

        <!-- .top-shadow -->
        <div class="top-shadow"></div>

        <div class="clearfix"></div>

        <?php echo $content ?>

        <!-- Footer -->
        <div id="footer-wrapper" class="clearfix">


            <footer id="footer" class="container_12">


                <ul class="footer-widget-container grid_3" style="margin-bottom:0">
                    <li class="widget widget_text">
                        <img src="./img/admin_logo.png" alt="FSNS thailand" style="width:150px"/>
                        <p>
                            Food Service and Solution Co.,Ltd</br>
                            29 S.Chalaemnimit,</br>
                            Bangkhlo, Bangkorlaem,</br>
                            Bangkok 10120
                        </p>
                    </li>
                </ul>

                <!-- .footer-widget-container start -->
                <ul class="footer-widget-container grid_6"  style="margin-bottom:0">
                    <!-- .widget_tag_cloud start -->
                    <li class="widget widget_tag_cloud">
                        <div class="title">
                            <h5>OUR PARTNERS</h5>
                        </div>
                        <a href="http://www.fda.gov/" target="_blank" rel="nofollow" style="float:left;display:block;margin: 10px;padding:5px;min-width: 100px;height: 100px"><img src="fsns_assets/misc/fda.png" style="width:100px;"/></a>
                        <a href="http://www.eurobrush.com/" target="_blank"  rel="nofollow"  style="float:left;margin: 10px;padding:5px;min-width: 100px;min-height: 100px"><img src="fsns_assets/misc/feibp.png" style="width:100px;"/></a>
                        <a href="javascript:void();" target="_blank"  rel="nofollow"  style="float:left;margin: 10px;padding:5px;min-width: 100px;min-height: 100px"><img src="fsns_assets/misc/issa.png" style="width:100px;"/></a>
                    </li>
                </ul>

                <ul class="footer-widget-container grid_3"  style="margin-bottom:0">
                    <li class="widget widget_text">
                        <div class="title">
                            <h5>get in touch</h5>
                        </div>
                        <p>
                            If you have any questions you can contact us via
                            our contact form <a class="text-color" href="./contact">here</a> or
                            social networks.
                        </p>

                        <ul class="social-links">
                            <li>
                                <a target="_blank" href="https://plus.google.com/114326356306018262958/about" class="pixons-google_plus" rel="author"></a>
                            </li>

                            <li>
                                <a target="_blank" href="https://www.facebook.com/fsns.thailand" class="pixons-facebook-1"></a>
                            </li>

                            <li>
                                <a target="_blank" href="https://www.instagram.com/foodserviceandsolution" class="pixons-instagram"></a>
                            </li>

                        </ul>

                    </li>
                </ul>


            </footer>

            <!-- .copyright-container start -->
            <section class="copyright-container">
                <div class="container_12">
                    <div class="grid_6">
                        <p>
                            Copyright FSNS <?php echo date('Y') ?>. All rights reserved.
                        </p>
                    </div>

                    <div class="grid_6">
                        <ul class="footer-contact-info">
                            <li class="phone">083-839-2929, 081-615-2621</li>
                            <li class="mail"><a href="mailto:contact@fsns-thailand.com">contact@fsns-thailand.com</a></li>
                        </ul>
                    </div>

                </div>
            </section> <!-- .copyright-container end -->

        </div><!-- #footer-wrapper end -->





        <script>
            /* <![CDATA[ */
            /* REVOLUTION SLIDER */
            jQuery(document).ready(function () {

                if (jQuery.fn.cssOriginal !== undefined) {
                    // CHECK IF fn.css already extended
                    jQuery.fn.css = jQuery.fn.cssOriginal;
                }

                jQuery('.fullwidthbanner').revolution(
                        {
                            delay: 5000,
                            startheight: 420,
                            startwidth: 940,
                            hideThumbs: 200,
                            thumbWidth: 100,
                            thumbHeight: 50,
                            thumbAmount: 5,
                            navigationType: "none",
                            navigationArrows: "verticalcentered",
                            navigationStyle: "round",
                            navigationHAlign: "right",
                            navigationVAlign: "top",
                            navigationHOffset: 0,
                            navigationVOffset: 20,
                            soloArrowLeftHalign: "left",
                            soloArrowLeftValign: "center",
                            soloArrowLeftHOffset: 20,
                            soloArrowLeftVOffset: 0,
                            soloArrowRightHalign: "right",
                            soloArrowRightValign: "center",
                            soloArrowRightHOffset: 20,
                            soloArrowRightVOffset: 0,
                            touchenabled: "on",
                            onHoverStop: "on",
                            navOffsetHorizontal: 0,
                            navOffsetVertical: 20,
                            hideCaptionAtLimit: 0,
                            hideAllCaptionAtLilmit: 0,
                            hideSliderAtLimit: 0,
                            stopAtSlide: -1,
                            stopAfterLoops: -1,
                            shadow: 1,
                            fullWidth: "on"
                        });
            });


        </script>
    </body>
</html>