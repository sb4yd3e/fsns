<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <div align="center"><h2>My Shopping Carts</h2></div>
            <div id="empty">
                ไม่มีรายการสินค้าในตะกร้าของคุณ
            </div>
            <div id="table-orders">
                <table class="table">
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2">จำนวนทั้งหมดโดยประมาณ</td>
                        <td>
                            <div id="total-simple"></div>
                            <div id="vat-text">รวมภาษีมูลค่าเพิ่ม</div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        re_render();


        function re_render() {
            cal_simpleorder();
            if (Object.size(products) <= 0) {
                $('#table-orders').hide();
                $('#empty').show();
                return false;
            } else {
                $('#table-orders').show();
                $('#empty').hide();
            }
            var total = 0;
            for (var i in products) {
                var html = '<tr id="p-' + i + '"><td><img src="' + products[i]['image'] + '" class="cart-thumb"></td>';
                html += '<td><div class="cart-title">' + products[i]['title'] + '</div>';
                if (products[i]['sp_price'] > 0) {
                    total = total + (products[i]['price'] - products[i]['sp_price']);
                    html += '<div class="cart-spprice">' + products[i]['sp_price'] + '</div>';
                    html += '<div class="cart-price">' + products[i]['price'] + '</div>';
                } else {
                    total = total + products[i]['price'];
                    html += '<div class="cart-price">' + products[i]['price'] + '</div>';
                }
                html += '<div class="cart-qty"><input type="number" min="1" class="number"  data-id="' + i + '" value="' + products[i]['qty'] + '"></div></td>';
                html += '<td><button class="cart-delete" data-id="' + i + '">Delete</button></td></tr>';
                html += '</tr>';

                $('#table-orders tbody').append(html);
            }

        }

        $(document).on('change', '.cart-qty .number', function () {
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
        $(document).on('click', '.cart-delete', function () {
            var id = $(this).data('id');
            update_product('delete', id, null, null);
            setTimeout(function(){
                re_render();
            },500);
        });
    });
</script>