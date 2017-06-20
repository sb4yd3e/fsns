<section>
    <section id="product-detail">

        <div id="membber-form">
            <div class="col-6" id="div-login">
                <div align="center"><h2>แจ้งชำระค่าสินค้า</h2></div>
                <form action="" method="post" enctype="multipart/form-data" id="confirm-form">
                    <div>
                        <label>Order Number</label>
                        <input type="text" class="input"
                               value="<?php echo str_pad($order['oid'], 6, "0", STR_PAD_LEFT); ?>" maxlength="100"
                               readonly>
                    </div>

                    <div>
                        <label>ชื่อ นามสกุล</label>
                        <input type="text" name="name" value="<?php echo $user_data['name']; ?>" class="input"
                               maxlength="100" required>
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $user_data['email']; ?>" class="input"
                               maxlength="100" required>
                    </div>
                    <div>
                        <label>เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" value="<?php echo $user_data['phone']; ?>" class="input"
                               maxlength="100" required>
                    </div>
                    <div>
                        <label>จำนวนเงินที่ชำระ</label>
                        <input type="text" name="amount" value="<?php echo $order['total_amount']; ?>"
                               class="input digi"
                               maxlength="100" required>
                    </div>
                    <div>
                        <label>วัน เวลาที่ชำระเงิน</label>
                        <input type="text" name="date" value="<?php echo date('d/m/Y H:i:s'); ?>" class="input"
                               maxlength="100" required>
                    </div>
                    <div>
                        <label>ช่องทางการชำระเงิน</label>
                        <select name="gateway" class="input" style="width: calc(100% - 40px);" required>
                            <option value="bank1">โอนเงินผ่านธนาคารอะไรซักอย่าง</option>
                        </select>
                    </div>
                    <div>
                        <label>หลักฐานการชำระ</label>
                        <input type="file" name="slip" accept="image/*,.pdf,.doc" class="input" required>
                    </div>
                    <div>
                        <label>หมายเหตุ</label>
                        <textarea name="note" class="input" rows="4" maxlength="500"></textarea>
                    </div>

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button class="btn" type="submit" id="submit-confirm">ยืนยันการชำระเงิน</button>

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