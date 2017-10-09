<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form" style="min-height: 400px;">
            <div align="center"><h2>ตะกร้าสินค้า</h2></div>
            <section class="infobox" id="empty">
                <p>ไม่มีสินค้าในรถเข็น</p>
            </section>
            <div class="shopping-cart" id="table-orders">

                <div class="column-labels">
                    <span class="product-image">&nbbsp;</span>
                    <span class="product-details">สินค้า</span>
                    <span class="product-price">ราคา(บาท)</span>
                    <span class="product-quantity">จำนวน</span>
                    <span class="product-removal">&nbbsp;</span>
                    <span class="product-line-price">รวม(บาท)</span>
                </div>
                <div id="order-list">


                </div>
                <div class="totals">
                    <div class="totals-item">
                        <span>ราคารวมภาษีมูลค่าเพิ่ม</span>
                        <div class="totals-value" id="total-simple">0</div>
                    </div>
                </div>

                <a href="<?php echo base_url('checkout/delivery-info'); ?>" class="wpcf7-submit" style="float: right;">Checkout</a>

            </div>


        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        re_render();


        function re_render() {
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
                var t = 0;
                var html = '<div class="product" id="p-' + i + '"><div class="product-image"><img src="' + products[i]['image'] + '"></div><div class="product-details">';
                html += '<div class="product-title">' + products[i]['title'] +'</div><p class="product-description">' + products[i]['code'] + ' - ' + products[i]['value'] + ' </p></div><div class="product-price">';
                if (products[i]['sp_price'] > 0) {
                    html += '<div class="cart-price"><s>' + products[i]['price'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</s></div>';
                    html += '<div class="cart-spprice">' + products[i]['sp_price'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</div>';
                    t = products[i]['sp_price'] * products[i]['qty'];
                } else {
                    t = products[i]['price'] * products[i]['qty'];
                    html += '<div class="cart-price">' + products[i]['price'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</div>';
                }
                var msg_minimum = '';
                if(products[i]['minimum'] > 1){
                    msg_minimum = '*ขั้นต่ำ '+products[i]['minimum']+' หน่วย';
                }
                html += '</div><div class="product-quantity"><input type="number" class="wpcf7-text number" min="' + products[i]['minimum'] + '" data-id="' + i + '" value="' + products[i]['qty'] + '">'+msg_minimum+'</div>';
                html += '<div class="product-removal"><button class="remove-product"  data-id="' + i + '">ลบ</button></div><div  id="price-' + i + '" class="product-line-price" >' + t.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</div></div>';

                $('#order-list').append(html);
            }

        }

        $(document).on('change', '.product-quantity .number', function () {
            var num = $(this).val();
            var id = $(this).data('id');
            if (num.length <= 0) {
                num = 1;
            } else {
                num = parseInt(num);
            }
            if (num < parseInt($(this).attr('min'))) {
                num = parseInt($(this).attr('min'));
                $(this).val(num);
            }
            update_product('edit', id, 'qty', num);
            cal_simpleorder();
        });
        $(document).on('click', '.remove-product', function () {
            $(this).html('กำลังลบ...');
            var id = $(this).data('id');
            update_product('delete', id, null, null);
            setTimeout(function () {
                re_render();
            }, 1000);
        });
    });
</script>