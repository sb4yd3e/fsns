<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <h3>รายการสั่งซื้อหมายเลข : <?php echo str_pad($order['oid'], 6, "0", STR_PAD_LEFT); ?></h3>
            <?php foreach ($timelines as $time) {

                switch ($time['process_type']) {
                    case 'status':
                        ?>
                        <div class="timeline">
                            <div class="title"><span><?php echo date('d/m/Y H:i:s', $time['at_date']); ?></span>
                                | <?php echo order_status($time['process_title']); ?></div>
                            <div class="detail"><?php echo nl2br($time['process_detail']); ?></div>
                        </div>
                        <?php
                        break;

                    case 'shipping':
                        ?>
                        <div class="timeline">
                            <div class="title"><span><?php echo date('d/m/Y H:i:s', $time['at_date']); ?></span>
                                | <?php echo $time['process_title']; ?></div>
                            <div class="detail"><?php echo nl2br($time['process_detail']); ?></div>
                        </div>
                        <?php
                        break;
                    case 'shipping_all':
                        ?>
                        <div class="timeline">
                            <div class="title"><span><?php echo date('d/m/Y H:i:s', $time['at_date']); ?></span>
                                | <?php echo $time['process_title']; ?></div>
                            <div class="detail"><?php echo nl2br($time['process_detail']); ?></div>
                        </div>
                        <?php
                        break;
                    case 'shipping_list':
                        ?>
                        <div class="timeline">
                            <div class="title"><span><?php echo date('d/m/Y H:i:s', $time['at_date']); ?></span>
                                | <?php echo $time['process_title']; ?></div>
                            <div class="detail"><?php echo get_product_by_oid($time['process_detail']); ?></div>
                        </div>
                        <?php
                        break;
                    case 'document':
                        ?>
                        <div class="timeline">
                            <div class="title"><span><?php echo date('d/m/Y H:i:s', $time['at_date']); ?></span>
                                | เอกสาร : <?php echo $time['process_title']; ?></div>
                            <div class="detail"><?php echo front_end_list_document($time['process_detail']); ?></div>
                        </div>
                        <?php
                        break;
                    case 'edit_order':

                        break;
                }
            } ?>
        </div>
        <?php if ($order['order_status'] == 'confirmed') { ?>
            <a href="<?php echo base_url('order/confirm-payment/' . $order['oid']); ?>" class="btn btn-success" id="btn-confirm-payment">แจ้งชำระค่าสินค้า/อัพโหลดเอกสาร</a>
        <?php } ?>
    </section>
</section>
