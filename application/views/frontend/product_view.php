<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">
        <div class="container_6">
            <div class="inner-wrap">
                <div class="big-thumb">
                    <img src="<?php echo base_url('timthumb.php?src=') . base_url('uploads/products/' . $product_data['cover']) . '&w=480&h=480&z=c'; ?>"
                         alt="<?php echo $product_data['title']; ?>">
                </div>
                <?php if ($product_data['pdf']) { ?>
                    <div class="pdf">
                        <a href="<?php echo base_url() ?>frontend/product_pdf_download/<?php echo $product_data['id'] ?>/<?php echo md5($product_data['id'] . 'suwichalala') ?>/<?php echo url_title($product_data['title']) ?>_Specification.pdf"
                           target="_blank"><img src="<?php echo base_url('img/download-pdf.png'); ?>"
                                                alt="Download PDF"> ดาวน์โหลด
                            PDF</a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="container_6">
            <div class="inner-wrap">
                <input type="hidden" id="product_pid" value="<?php echo $product_data['id']; ?>">
                <input type="hidden" id="product_title" value="<?php echo $product_data['title']; ?>">
                <input type="hidden" id="product_paid">
                <input type="hidden" id="product_code">
                <input type="hidden" id="product_value">
                <input type="hidden" id="product_price">
                <input type="hidden" id="product_spprice">

                <div class="product-title">
                    <?php echo $product_data['title']; ?>
                </div>
                <div class="product-desc">
                    <?php echo $product_data['info']; ?>
                </div>
                <div class="product-code">

                    CODE : <span id="code"><?php echo $product_attr[0]['code']; ?></span>
                </div>
                <strong>เลือกสี : </strong><br>
                <div class="product-color">

                    <div class="color-wrap">
                        <?php foreach ($product_attr as $k => $attr) { ?>
                            <div class="select-color" data-code="<?php echo $attr['code']; ?>"
                                 data-price="<?php echo $attr['normal_price']; ?>"
                                 data-stock="<?php echo $attr['in_stock']; ?>"
                                 data-spprice="<?php echo $attr['special_price']; ?>"
                                 data-aid="<?php echo $attr['pa_id']; ?>"
                                 data-code="<?php echo $attr['code']; ?>"
                                 data-value="<?php echo $attr['p_value']; ?>"
                                 style="background: <?php echo $attr['color']; ?>"></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="product-price">

                    <?php if (is_login()) { ?>
                        ราคา :
                        <?php if ($product_data['special_price'] > 0) { ?>
                            <span class="default-price"><s><?php echo $product_data['normal_price']; ?></s></span>
                            <span class="special-price"><?php echo $product_data['special_price']; ?></span>
                        <?php } else { ?>
                            <span class="default-price"><?php echo $product_data['normal_price']; ?></span>
                            <span class="special-price"></span>
                        <?php } ?>
                        บาท / ชิ้น
                    <?php } else { ?>
                    <span class="special-price">Please login to make an order.</span>
                    <?php } ?>
                </div>
                <div class="product-qty">
                    <input type="number" class="number" min="1" id="qty" value="1">
                </div>
                <div class="product-addcart">
                    <button id="add-to-cart" <?php if (!is_login()) { ?>disabled<?php } ?>>ADD TO CART</button>
                </div>

            </div>
        </div>
        <div class="clearfix"></div>
        <div id="product-content">
            <div class="inner-wrap">
                <?php echo $product_data['body']; ?>
            </div>
        </div>
    </section>
</section>