<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <!--            <div><h2>Payment method</h2></div>-->

            <a href="<?php echo base_url('checkout/delivery-info'); ?>" id="back-btn">< Back to delivery information</a>
            <div class="shopping-cart" id="table-orders">

                <div class="column-labels">
                    <span class="product-details wd">รายการสินค้า</span>
                    <span class="product-price wd">ราคา/หน่วย</span>
                    <span class="product-quantity wd">จำนวน</span>
                    <span class="product-line-price wd">รวม(บาท)</span>
                </div>
                <div id="order-list">


                </div>
                <div class="totals">
                    <div class="totals-item">
                        <span>รวมราคาสินค้า</span>
                        <div class="totals-value" id="amount">0</div>
                    </div>
                    <div class="totals-item" id="coupon-box">
                        <span>รหัสส่วนลด
                        <input type="text" class="wpcf7-text" maxlength="20" placeholder="Please enter coupon code"
                               id="coupon"></span>
                        <div class="totals-value" id="amount"></div>
                    </div>
                    <div class="totals-item">
                        <span><strong>ส่วนลด</strong></span><br>
                        <div id="discount-result"></div>
                        <div class="totals-value" id="discount">0</div>
                    </div>
                    <div class="totals-item">
                        <span><strong>ยอดรวมก่อนภาษี</strong></span>
                        <div class="totals-value" id="total-before">0</div>
                    </div>
                    <div class="totals-item">
                        <span><strong>ภาษีมูลค่าเพิ่ม (7%)</strong></span>
                        <div class="totals-value" id="vat">0</div>
                    </div>
                    <div class="totals-item">
                        <span><strong>รวมเป็นเงิน</strong></span>
                        <div class="totals-value" id="before_shiping">0</div>
                    </div>
                    <div class="totals-item">
                        <span><strong>ค่าส่งสินค้า</strong></span>
                        <div class="totals-value" id="shipping">0</div>
                    </div>

                    <div class="totals-item">
                        <span><strong>รวมเป็นเงินที่ต้องชำระ</strong></span>
                        <div class="totals-value" id="total">0</div>
                    </div>
                    <div class="totals-item">
                        <span><strong>หมายเหตุ</strong></span>
                        <textarea class="wpcf7-textarea" rows="4" id="note" maxlength="1000"></textarea>
                    </div>
                </div>

                <div id="payment-method">

                </div>
                <button id="submit-order" class="btn-big black" style="float: right;">Confirm order</button>

            </div>


        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        var rs_no_discount = 0; //ราคารวมทั้งหมด
        var rs_sp_discount = 0; //ส่วนลดพิเศษ
        var rs_total_sp_discount = 0; //รวมราคาพิเศษ
        var rs_total_before = 0; //ราคารวมก่อนภาษี
        var rs_discount_code = "";//code coupon
        var rs_discount_code_value = 0; //ส่วนลดจากคูปอง
        var rs_discount_code_amount = 0; //รวมส่วนลดจากคูปอง
        var rs_discount_10k = 0;//ส่วนลดซื้อเกิน 1000000
        var rs_vat = 0; //ภาษี
        var rs_total = 0; //รวมราคาทั้งหมด
        var rs_shipping = 0; //ค่าขนส่ง
        var rs_total_discount = 0; //รวมส่วนลดทั้งหมด
        var rs_before_shiping = 0; //รวมส่วนลดทั้งหมด


        var zip = '<?php echo $this->setting_data['shipping_zip']; ?>'.split(',');

        if (zip.indexOf('<?php echo $shipping['shipping_zip']; ?>') !== -1) {
            rs_shipping = parseInt('<?php echo $this->setting_data['shipping_inarea']; ?>');
        } else {
            rs_shipping = parseInt('<?php echo $this->setting_data['shipping_outarea']; ?>');
        }

        $('#order-list').html('');
        cal_simpleorder();
        if (Object.size(products) <= 0) {
            $('#table-orders').hide();
            $('#empty').show();
            return false;
        } else {
            $('#table-orders').show();
            $('#empty').hide();
        }
        for (var i in products) {
            var rs_t = 0, rs_t_no = 0;
            var html = '<div class="product wd" id="p-' + i + '"><div class="product-details wd">';
            html += '<div class="product-title wd"><strong>' + products[i]['title'] + '</strong> <br><span class="light">' + products[i]['code'] + ' ' + products[i]['value'] + '</span></div></div><div class="product-price wd">';
            html += '<div class="cart-price wd">' + (products[i]['price']).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</div>';
            if (products[i]['sp_price'] > 0) {
                rs_t = products[i]['sp_price'] * products[i]['qty'];
                rs_sp_discount = rs_sp_discount + ((products[i]['price'] - products[i]['sp_price']) * products[i]['qty']);
            } else {
                rs_t = products[i]['price'] * products[i]['qty'];
            }
            rs_t_no = rs_t_no + (products[i]['price'] * products[i]['qty']);

            rs_no_discount = rs_no_discount + (products[i]['price'] * products[i]['qty']);
            rs_total_sp_discount = rs_total_sp_discount + rs_t;

            html += '</div><div class="product-quantity wd">' + (products[i]['qty']) + '</div>';
            html += '<div  id="price-' + i + '" class="product-line-price wd">' + rs_t_no.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</div> </div> ';
            $('#order-list').append(html);
        }
        cal_order();

        $('#coupon').change(function () {
            var code = $(this).val();
            if (code.length > 0) {
                $.ajax({
                    type: 'POST',
                    data: {code: code},
                    url: "<?php echo base_url('get-coupon'); ?>"
                }).done(function (status) {
                    var obj = jQuery.parseJSON(status);
                    if (obj.status === "error") {
                        $('#coupon').val('');
                        swal({
                            title: "ผิดพลาด",
                            type: "error",
                            text: "ไม่พบรหัสส่วนลดนี้หรือหมดอายุไปแล้ว",
                            html: true
                        });
                    } else {
                        rs_discount_code_value = obj['discount'];
                        rs_discount_code = obj['code'];
                        cal_order();
                    }
                });
            }
        });

        function cal_order() {
            //========= discount
            var tmp_10k = 0, tmp_discount = 0;

            if (rs_total_sp_discount >= 100000) {
                tmp_10k = (rs_total_sp_discount / 100) * 5;
            }
            if (rs_discount_code_value > 0) {
                tmp_discount = (rs_total_sp_discount / 100) * rs_discount_code_value;
            }

            if (tmp_10k > tmp_discount) {
                rs_discount_10k = tmp_10k;
                rs_discount_code_amount = 0;
            } else if (tmp_10k < tmp_discount) {
                rs_discount_10k = 0;
                rs_discount_code_amount = tmp_discount;
            } else if (tmp_10k === tmp_discount) {
                rs_discount_10k = 0;
                rs_discount_code_amount = tmp_discount;
            } else {
                rs_discount_10k = 0;
                rs_discount_code_amount = 0;
            }
            //========= discount
            rs_total_discount = rs_sp_discount + rs_discount_code_amount + rs_discount_10k;
            rs_total_before = rs_total_sp_discount - rs_discount_code_amount - rs_discount_10k;

            rs_vat = (rs_total_before / 100) * 7;
            rs_total = rs_total_before + rs_shipping + rs_vat;


            if (rs_discount_code_amount > 0) {
                $('#discount-result').html('- รหัสส่วนลด ' + rs_discount_code_value + '% [' + rs_discount_code + '] ลดรวม (' + rs_discount_code_amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + ' บาท)<br>');
            } else if (rs_discount_10k > 0) {
                $('#discount-result').html('- ซื้อครบ 100,000 บาทลด 5 % ลดรวม ( ' + rs_discount_10k.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + 'บาท)<br>');
            }
            if (rs_sp_discount > 0) {
                $('#discount-result').append('- ราคาพิเศษ ลดรวม (' + rs_sp_discount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + ' บาท)');
            }
            rs_before_shiping = rs_total - rs_shipping;

            $('#total-before').html(rs_total_before.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#discount').html(rs_total_discount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#shipping').html(rs_shipping.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#amount').html(rs_no_discount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#vat').html(rs_vat.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#total').html(rs_total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('#before_shiping').html(rs_before_shiping.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
        }

        $('#submit-order').click(function () {
            $(this).html('loading...').attr('disabled', 'disabled');
            if (Object.size(products) <= 0) {
                swal({
                    title: "ผิดพลาด",
                    type: "error",
                    text: "ไม่สามารถดำเนินการสั่งซื้อได้ กรุณาทำรายการใหม่อีกครั้ง",
                    html: true
                });
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    data: {coupon_code: rs_discount_code,note:$('#note').val(), products: JSON.stringify(products)},
                    url: "<?php echo base_url('/checkout/confirm-order'); ?>"
                }).done(function (status) {
                    var obj = jQuery.parseJSON(status);
                    if (obj.status === "error") {
                        $('#coupon').val('');
                        swal({
                            title: "ผิดพลาด",
                            type: "error",
                            text: "ไม่สามารถดำเนินการสั่งซื้อได้ กรุณาทำรายการใหม่อีกครั้ง",
                            html: true
                        });
                    } else {
                        localStorage.setItem('products', JSON.stringify({}));
                        window.location = "<?php echo base_url('checkout/confirm-order?id='); ?>" + obj.id;
                    }
                });
                return false;
            }
        });
    });
</script>