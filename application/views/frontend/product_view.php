<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">
        <div class="container_6">
            <div class="inner-wrap">
                <div class="big-thumb">
                    <img src="<?php echo base_url('timthumb.php?src=') . base_url('uploads/products/' . $product_data['cover']) . '&w=480&h=480&zc=2'; ?>"
                         alt="<?php echo $product_data['title']; ?>">

                </div>
                <input type="hidden" id="hidden-thumb" value="<?php echo $product_data['cover']; ?>">
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
                <input type="hidden" id="product_instock">
                <div class="product-title">
                    <?php echo $product_data['title']; ?>
                </div>
                <div class="product-desc">
                    <?php echo $product_data['body']; ?>

                </div>
                <?php //if ($product_data['online'] == '1') { ?>
                <div class="product-code">

                    CODE : <span
                            id="code"><?php echo ($product_data['model_code']) ? $product_data['model_code'] : '-'; ?></span>
                </div>
                <?php if ($product_data['att_type'] == "color") { ?>
                    <?php if (is_login() && $product_data['online'] == 1) { ?>
                    <strong>เลือกสี : </strong><br>
                <?php } else { ?>
                    <strong>สี : </strong><br>
                <?php } ?>
                    <div class="product-color">

                        <div class="color-wrap">
                            <?php foreach ($product_attr as $k => $attr) { ?>
                                <div class="select-color <?php if (!is_login() || $product_data['online'] != 1) { ?>disabled<?php } ?>"
                                     data-code="<?php echo $attr['code']; ?>"
                                     data-price="<?php echo is_login() ? $attr['normal_price'] : "0.00"; ?>"
                                     data-stock="<?php echo $attr['in_stock']; ?>"
                                     data-spprice="<?php echo $attr['special_price']; ?>"
                                     data-aid="<?php echo $attr['pa_id']; ?>"
                                     data-code="<?php echo $attr['code']; ?>"
                                     data-value="<?php echo $attr['p_value']; ?>"
                                     data-cover="<?php echo  $attr['p_cover']; ?>"
                                     style="background: <?php echo $attr['color']; ?>"></div>
                            <?php } ?>
                        </div>
                    </div>
                <?php if (is_login() && $product_data['online'] == 1) { ?>
                    <script>
                        $(document).ready(function () {
                            $('.select-color:nth-child(1)').click();
                        });
                    </script>
                <?php } ?>
                <?php } ?>
                <?php if ($product_data['att_type'] == "size") { ?>
                    <?php if (is_login() && $product_data['online'] == 1) { ?>
                        <strong>เลือกขนาด : </strong><br>
                        <select id="select-size">

                            <?php foreach ($product_attr as $k => $attr) { ?>
                                <option value="<?php echo $attr['code'] . '|' . $attr['normal_price'] . '|' . $attr['in_stock'] . '|' . $attr['special_price'] . '|' . $attr['pa_id'] . '|' . $attr['p_value']. '|' . $attr['p_cover']; ?>"><?php echo $attr['p_value']; ?></option>
                            <?php } ?>
                        </select>
                        <script>
                            $(document).ready(function () {
                                $("#select-size").change();
                            });
                        </script>
                    <?php } else { ?>
                        <strong>รุ่น : </strong><br>
                        <div class="tagcloud">
                            <?php foreach ($product_attr as $k => $attr) { ?>
                                <a><?php echo $attr['p_value']; ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if ($product_data['att_type'] == "model") { ?>
                    <?php if (is_login() && $product_data['online'] == 1) { ?>
                        <strong>เลือกรุ่น : </strong><br>
                        <select id="select-size">

                            <?php foreach ($product_attr as $k => $attr) { ?>
                                <option value="<?php echo $attr['code'] . '|' . $attr['normal_price'] . '|' . $attr['in_stock'] . '|' . $attr['special_price'] . '|' . $attr['pa_id'] . '|' . $attr['p_value']. '|' . $attr['p_cover']; ?>"><?php echo $attr['p_value']; ?></option>
                            <?php } ?>
                        </select>
                        <script>
                            $(document).ready(function () {
                                $("#select-size").change();
                            });
                        </script>
                    <?php } else { ?>
                        <strong>รุ่น : </strong><br>
                        <div class="widget_tag_cloud">
                            <div class="tagcloud">
                                <?php foreach ($product_attr as $k => $attr) { ?>
                                    <a><?php echo $attr['p_value']; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php //} ?>
                <div class="product-price">

                    <?php if (is_login() && $product_data['online'] == 1) { ?>
                        ราคา :
                        <?php if ($product_data['special_price'] > 0) { ?>
                            <span class="default-price"><s><?php echo $product_data['normal_price']; ?></s></span>
                            <span class="special-price"><?php echo $product_data['special_price']; ?></span>
                        <?php } else { ?>
                            <span class="default-price"><?php echo $product_data['normal_price']; ?></span>
                            <span class="special-price"></span>
                        <?php } ?>
                        บาท / ชิ้น
                    <?php } ?>

                </div>
                <?php if ($product_data['online'] == '1' && is_login()) { ?>
                    <div class="product-qty">
                        <input type="number" class="number" min="1" id="qty" value="1">
                    </div>
                    <div class="product-addcart">
                        <button id="add-to-cart" class="btn-big black">ADD TO CART</button>
                    </div>
                <?php } else if(is_login()) { ?>
                    <div id="login-text-red">กรุณาติดต่อ <?php echo $this->setting_data['phone']; ?> เพือเพื่อสอบถามข้อมูลเพิ่มเติมจากเจ้าหน้าที่</div>
                    <?php }else{ ?>
                    <div id="login-text">กรุณา <a href="<?php echo base_url('login'); ?>">เข้าสู่ระบบ</a> เพื่อสั่งซื้อสินค้าหรือดูรายละเอียดราคา</div>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="product-content">
            <div class="inner-wrap">
                <?php echo $product_data['info']; ?>
            </div>
        </div>
    </section>
</section>