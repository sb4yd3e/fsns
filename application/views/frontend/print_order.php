<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Print</title>
    <style>

        #cart_print {
            font: normal 13px Verdana, Geneva, sans-serif;
            color: #000000;
            margin-top: -7px;
            width: 910px;
            border: 1px solid #b3afaf;
            padding: 2px;
        }

        #cart_print a {
            color: #014993;
            font-weight: normal;
        }

        #cart_print a:hover {
            color: #000000;
            font-weight: normal;
            text-decoration: underline;
        }

        #cart_print h1 {
            color: #014993;
            text-align: left;
            font-size: 20px;
            margin: 0;
            padding: 0;
        }

        #cart_print h2 {
            color: #000000;
            text-align: left;
            margin: 0;
            padding: 0;
        }

        #cart_print .cart_print2 table {
            margin-top: 5px;
            width: 900px;
            border: 0px solid #5a5a5a;
            border-collapse: 5px;
            border-spacing: 0px;
        }

        #cart_print .cart_print2 table tr {
            height: 35px;
        }

        #cart_print .cart_print {
            width: 900px;
        }

        #cart_print .cart_print table {
            width: 100%;
            border: 0px solid #5a5a5a;
            border-collapse: 1px;
            border-spacing: 1px;
        }

        #cart_print .cart_print table tr:first-child {
            background-color: #f0f0f0;
            text-align: center;
            height: 35px;
        }

        #cart_print .cart_print table tr:nth-child(even) {
            text-align: left;
            height: 35px;
            background-color: #efefef;
        }

        #cart_print .cart_print table tr:nth-child(odd) {
            text-align: left;
            height: 35px;
            background-color: #f8f7f7;

        }

        #cart_print .cart_t {
            border-top: 1px solid #cdcdcd;

        }

        #cart_print .cart_r {
            border-right: 1px solid #cdcdcd;

        }

        #cart_print .cart_b {
            border-bottom: 1px solid #cdcdcd;

        }

        #cart_print .cart_l {
            border-left: 1px solid #cdcdcd;

        }

        #cart_print .pay table {
            width: 100%;
            border: 0px solid #5a5a5a;
            border-collapse: 1px;
            border-spacing: 1px;
        }

        #cart_print .pay table tr:first-child {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            height: 35px;
        }

        #cart_print .pay table tr {
            text-align: left;
            height: 25px;
            background-color: #ffffff;
        }

        #cart_print .pay ul {
            padding: 0px;
        }

        #cart_print .pay li {
            list-style: none;
            height: 25px;
        }

        .container_pay {
            font-family: Verdana, Geneva, sans-serif;

            color: #000000;

        }

        .container_pay p {
            font-family: Verdana, Geneva, sans-serif;

        }

        .container_pay strong {
            color: #00538a;
        }

        .container_pay br {
            line-height: 0x;
        }

        #paysbuy {
            display: block;
        }

        #creditcard {
            display: block;
        }

        #counterservice {
            display: block;
        }

        #bank {
            display: block;
        }

        #bank_select {
            display: none;
            clear: both;

        }

        #bank_select ul {
            margin-left: 20px;
            margin-top: 10px;
        }

        #bank_select ul li {
            padding-bottom: 0px;
            margin-bottom: 0px;
            font-weight: normal;
            color: #000;
        }

        #bank_select ul li:hover {
            font-weight: normal;
        }

        #pay_select {

        }

        #pay_select li {
            border: 0px solid red;
            padding-bottom: 60px;
            margin-bottom: 2px;
            background-color: #f6f6f6;
            font-weight: bold;
            color: #b3afaf;
        }

        #pay_select li:hover {
            background-color: #fff;
            color: #014993;
            font-weight: bold;
        }

        .payshow {
            display: block;
        }

        .font_discount {
            color: red;
        }

        .font_total {
            color: blue;
        }

        .font_bold {
            font-weight: bold;
        }

        .font_underline {
            text-decoration: underline
        }

        .line_under {
            border-bottom: 1px solid #e0e0e0;
        }

        #view_pay {
            min-height: 700px;
            padding: 5px;
        }

        .complete_cart_print ul {
            font-weight: bold;
            margin-left: 8px;
            padding: 0;
            list-style: none;
            text-align: left;
        }

        .complete_cart_print li {
            font-weight: normal;
            text-align: left;
            color: #000;
            padding: 5px 0 0 0px;
            margin-left: 20px;
            list-style-type: decimal;
        }

        #cart_print .transport table {
            width: 100%;
            border: 0px solid #5a5a5a;
            border-collapse: 1px;
            border-spacing: 1px;
        }

        #cart_print .transport table tr:first-child {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            height: 35px;
        }

        #cart_print .transport table tr {
            text-align: left;
            height: 25px;
            background-color: #ffffff;
        }

        #cart_print .transport p {
            padding: 0px;
            margin: 0px;
        }

        #cart_print .address {
            margin-top: 10px;
            text-align: left;
        }

        #cart_print .address ul {
            font-weight: bold;
            margin-left: 8px;
            padding: 0;
            list-style: none;
        }

        #cart_print .address ul li {
            font-weight: normal;
            height: 25px;
        }

        #cart_print .address ul li:first-child {
            padding-top: 5px;
        }

        #cart_print .left {
            text-align: left;
        }

        #cart_print .right {
            text-align: right;
        }

        #cart_print .center {
            text-align: center;
        }

        #cart_print table {

            line-height: 150%;
        }

        #cart_print ul {

            padding: 0;
            margin: 0;
        }

        #cart_print li:first-child {
            font-size: 26px;
        }

        #cart_print li {
            list-style: none;
            padding: 2px;
            margin: 0;
            font-weight: normal;
        }

        .clear {
            clear: both;
        }

        @media print {
            #idprint {
                display: none;
            }

        // #idprint {
               display: block;
           }
        }

    </style>

</head>
<body>
<center>
    <div id="cart_print">
        <input type="image" id="idprint" src="<?php echo base_url('img/print_icon.jpg'); ?>" width="43" height="43"
               onClick="window.print();" title="พิมพ์"/>
        <div class="clear"></div>
        <div style="margin: 2px;width: 900px;border: 0px solid #000">

            <div style="text-align: left"><img src="<?php echo base_url('img/logo.png'); ?>" border="0" width="150">
            </div>
            <div>
                <div style="float: left;text-align:left;margin-top: 8px;margin-bottom: 10px;width: 330px;">
                    Food Service and Solution Co.,Ltd<br>
                    29 S.Chalaemnimit,<br>
                    Bangkhlo, Bangkorlaem,<br>
                    Bangkok 10120
                </div>

                <div style="float: left;width: 268px;text-align: right;font-size: 13px;text-align:center;border:0px solid #e0e0e0;font-size:30px; font-weight: bold;">
                    ใบสั่งซื้อสินค้า
                </div>

                <div style="float: left;width: 300px;text-align: right;font-size: 13px;">
                    <div style="float: right;margin-bottom: 10px;border:0px solid #000000;margin-top:-55px;">

                        <div id="bcTargetOrderNumber"
                             style="margin-top:0px;margin-bottom: 0px;float: right;text-align: center;font-size: 20px;">
                            <span id="bcTarget">
                                <?php echo order_status($order['order_status']); ?>
                            </span>
                        </div>
                    </div>
                    <table border="0" style="font-size:9px; width: 100%;">
                        <tr>
                            <td align="right">เลขที่ใบสั่งซื้อ :</td>
                            <td width="150"
                                align="left"><?php echo str_pad($order['oid'], 6, "0", STR_PAD_LEFT); ?></td>
                        </tr>
                        <tr>
                            <td align="right">วันที่สังซื้อ :</td>
                            <td align="left"><?php echo date('d/m/Y H:i:s', $order['at_date']); ?></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="clear"></div>

            <div>
                <div style="float: left;text-align:left;margin-bottom: 5px;margin-right: 2px;width: 494px;min-height: 150px;border:1px solid #e0e0e0;">
                    <table style="width: 100%;" border="0" height="150">
                        <tr>
                            <td colspan="2" style="font-weight: bold;background-color:#e0e0e0;height: 25px;">
                                ที่อยู่ในการจัดส่งสินค้า
                            </td>
                        </tr>
                        <tr>
                            <td width="100">ชื่อ-นามสกุล</td>
                            <td><?php echo $order['shipping_name']; ?></td>
                        </tr>
                        <tr valign="top">
                            <td>ที่อยู่</td>
                            <td>
                                <?php echo nl2br($order['shipping_address']); ?><br>
                                <?php echo $order['shipping_province']; ?><br>
                                <?php echo $order['shipping_zip']; ?>
                            </td>
                        </tr>

                    </table>
                </div>

                <div style="float: left;width: 400px;text-align: left;border:1px solid #e0e0e0;min-height: 150px;">
                    <table style="width: 100%;" border="0" height="150">
                        <tr>
                            <td colspan="2" style="font-weight: bold;background-color:#e0e0e0;height: 25px;">
                                ที่อยู่ใบกำกับภาษี
                            </td>
                        </tr>

                        <tr>
                            <td width="100">ชื่อ-นามสกุล</td>
                            <td><?php echo $order['shipping_name']; ?></td>
                        </tr>
                        <tr valign="top">
                            <td>ที่อยู่</td>
                            <td>
                                <?php echo nl2br($order['shipping_address']); ?><br>
                                <?php echo $order['shipping_province']; ?><br>
                                <?php echo $order['shipping_zip']; ?>
                            </td>
                        </tr>

                    </table>
                </div>

            </div>

            <div class="clear"></div>
        </div>
        <div class="clear"></div>

        <div class="cart_print">

            <table style="border:1px solid #e0e0e0;margin: 0px;width: 900px;" border="0">
                <tr style="background-color:#e0e0e0;font-weight: bold;text-align: center">
                    <td width='50' class="cart_t cart_r cart_l">ลำดับ</td>
                    <td width='100' class="cart_t cart_r cart_l">รหัสสินค้า</td>
                    <td class="cart_t cart_r">รายการสินค้า</td>
                    <td width='50' class="cart_t cart_r">จำนวน</td>
                    <td width='120' class="cart_t cart_r">ราคา / หน่วย</td>
                    <td width='50' class="cart_t cart_r">ส่วนลด</td>
                    <td width='100' class="cart_t cart_r">จำนวนเงินรวม</td>
                </tr>
                <?php
                $total = 0;
                foreach ($products as $k => $product) { ?>
                    <tr>
                        <td align="center"><?php echo $k+1; ?></td>
                        <td class="cart_t cart_r cart_l"><?php echo $product['product_code']; ?></td>
                        <td class="cart_t cart_r"><?php echo $product['product_title'].' - '.$product['product_value']; ?></td>
                        <td class="center cart_t cart_r" style="font-weight: bold;"><?php echo number_format($product['product_qty']); ?></td>
                        <td class="right cart_t cart_r "><?php echo number_format($product['product_amount'], 2); ?></td>
                        <td class="right font_discount cart_t cart_r">
                            <?php if($product['product_spacial_amount'] > 0){
                                $total = $total + $product['product_spacial_amount'];
                                echo number_format(($product['product_amount'] - $product['product_spacial_amount']) * $product['product_qty'], 2);
                            }else{
                                $total = $total + $product['product_amount'];
                            }; ?></td>
                        <td class="right cart_t cart_r"><?php echo number_format($product['total_amount'], 2); ?></td>
                    </tr>
                <?php } ?>
                <tr style="background-color: #fff;   height:35px;">
                    <td colspan="5" class="right font_bold cart_t cart_r cart_l cart_b" style="text-align:right">
                        รวมราคาสินค้า (บาท)
                    </td>
                    <td class="right font_bold font_discount cart_t  cart_b"><?php echo number_format($order['spacial_amount'], 2); ?></td>
                    <td class="right font_bold font_total cart_t cart_r cart_l cart_b"><?php echo number_format($total, 2); ?></td>
                </tr>



                <tr style="background-color: #fff;   height:35px;">
                    <td colspan="4"></td>
                    <td colspan="2" class="line_under right font_bold">คูปองส่วนลด (บาท)</td>
                    <td class="right line_under font_bold"><?php echo number_format(((($order['amount'] - $order['spacial_amount']) / 100) * $order['discount']) + $order['discount_100k'], 2); ?></td>
                </tr>
                <tr style="background-color: #fff;   height:35px;">
                    <td colspan="4"></td>
                    <td colspan="2" class="line_under right font_bold">ภาษีมูลค่าเพิ่ม 7% (บาท)</td>
                    <td class="right line_under font_bold"><?php echo number_format($order['vat_amount'], 2); ?></td>
                </tr>
                <tr style="background-color: #fff;   height:35px;">
                    <td colspan="4"></td>
                    <td colspan="2" class="line_under right font_bold">ค่าจัดส่ง (บาท)</td>
                    <td class="right line_under font_bold"><?php echo number_format($order['shipping_amount'], 2); ?></td>
                </tr>

                <tr style="background-color: #fff;   height:35px;">
                    <td colspan="4"></td>
                    <td colspan="2" class="line_under right font_bold">รวมเป็นเงินทั้งสิ้น</td>
                    <td class="right font_total line_under font_underline font_bold"><?php echo number_format($order['total_amount'], 2); ?></td>
                </tr>
            </table>

        </div>


        <div style="text-align: left;margin:10px 0 5px 5px;">
        </div>

        <div style="margin-top: 10px;width: 900px; border:0px solid #e0e0e0;">
            <table border="0" cellpadding="0" cellspacing="1"
                   style="border:1px solid #e0e0e0;margin: 0px;width: 900px;">
                <tr>
                    <td>
                        <div id="creditcard" class="container_pay" style="padding:5px;">
                            <div style="background-color:#dad9d9;line-height:30px;text-align:center; vertical-align: middle;font-size:15px;font-weight:bold;">
                                วิธีการชำระเงินค่าสินค้า
                            </div>
                            <div style="font-size:15px;font-weight:bold;margin-top:15px;">
                                ยังไม่มีข้อมูล
                            </div>
                            <div>
                                <div style="width:700px;text-align:left;margin-left:20px;">
                                    xx
                                </div>

                                <div style="clear:both;"></div>
                            </div>
                            <div style="clear:both;"></div>

                            <div style="background-color:#dad9d9;line-height:30px;text-align:center; vertical-align: middle;font-size:15px;font-weight:bold;">
                                สอบถามข้อมูลเพิ่มเติม
                            </div>
                            <div style="font-size:13px;margin-top:15px;">
                                <p>Tel: 083-839-2929, 081-615-2621<br>
                                Fax: 02-6885755<br>
                                Email : contact@fsns-thailand.com</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</center>
</body>
</html>
