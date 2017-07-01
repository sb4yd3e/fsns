<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">

            <div class="message_payment">
                <div align="center"><strong>ขอบคุณที่สั่งซื้อสินค้ากับเรา</strong></div>
                <div align="center" style="margin-top: 20px;">
                    คุณจะได้รับอีเมล์ยืนยันการสั่งซื้อพร้อมมรายละเอียดการสั่งซื้อสินค้า
                </div>
                <div style="padding: 20px;" align="left">
                    <?php if ($type == 'business') {
                        foreach ($payments as $pv) {
                            if ($type == 'business' && $pv['type'] == 'business') { ?>
                                <div align="left"><strong><?php echo $pv['title']; ?></strong></div><br>
                                <div class="bank-info">
                                    <?php echo $pv['detail']; ?>
                                </div>
                                <?php
                            }
                        }
                    } else { ?>
                        <div align="left"><strong>ช่องทางการชำระค่าสินค้า</strong></div><br>
                        <?php
                        foreach ($payments as $pv) {
                            if ($type == 'personal' && $pv['type'] == 'personal') { ?>

                                <div class="bank-info">
                                    <?php echo payment_list()[$pv['bank_name']]; ?><br>
                                    ชื่อบัญชี : <?php echo $pv['title']; ?><br>
                                    เลขที่บัญชี : <?php echo $pv['bank_acc']; ?><br>
                                    ประเภท : <?php echo $pv['bank_type']; ?><br>
                                    สาขา : <?php echo $pv['bank_branch']; ?><br>
                                </div>

                            <?php }
                        } ?>

                    <?php } ?>
                </div>
            </div>

        </div>
    </section>
</section>