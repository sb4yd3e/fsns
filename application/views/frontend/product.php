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
                <?php foreach ($products as $product): ?>
                    <!-- Product Item -->
                    <li class="grid_4">
                        <figure class="portfolio clearfix">
                            <div class="portfolio-image">
                                <a href="<?php echo base_url('product/' . $product['id'] . '/' . url_title($product['title'])); ?>">
                                    <img src="./timthumb.php?src=./<?php echo PRODUCT_PATH ?>/<?php echo $product['cover'] ?>&zc=1&w=449&235"
                                         alt="portfolio" width="300"
                                         alt="<?php echo $web_title . ' - ' . $product['title'] ?>"/>
                                </a>

                            </div>

                            <figcaption>
                                <div class="caption-title">
                                    <p class="title">
                                        <?php echo strtoupper(strip_tags($product['title'])) ?>
                                    </p>

                                    <span class="subtitle">
                                    <?php echo $product['body'] ?>
                                </span>
                                    <?php if (is_login() && $product['online'] == 1) { ?>
                                        <div class="title"><?php echo number_format(($product['special_price'] >0)?$product['special_price']:$product['normal_price'],2); ?> บาท</div>
                                        <?php } else { ?>
                                        <div class="title">กรุณาสอบถาม</div>
                                    <?php } ?>
                                </div>
                            </figcaption>
                        </figure>
                    </li>
                    <!-- End of Product item -->
                <?php endforeach; ?>
                <li class="clearfix"></li>
            </ul>
        </section>
        <?php
    endforeach;
    ?>
</section><!-- #content-wrapper end -->
<script>
    $(function () {
        var max_height = 0;
        $('.caption-title').each(function () {
            if ($(this).height() > max_height) {
                max_height = $(this).height();
            }

        });
        $('.caption-title').height(max_height);
    });
</script>