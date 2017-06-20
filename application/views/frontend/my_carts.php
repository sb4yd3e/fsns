<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <div align="center"><h2>My Shopping Carts</h2></div>
            <div id="empty">
                ไม่มีสินค้าในรถเข็น
            </div>


            <div class="shopping-cart" id="table-orders">

                <div class="column-labels">
                    <span class="product-image">Image</span>
                    <span class="product-details">Product</span>
                    <span class="product-price">Price</span>
                    <span class="product-quantity">Quantity</span>
                    <span class="product-removal">Remove</span>
                    <span class="product-line-price">Total</span>
                </div>
                <div id="order-list">


                </div>
                <div class="totals">
                    <div class="totals-item">
                        <span>จำนวนทั้งหมดโดยประมาณ<br>(รวมภาษีมูลค่าเพิ่ม)</span>
                        <div class="totals-value" id="total-simple">71.97</div>
                    </div>
                </div>

                <a href="<?php echo base_url('checkout/delivery-info'); ?>" class="checkout">Checkout</a>

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
                html += '<div class="product-title">' + '['+products[i]['code'] + '] ' + products[i]['title'] + ' - ' + products[i]['value'] + '</div><p class="product-description"></p></div><div class="product-price">';
                if (products[i]['sp_price'] > 0) {

                    html += '<div class="cart-spprice"> ราคาพิเศษ : ' + products[i]['sp_price'] + '</div>';
                    html += '<div class="cart-price">ราคา : <s>' + products[i]['price'] + '</s></div>';
                    t = products[i]['sp_price'] * products[i]['qty'];
                } else {
                    t =  products[i]['price'] * products[i]['qty'];
                    html += '<div class="cart-price">ราคา : ' + products[i]['price'] + '</div>';
                }

                html += '</div><div class="product-quantity"><input type="number" class="number" min="1" data-id="' + i + '" value="' + products[i]['qty'] + '"></div>';
                html += '<div class="product-removal"><button class="remove-product"  data-id="' + i + '">ลบ</button></div><div  id="price-' + i + '" class="product-line-price" >' + t.toFixed(2) + '</div> </div> ';


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