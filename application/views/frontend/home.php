
<!-- slider start -->
<div class="slider-wrapper">

    <div class="fullwidthbanner-container">

        <div class="fullwidthbanner">
            <ul>
                <li data-transition="boxslide">
                    <img src="img/slider/slider-bkg-2.png" alt="Slider background">
                    <div class="caption lfr"  data-x="379" data-y="0" data-speed="300" data-start="1000"><img src="img/slider/haccp.png" alt="HACCP Colour Coding"></div>   
                    <div class="caption lfl regular_title"  data-x="0" data-y="100" data-speed="700" data-start="300" data-easing="easeOutBack">The Benefits</div>
                    <div class="caption lfl regular_subtitle"  data-x="0" data-y="140" data-speed="500" data-start="200" data-easing="easeOutBack">of implementing HACCP & Colour coding</div>
                    <div class="caption lfl regular_text"  data-x="0" data-y="180" data-speed="500" data-start="100" data-easing="easeOutBack">- Helps eliminate cross-contamination</br>- Improves awareness of potential hazards</br>- Prioritises and control potential hazards</br>- Internationally recognised approach</br>- Used at all stages of food production</br>- Improves customer and consumer confidence</br>- Trading benefit</br>- Can be aligned with other Management & QA systems</div>
                </li>

            </ul>
        </div>
    </div>
</div> <!-- slider end -->

<!-- #content-wrapper start -->
<section id="content-wrapper">
    <div class="container_12">


        <!-- Motto Section -->
        <article class="grid_12 note">
            <div class="note-content" style="text-align: justify">
                <center>
                    <h2>"SUPPORT ALL ABOUT FOOD WITH GOOD SERVICE AND BENEFIT SOLUTION"</h2>
                </center>
            </div>
        </article>

        <article class="grid_12">
            <a href="./catalog/25/Hillbrush/28/Anti-Microbial">
                <section class="service-box" style="text-align:center">
                    <img src="fsns_assets/misc/icons/icon1.png"> 

                    <h5>Anti-Microbial</h5>

                    <p>
                        Products contain a uniquely created additive using silver ion technology at a precise concentration to prevent in excess of 99% of bacterial contamination including MRSA and E-Coli. With comprehensive approval for food contact applications.</br></br></br>
                    </p>
                </section>
            </a>

            <a href="./catalog/25/Hillbrush/29/Resin-Set">
                <section class="service-box" style="text-align:center">
                    <img src="fsns_assets/misc/icons/icon2.png">

                    <h5>Resin Set</h5>

                    <p>
                        HBC has developed a very effective “Dual Retention System” for securing brush filaments, whereby each tuft is stapled into the brush back with food grade stainless steel staples in the normal way, and the epoxy resin is floated into a recess on the face of the brush. This practically eliminates the risk of lost filaments.
                    </p>
                </section>
            </a>

            <a href="./catalog/25/Hillbrush/30/Professional-Premier">
                <section class="service-box" style="text-align:center">
                    <img src="fsns_assets/misc/icons/icon3.png">

                    <h5>Professional&Premier</h5>

                    <p>
                        Professional hygiene tool manufactured with total color coding for prevention of cross-contamination, segregation and HACCP.</br></br></br></br></br></br></br>
                    </p>
                </section>
            </a>

            <a href="./catalog/25/Hill-Brush/51/Halal-Approved-Brush">
                <section class="service-box"  style="text-align:center">
                    <img src="fsns_assets/misc/icons/icon5.png">

                    <h5>Halal Approved Hygienic Tools</h5>

                    <p>
                        Hill Brush presents the world’s first ever range of Halal
approved hygienic cleaning tools, made using fully compliant
materials and processes.</br></br></br></br></br></br></br>
                    </p>
                </section>
            </a>

        </article>

        <div class="clearfix"></div>

        <div class="divider grid_12">
            <div class="divider-icon">
                <img src="img/divider.png" alt="" />
            </div>
        </div>

        <div class="clearfix"></div>


        <article class="grid_12 blog-posts">
            <h3>Testimonial</h3>
            <section class="grid_6 alpha blog-post format-video">
                <iframe  src="//www.youtube.com/embed/9k7WSZCbNnI" frameborder="0" allowfullscreen></iframe>
            </section>
            <section class="grid_6 alpha blog-post format-video">
                <iframe  src="//www.youtube.com/embed/O1qXdDpZvz4" frameborder="0" allowfullscreen></iframe>
            </section>
        </article>

        <div class="divider grid_12">
            <div class="divider-icon">
                <img src="img/divider.png" alt="" />
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Motto Section -->
        <article class="grid_12 note">
            <div class="note-content" style="text-align: justify">
                <center>
                    <img src="./img/salmon-hygiene.png"/>
                </center>
                <h3 style="text-align: center">HILL BRUSH – SALMON HYGIENE TECHNOLOGY</h3>
                <p>
                    We manufacture and supply to over 85 countries worldwide with an extensive range of practical cleaning solutions designed for the professional, home and outdoors.
                </p>
                <p>
                    HBC is one of the most comprehensive systems in the world created for manual cleaning. These products are manufactured to clean hygienically sensitive areas within food production facilities, catering establishments, dairies and hospitals.
                </p>
                <p>
                    HBC colour coded cleaning equipment is covered by the FEIBP PHB Hygiene Charter and is all produced with FDA approved materials. The colour coded cleaning equipment is HACCP compliant and features within our Food & Beverage section above.
                    We specialize in manufacturing product lines for Food & Beverage, Healthcare, Janitorial, Hotel & Catering, Kitchens & Restaurants and Agricultural industries.
                </p>
            </div>
        </article>

        <div class="divider grid_12">
            <div class="divider-icon">
                <img src="img/divider.png" alt="" />
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- News Teaser Section -->
        <article class="grid_12 blog-posts">
            <h3>News</h3>
            <?php foreach ($news_list as $news): ?>
                <!-- News Item -->
                <li class="grid_4 omega blog-post format-standard clearfix" style="border-bottom:0">  
                    <a href="./news/<?php echo $news['id'] ?>/<?php echo url_title($news['title']) ?>" class="post-image" title="<?php echo $news['title'] ?>">
                        <img src="./<?php echo NEWS_PATH ?>/<?php echo $news['cover'] ?>" alt="blog post with image" width="300">
                    </a>

                    <article class="post-body-container">
                        <div class="post-category">
                            <i class="serviceicon-folder"></i>
                        </div>

                        <div class="post-body">
                            <a href="./news/<?php echo $news['id'] ?>/<?php echo url_title($news['title']) ?>" title="<?php echo $news['title'] ?>">
                                <h3><?php echo $news['title'] ?></h3>
                            </a>

                            <ul class="post-meta">
                                <li>
                                    <span class="date"><?php echo date('j M Y', $news['created_at']) ?></span>
                                </li>
                            </ul>

                            <?php echo character_limiter(strip_tags($news['body']), 350) ?>

                            <a href="./news/<?php echo $news['id'] ?>/<?php echo url_title($news['title']) ?>" title="<?php echo $news['title'] ?>">
                                Continue reading <span>&gt;</span>
                            </a>

                        </div>
                    </article>
                </li>
                <!-- End of New Item -->
            <?php endforeach; ?>

        </article>
    </div>
</section><!-- #content-wrapper end -->
<script>
    function resize_service_box()
    {
        $('.service-box').css('height', 'auto');
        var max_height = 0;
        $('.service-box').each(function () {
            if ($(this).height() > max_height)
                max_height = $(this).height();
        });
        $('.service-box').height(max_height);
    }
    $('.service-box img').load(function () {
        resize_service_box();
    });
    $(window).resize(function () {

        resize_service_box();
    });
</script>