<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">

            <div class="message_payment">
                <div align="center"><strong>ขอขอบคุณสำหรับคำสั่งซื้อสินค้า</strong></div>
                <div align="center" style="margin-top: 20px;">
                     หมายเลขการสั่งซื้อสินค้าของท่านคือ #<?php echo str_pad($this->input->get('id'), 6, "0", STR_PAD_LEFT); ?> ท่านสามารถตรวจสอบรายละเอียดการสั่งซื้อได้ที่ Email ที่ลงทะเบียนไว้<br>
                    หรือดูรายละเอียดการสั่งซื้อของท่านได้ที่เมนู Member -> My Orders (<a href="<?php echo base_url('my-orders'); ?>">หรือกดที่นี่</a>)
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
                                    <?php
                                    $pay = payment_list();
                                    echo $pay[$pv['bank_name']]; ?><br>
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