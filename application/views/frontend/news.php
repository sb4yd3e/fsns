
<section class="page-title-container">
    <div class="container_12">
        <div class="page-title grid_12">
            <div class="title">
                <h1><?php echo $web_title ?></h1>

            </div>
        </div>
    </div>
</section>

<section id="content-wrapper">
    <div class="container_12">
        <article class="grid_12 blog-posts blog-post-single">
            <article class="blog-post format-standard clearfix">  
                <article class="post-body-container">
                    <!-- News Content -->
                    <div class="post-body">
                        <?php echo $news['body']?>
                        <br/>
                        <b>สร้างเมื่อ: </b><?php echo date('j M Y',$news['created_at'])?>
                    </div>
                    <!-- End of News Content-->
                </article>
            </article>
        </article>
    </div>
</section>
