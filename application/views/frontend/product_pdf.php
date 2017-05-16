
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



    <section class="container_12 clearfix" style="position:relative;clear:both">
        <div class="videoWrapper">
            <iframe src="https://docs.google.com/document/d/1fpGYC8RXMBJFXpzWKO5miRefGqr0qo1ANvsO7Fayj0k/pub?embedded=true" width="560" height="1000"></iframe>
        </div>
        <style>
            .videoWrapper {
                position: relative;
                padding-bottom: 56.25%; /* 16:9 */
                padding-top: 25px;
                height: 0;
            }
            .videoWrapper iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
        </style>
    </section>

</section><!-- #content-wrapper end -->
<script>
    $(function () {
        var max_height = 0;
        $('.caption-title').each(function () {
            if ($(this).height() > max_height)
            {
                max_height = $(this).height();
            }

        });
        $('.caption-title').height(max_height);
    });
</script>