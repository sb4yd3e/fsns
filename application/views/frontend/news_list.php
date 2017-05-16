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

        <ul class="grid_12 blog-posts boxes">

            <?php foreach ($news_list as $news): ?>
                <!-- News Item -->
                <li class="grid_4 omega blog-post format-standard clearfix">  
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






        </ul>
    </div>
</section>

<script>
    $(window).load(function(){
        var max_height = 0;
       $('.blog-post').each(function(){
           if ($(this).height() > max_height)
           {
               max_height = $(this).height();
           }
           
       });
       $('.blog-post').height(max_height);
    });
    </script>