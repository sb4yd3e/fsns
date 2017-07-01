<section>
    <section id="product-detail">

        <div id="membber-form" class="wpcf7">
            <div class="col-6" id="div-login">
                <div align="center"><h2>แจ้งชำระค่าสินค้า</h2></div>
                <form action="" method="post" enctype="multipart/form-data" id="confirm-form">
                    <fieldset>
                        <label>Order Number</label>
                        <input type="text" class="wpcf7-text"
                               value="<?php echo str_pad($order['oid'], 6, "0", STR_PAD_LEFT); ?>" maxlength="100"
                               readonly>
                    </fieldset>

                    <fieldset>
                        <label>ชื่อ นามสกุล</label>
                        <input type="text" name="name" value="<?php echo $user_data['name']; ?>" class="wpcf7-text"
                               maxlength="100" required>
                    </fieldset>
                    <fieldset>
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $user_data['email']; ?>" class="wpcf7-text"
                               maxlength="100" required>
                    </fieldset>
                    <fieldset>
                        <label>เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" value="<?php echo $user_data['phone']; ?>" class="wpcf7-text"
                               maxlength="100" required>
                    </fieldset>
                    <fieldset>
                        <label>จำนวนเงินที่ชำระ</label>
                        <input type="text" name="amount" value="<?php echo $order['total_amount']; ?>"
                               class="wpcf7-text digi"
                               maxlength="100" required>
                    </fieldset>
                    <fieldset>
                        <label>วัน เวลาที่ชำระเงิน</label>
                        <input type="text" name="date" value="<?php echo date('d/m/Y H:i:s'); ?>" class="wpcf7-text"
                               maxlength="100" required>
                    </fieldset>
                    <fieldset>
                        <label>ช่องทางการชำระเงิน</label>
                        <?php echo form_dropdown('gateway',$payments,'','class="wpcf7-text" style="width: calc(100% - 40px);" required'); ?>

                    </fieldset>
                    <fieldset>
                        <label>หลักฐานการชำระ</label>
                        <input type="file" name="slip" accept="image/*,.pdf,.doc" class="wpcf7-text">
                    </fieldset>
                    <fieldset>
                        <label>หมายเหตุ</label>
                        <textarea name="note" class="wpcf7-textarea" rows="4" maxlength="500"></textarea>
                    </fieldset>

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button class="btn-medium black" type="submit" id="submit-confirm">ยืนยันการชำระเงิน</button>

                </form>
            </div>

            <div class="clearfix"></div>


        </div>
    </section>
</section>
<script>

    $(document).ready(function () {
        var options_confirm = {
            beforeSubmit: showRequest_confirm,
            success: showResponse_confirm
        };
        $('#confirm-form').ajaxForm(options_confirm);
    });

    function showRequest_confirm() {
        $('#submit-confirm').html('Loading...').attr('disabled', 'disabled');
        return true;
    }

    function showResponse_confirm(responseText) {
        var obj = jQuery.parseJSON(responseText);
        if (obj.status === 'success') {
            swal({
                    title: "Save success",
                    text: "แจ้งชำระค่าสินค้าเรียบร้อย",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "ตกลง",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        window.location = '<?php echo base_url('my-orders'); ?>';
                    }
                });
        } else {
            swal({
                title: "Error",
                type: "error",
                text: obj.message,
                html: true
            });
        }
        $('#submit-confirm').html('ยืนยันการชำระเงิน').removeAttr('disabled');

    }
</script>