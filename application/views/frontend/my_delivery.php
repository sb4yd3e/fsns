<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <div align="center"><h2>My Delivery Information</h2></div>
            <div id="empty">
                ไม่มีสินค้าในรถเข็น
            </div>
            <div id="shiping_address">
                <div class="col-6">
                    <a href="<?php echo base_url('shopping-carts'); ?>" id="back-btn">< Back to carts</a>
                    <form method="post" action="<?php echo base_url('checkout/delivery-info'); ?>" id="shipping-form">
                        <div>
                            <label> Name</label>
                            <input type="text" name="shipping_name" class="input" placeholder="Shipping Name"
                                   maxlength="200" value="<?php echo $shipping['shipping_name']; ?>" required>
                        </div>

                        <div>
                            <label> Address</label>
                            <textarea name="shipping_address" class="input" placeholder="Shipping Address" rows="4"
                                      required><?php echo $shipping['shipping_address']; ?></textarea>
                        </div>
                        <div>
                            <label> Province</label>
                            <?php echo form_dropdown('shipping_province', list_province(), $shipping['shipping_province'], 'class="input" style="width: calc(100% - 40px);height: 34px;" required'); ?>
                        </div>
                        <div>
                            <label> Zip</label>
                            <input type="text" name="shipping_zip" id="shipping_zip" class="input number"
                                   placeholder="Zip code"
                                   maxlength="5"
                                   value="<?php echo $shipping['shipping_zip']; ?>" minlength="5" required>
                        </div>


                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                               value="<?php echo $this->security->get_csrf_hash(); ?>">

                        <button class="btn" type="submit" id="submit-shipping">Continue to checkout</button>
                    </form>
                </div>
                <div class="col-6 hide-mobile">
                    <div id="shoping-detail">
                        <div id="head-table">สรุปการสั่งซื้อ</div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>สินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคา</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>

                            <tr id="summery">
                                <td colspan="2">มูลค่าสินค้า</td>
                                <td>0</td>
                            </tr>
                            <tr id="shipping">
                                <td colspan="2">ค่าจัดส่ง</td>
                                <td>0</td>
                            </tr>
                            <tr id="amount">
                                <td colspan="2">ยอดสุทธิ<span>(รวมภาษีมูลค่าเพิ่ม)</span></td>
                                <td>0</td>
                            </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </section>
</section>

<script>
    $(document).ready(function () {
        if (Object.size(products) <= 0) {
            $('#empty').show();
            swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "ไม่มีสินค้าในรถเข็น กรุณาเลือกสินค้าก่อน",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonText: "ตกลง",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        window.location = '/';
                    }
                });
        } else {
            $('#empty').remove();
            var options_shipping = {
                beforeSubmit: showRequest_shipping,
                success: showResponse_shipping
            };
            $('#shipping-form').ajaxForm(options_shipping);
            var xip = '<?php echo $this->setting_data['shipping_zip']; ?>';
            var zip = xip.split(',');
            var summery = 0;
            var shipping = 0;
            if (zip.indexOf($('#shipping_zip').val()) !== -1) {
                shipping = parseInt('<?php echo $this->setting_data['shipping_inarea']; ?>');
            } else {
                shipping = parseInt('<?php echo $this->setting_data['shipping_outarea']; ?>');
            }
            $('#shipping td:nth-child(2)').html(shipping.toFixed(2));
            for (var i in products) {
                var t = 0;
                var html = '';
                html += '';
                if (products[i]['sp_price'] > 0) {
                    html += '<tr><td>' + products[i]['title'] + '</td><td>' + products[i]['qty'] + '</td><td><s>' + products[i]['price'] + '</s><br>' + products[i]['sp_price'] + '</td></tr>';
                    t = products[i]['sp_price'] * products[i]['qty'];
                    summery = summery + t;
                } else {
                    t = products[i]['price'] * products[i]['qty'];
                    summery = summery + t;
                    html += '<tr><td>' + products[i]['title'] + '</td><td>' + products[i]['qty'] + '</td><td>' + products[i]['price'] + '</td></tr>';
                }

                $('#shoping-detail tbody').append(html);

            }
            $('#summery td:nth-child(2)').html(summery.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#amount td:nth-child(2)').html((((summery / 100) * 7) + shipping + summery).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#shipping_zip').change(function () {
                if (zip.indexOf($(this).val()) !== -1) {
                    shipping = parseInt('<?php echo $this->setting_data['shipping_inarea']; ?>');
                } else {
                    shipping = parseInt('<?php echo $this->setting_data['shipping_outarea']; ?>');
                }
                $('#shipping td:nth-child(2)').html(shipping.toFixed(2));
                $('#amount td:nth-child(2)').html((((summery / 100) * 7) + shipping + summery).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            });
        }
    });

    function showRequest_shipping() {
        $('#submit-shipping').html('Loading...').attr('disabled', 'disabled');
        return true;
    }

    function showResponse_shipping(responseText) {
        var obj = jQuery.parseJSON(responseText);
        if (obj.status === 'success') {
            window.location = '<?php echo base_url('checkout/payment'); ?>';
        } else {
            swal({
                title: "Warning!",
                text: obj.message,
                html: true
            });
            grecaptcha.reset();
            $('#submit-shipping').html('Continue to checkout').removeAttr('disabled');
        }

    }
</script>