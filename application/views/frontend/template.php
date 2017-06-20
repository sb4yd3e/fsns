<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $web_title ?> | <?php echo $this->setting_data['site_title']; ?></title>
    <base href="<?php echo base_url() ?>"/>
    <meta name="description" content="<?php echo $this->setting_data['site_description']; ?>"/>
    <meta name="keywords" content="<?php echo $this->setting_data['site_keyword']; ?>">

    <meta name="author" content="Food Service and Solution Co.,Ltd"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width"/>
    <link rel="shortcut icon" href="<?php echo base_url('img/favicon.ico'); ?>"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=yes">
    <link rel="author" href="https://plus.google.com/114326356306018262958/about"/>


    <!-- stylesheets -->
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('css/blue.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('css/override.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('css/product.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('css/sweetalert.css'); ?>"/>
    <!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700&amp;subset=latin,greek-ext,greek,vietnamese,latin-ext,cyrillic' rel='stylesheet' type='text/css'/>-->
    <link rel="stylesheet" href="<?php echo base_url('pixons/style.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('css/prettyPhoto.css'); ?>" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url('service-icons/style.css'); ?>"/>
    <!-- REVOLUTION BANNER CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('rs-plugin/css/settings.css'); ?>" media="screen"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('rs-plugin/css/revolution.css'); ?>"
          media="screen"/>

    <!--[if lt IE 9]>
    <script src="<?php echo base_url('js/html5shiv.js'); ?>"></script>
    <![endif]-->

    <!--[if IE 8]>
    <link rel="stylesheet" href="<?php echo base_url('css/ie8.css'); ?>" media="screen"/>
    <![endif]-->

    <!-- scripts -->
    <script src="<?php echo base_url('js/jquery-1.8.3.js'); ?>"></script> <!-- jQuery library -->
    <script src="<?php echo base_url('js/jquery.placeholder.min.js'); ?>"></script>
    <!-- jQuery placeholder fix for old browsers -->

    <!-- jQuery REVOLUTION Slider  -->
    <script type="text/javascript"
            src="<?php echo base_url('rs-plugin/js/jquery.themepunch.plugins.min.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('rs-plugin/js/jquery.themepunch.revolution.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/form.js'); ?>"></script>
    <script src="<?php echo base_url('js/include.js'); ?>"></script>
    <script src="<?php echo base_url('js/product.js'); ?>"></script>
    <script src="<?php echo base_url('js/sweetalert.min.js'); ?>"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- FB -->
    <meta property="og:title" content="FSNS Thailand"/>
    <meta property="og:description" content="FOOD Service & Solution"/>
    <meta property="og:image" content="<?php echo base_url('img/logo.png'); ?>"/>


</head>

<body>
<section id="header-wrapper" class="clearfix">
    <!-- #header start -->
    <header id="header" class="clearfix">

        <!-- #logo start -->
        <section id="logo">
            <a href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url('img/logo.png'); ?>" alt="logo"/>
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
                    <li class="has-sub">
                        <a href="javascript:void(0)">Products</a>
                        <ul>
                            <?php foreach ($product_category as $category): ?>

                                <li>
                                    <a href="javascript:void(0)"><?php echo strip_tags($category['title']) ?></a>
                                        <ul>
                                <?php foreach ($category['children'] as $subcategory): ?>
                                    <li>
                                        <a href="<?php echo base_url('catalog/' . $category['term_id']); ?>/<?php echo url_title($category['title']) ?>/<?php echo $subcategory['term_id'] ?>/<?php echo url_title($subcategory['title']) ?>"><?php echo $subcategory['title'] ?></a>
                                    </li>
                                <?php endforeach ?>
                                    </ul></li>


                            <?php endforeach ?>
                        </ul>
                    </li>

                    <li class="has-sub <?php echo $active_menu === 'services' ? 'current-menu-item' : '' ?>">
                        <a href="javascript:void(0)">Services</a>
                        <ul style="visibility: hidden; display: block;">
                            <li><a href="<?php echo base_url('services/food_safety_inspection'); ?>">Food Safety
                                    Inspection</a></li>
                            <li><a href="<?php echo base_url('services/basic_food_safety'); ?>">Basic Food Safety</a>
                            </li>
                            <li><a href="<?php echo base_url('services/gmp_haccp_awareness'); ?>">GMP &amp; HACCP
                                    Awareness</a></li>
                        </ul>
                    </li>

                    <li class="<?php echo $active_menu === 'news' ? 'current-menu-item' : '' ?>">
                        <a href="<?php echo base_url('news'); ?>">News</a>
                    </li>

                    <li class="<?php echo $active_menu === 'contact' ? 'current-menu-item' : '' ?>"><a
                                href="<?php echo base_url('contact'); ?>">Contact</a>
                    </li>
                    <li class="<?php echo $active_menu === 'cart' ? 'current-menu-item' : '' ?>"><a href="<?php echo base_url('shopping-carts'); ?>">My Carts <span
                                    class="cart-number"></span></a></li>

                    <li class="has-sub <?php echo $active_menu === 'member' ? 'current-menu-item' : '' ?>">
                        <a href="javascript:void(0)">Member</a>
                        <ul style="visibility: hidden; display: block; width: 200px;">
                            <?php if (is_login()) { ?>                                <li><a href="<?php echo base_url('profile'); ?>">Profile</a></li>
                                <li><a href="<?php echo base_url('my-orders'); ?>">My Orders</a></li>
                                <li><a href="<?php echo base_url('logout'); ?>">Logout</a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo base_url('register'); ?>">Register</a></li>
                                <li><a href="<?php echo base_url('login'); ?>">Login</a></li>
                            <?php } ?>
                        </ul>
                    </li>

                </ul>

            </nav><!-- #nav end -->

            <!-- responsive navigation start -->
            <select id="nav-responsive">
                <option value="index.php" <?php echo $active_menu === 'home' ? 'selected="selected"' : '' ?>>Home
                </option>

                <?php foreach ($product_category as $category): ?>

                    <optgroup label="<?php echo strip_tags($category['title']); ?>">
                        <?php foreach ($category['children'] as $subcategory): ?>
                            <option value="<?php echo base_url('catalog/' . $category['term_id']); ?>/<?php echo url_title($category['title']) ?>/<?php echo $subcategory['term_id'] ?>/<?php echo url_title($subcategory['title']) ?>"><?php echo strip_tags($subcategory['title']); ?></option>

                        <?php endforeach; ?>


                    </optgroup>

                <?php endforeach ?>


                <option value="news" <?php echo $active_menu === 'news' ? 'selected="selected"' : '' ?>>News</option>
                <option value="contact" <?php echo $active_menu === 'contact' ? 'selected="selected"' : '' ?>>
                    Contact
                </option>
                <optgroup label="Member">
                    <option value="<?php echo base_url('shopping-carts'); ?>">My Carts</option>
                    <?php if (is_login()) { ?>
                        <option value="<?php echo base_url('profile'); ?>">Profile</option>
                        <option value=<?php echo base_url('my-orders'); ?>"">My Orders></option>
                        <option value="<?php echo base_url('logout'); ?>">Logout</option>
                    <?php } else { ?>
                        <option value="<?php echo base_url('register'); ?>">Register</option>
                        <option value="<?php echo base_url('login'); ?>">Login</option>
                    <?php } ?>

                </optgroup>
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
                <img src="<?php echo base_url('img/admin_logo.png'); ?>" alt="FSNS thailand" style="width:150px"/>
                <p>
                    Food Service and Solution Co.,Ltd</br>
                    29 S.Chalaemnimit,</br>
                    Bangkhlo, Bangkorlaem,</br>
                    Bangkok 10120
                </p>
            </li>
        </ul>

        <!-- .footer-widget-container start -->
        <ul class="footer-widget-container grid_6" style="margin-bottom:0">
            <!-- .widget_tag_cloud start -->
            <li class="widget widget_tag_cloud">
                <div class="title">
                    <h5>OUR PARTNERS</h5>
                </div>
                <a href="http://www.fda.gov/" target="_blank" rel="nofollow"
                   style="float:left;display:block;margin: 10px;padding:5px;min-width: 100px;height: 100px"><img
                            src="<?php echo base_url('fsns_assets/misc/fda.png'); ?>" style="width:100px;"/></a>
                <a href="http://www.eurobrush.com/" target="_blank" rel="nofollow"
                   style="float:left;margin: 10px;padding:5px;min-width: 100px;min-height: 100px"><img
                            src="<?php echo base_url('fsns_assets/misc/feibp.png'); ?>" style="width:100px;"/></a>
                <a href="javascript:void();" target="_blank" rel="nofollow"
                   style="float:left;margin: 10px;padding:5px;min-width: 100px;min-height: 100px"><img
                            src="<?php echo base_url('fsns_assets/misc/issa.png'); ?>" style="width:100px;"/></a>
            </li>
        </ul>

        <ul class="footer-widget-container grid_3" style="margin-bottom:0">
            <li class="widget widget_text">
                <div class="title">
                    <h5>get in touch</h5>
                </div>
                <p>
                    If you have any questions you can contact us via
                    our contact form <a class="text-color" href="<?php echo base_url('contact'); ?>">here</a> or
                    social networks.
                </p>

                <ul class="social-links">
                    <li>
                        <a target="_blank" href="<?php echo $this->setting_data['google_plus']; ?>"
                           class="pixons-google_plus" rel="author"></a>
                    </li>

                    <li>
                        <a target="_blank" href="<?php echo $this->setting_data['facebook']; ?>"
                           class="pixons-facebook-1"></a>
                    </li>

                    <li>
                        <a target="_blank" href="<?php echo $this->setting_data['instagram']; ?>"
                           class="pixons-instagram"></a>
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
                    <li class="phone"><a href="tel:0838392929">083-839-2929</a>, <a
                                href="tel:0816152621">081-615-2621</a></li>
                    <li class="mail">
                        <a href="mailto:<?php echo $this->setting_data['email_for_contact']; ?>"><?php echo $this->setting_data['email_for_contact']; ?></a>
                    </li>
                </ul>
            </div>

        </div>
    </section> <!-- .copyright-container end -->

</div><!-- #footer-wrapper end -->

<?php
$this->load->helper('cookie');
if ($this->banner_data['visible'] == '1' && !get_cookie('show_popup')) {
    set_cookie('show_popup', 'true', $this->banner_data['delay']);
    ?>
    <div id="popup-overlay"></div>
    <div id="popup-box">
        <div id="close"><img src="<?php echo base_url('img/close.png'); ?>" width="25" height="25" alt=""></div>
        <a href="<?php echo $this->banner_data['link']; ?>" target="_blank">
            <img src="<?php echo base_url(BANNER_PATH . '/' . $this->banner_data['image']); ?>" alt="">
        </a>
    </div>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#popup-overlay').fadeIn(500);
                $('#popup-box').fadeIn(500);
            }, 3000)

            $('#close,#popup-overlay').click(function () {
                $('#popup-overlay').fadeOut(500);
                $('#popup-box').fadeOut(500);
            });
        });
    </script>
<?php } ?>

<div id="lightbox-overlay"></div>
<div id="lightbox">
    <div id="close-lightbox"><img src="<?php echo base_url('img/close.png'); ?>" width="25" height="25" alt=""></div>
    <div id="order-info">
        <div class="col-6">
            <h3>สินค้า 1 ชิ้น ได้ถูกเพิ่มเข้าไปยังตะกร้าสินค้าของคุณ</h3>
            <div class="p-thumb"></div>
            <div class="p-detail">
                <div class="p-title"></div>
                <div class="p-code"></div>
                <div class="p-price"></div>
                <div class="p-spprice"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-6">
            <div class="num-text">ตะกร้าสินค้าของคุณ <span class="cart-number">0</span> สินค้า</div>
            <div class="total-amount">มูลค่าสินค้า: <span>0</span> บาท</div>
            <div class="total-vat">ยอดสุทธิ (รวมภาษีมูลค่าเพิ่ม): <span>0</span> บาท</div>
            <a href="<?php echo base_url('checkout/delivery-info'); ?>" class="p-checkout">ชำระค่าสินค้า</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>


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