
<section class="page-title-container">
    <div class="container_12">
        <div class="page-title grid_12">
            <div class="title">
                <h1><?php echo $web_title ?></h1>
                <p class="subtitle">
                    <?php echo $term['body'] ?>
                </p>
            </div>
        </div>
    </div>
</section>


<section id="content-wrapper">
    <?php
    $current_product_group = '';
    $result_product = array();
    // Manipulate //
    
    foreach ($products as $product):
        if ($product['group'] != $current_product_group) {
            $result_product[$product['group']][] = $product;
        }
    endforeach;
    ?>


    <?php
    foreach ($result_product as $key => $products):
        ?>
        <section class="container_12 clearfix">
            <h2><?php echo strtoupper($key) ?></h2>
            <ul id="filter-item">
                <?php foreach ($products as $product):?>
                <!-- Product Item -->
                <li class="grid_4">
                    <figure class="portfolio clearfix">
                        <div class="portfolio-image">
                            <a href="<?php echo base_url('product/'.$product['id'].'/'.url_title($product['title'])); ?>">
                                <img src="./timthumb.php?src=./<?php echo PRODUCT_PATH?>/<?php echo $product['cover']?>&zc=1&w=449&235" alt="portfolio" width="300" alt="<?php echo $web_title.' - '.$product['title']?>"/>
                            </a>
                            <?php if($product['pdf'] && $product['online']=='0'):?>
                            <div class="portfolio-hover">
                                <div class="mask"></div>
                                <ul>

                                    <li class="portfolio-single">
                                        <a href="<?php echo base_url()?>frontend/product_pdf_download/<?php echo $product['id']?>/<?php echo md5($product['id'].'suwichalala')?>/<?php echo url_title($product['title'])?>_Specification.pdf" target="_blank">PDF</a>
                                    </li>
                                </ul>
                            </div>
                            <?php endif;?>
                        </div>

                        <figcaption>
                            <div class="caption-title">
                                <p class="title">
                                    <?php echo strtoupper(strip_tags($product['title']))?>
                                </p>

                                <span class="subtitle">
                                    <?php echo $product['body']?>
                                </span>
                            </div>
                        </figcaption>
                    </figure>
                </li>
                <!-- End of Product item -->
                <?php endforeach;?>
                <li class="clearfix"></li>
            </ul>
        </section>
        <?php
    endforeach;
    ?>
</section><!-- #content-wrapper end -->
<script>
    $(function(){
        var max_height = 0;
       $('.caption-title').each(function(){
           if ($(this).height() > max_height)
           {
               max_height = $(this).height();
           }
           
       });
       $('.caption-title').height(max_height);
    });
    </script>