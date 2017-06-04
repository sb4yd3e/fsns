<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Order #<?php echo sprintf("%06d", $data['oid']); ?></h3>

            </div>

            <div class="box-body">
                <?php echo validation_errors(); ?>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#info" aria-controls="home" role="tab" data-toggle="tab">Order info</a>
                    </li>
                    <li role="presentation">
                        <a href="#shping" aria-controls="shping" role="tab" data-toggle="tab" id="tab-shping"
                           class="disabled">Shiping</a>
                    </li>
                    <li role="presentation">
                        <a href="#document" aria-controls="document" role="tab" data-toggle="tab" id="tab-document"
                           class="disabled">Documents</a>
                    </li>

                    <li role="presentation">
                        <a href="#logs" aria-controls="bussiness" role="tab" data-toggle="tab" id="tab-logs">Logs</a>
                    </li>
                </ul>
                <div class="tab-content" style="margin-top: 20px;">
                    <div role="tabpanel" class="tab-pane fade in active" id="info">
                        <label for="">Order Status</label>
                        <div class="clearfix"></div>
                        <form action="<?php echo base_url('admin/orders/save_status/' . $data['oid']); ?>"
                              method="post">
                            <div class="col-md-3">
                                <div class="form-group">

                                    <?php echo form_dropdown('status', array('pending' => 'รอตรวจสอบการสั่งซื้อ',
                                        'confirmed' => 'ยืนยันการสั่งซื้อ',
                                        'wait_payment' => 'ลูกค้าชำระเงิน/ส่งเอกสาร',
                                        'confirm_payment' => 'ยืนยันการชำระ/ส่งเอกสาร',
                                        'shping' => 'มีการจัดส่ง',
                                        'success' => 'สำเร็จ',
                                        'cancel' => 'ยกเลิก'), $data['order_status'], 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <button class="btn btn-primary" type="submit" value="nomail" name="submit">Save without
                                    send email.
                                </button>
                                <button class="btn btn-success" type="submit" value="save" name="submit">Save and send
                                    email.
                                </button>
                                <button class="btn btn-info" type="submit" value="email" name="submit">Send email
                                    again.
                                </button>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                        <hr>
                        <?php if ($data['order_status'] == "pending") { ?>
                            <a href="#" class="btn btn-success btn-sm pull-right" data-toggle="modal"
                               data-target="#addproductModal"><i
                                        class="fa fa-plus"></i> Add new product</a>
                        <?php } ?>
                        <div class="clearfix"></div>

                        <table class="table table-bordered table-strip">
                            <thead>
                            <tr>
                                <th>รายการสินค้า</th>
                                <th>ราคา/หน่วย (บาท)</th>
                                <th>ราคาพิเศษ/หน่วย (บาท)</th>
                                <th>จำนวน</th>
                                <th>รวมต่อรายการ (บาท)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="body-product">
                            <?php foreach ($products as $product) { ?>
                                <tr id="p-<?php echo $product['pa_id']; ?>">
                                    <td><?php echo '<a href="' . base_url('product/' . $product['pid'] . '/' . url_title($product['product_title'])) . '" target="_blank">' . $product['product_title'] . '</a>'; ?>
                                        [<?php echo $product['product_code']; ?>]
                                        - <?php echo $product['product_value']; ?>
                                    </td>
                                    <td><input type="text" value="<?php echo $product['product_amount']; ?>"
                                               class="form-control product_amount digi"
                                               data-id="<?php echo $product['pa_id']; ?>"
                                               id="product_amount-<?php echo $product['pa_id']; ?>"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>></td>
                                    <td><input type="text" value="<?php echo $product['product_spacial_amount']; ?>"
                                               class="form-control product_spacial_amount digi"
                                               data-id="<?php echo $product['pa_id']; ?>"
                                               id="product_spacial_amount-<?php echo $product['pa_id']; ?>"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>></td>
                                    <td><input type="number" value="<?php echo $product['product_qty']; ?>"
                                               class="form-control product_qty number"
                                               data-id="<?php echo $product['pa_id']; ?>"
                                               id="product_qty-<?php echo $product['pa_id']; ?>"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>></td>
                                    <td id="total_amount_p<?php echo $product['pa_id']; ?>"><?php echo $product['total_amount']; ?></td>
                                    <td>
                                        <?php if ($data['order_status'] == "pending") { ?>
                                            <button type="button" class="btn btn-sm btn-danger delete-product"
                                                    data-id="<?php echo $product['pa_id']; ?>"><i
                                                        class="fa fa-times-circle"></i></button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4"><strong>ราคารวมสินค้า</strong></strong></td>
                                <td colspan="2">
                                    <strong>
                                        <div id="total-normal">0</div>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>ราคาพิเศษ ลดรวม</strong></strong></td>
                                <td colspan="2">
                                    <strong>
                                        <div id="spacial-discount">0</div>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>รหัสส่วนลด</strong></td>
                                <td colspan="3">
                                    <div class="input-group" style="max-width: 250px;">
                                        <input type="text" class="form-control"
                                               aria-label="Coupon Code" maxlength="20" id="coupon"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>>
                                        <span class="input-group-addon" id="submit-coupon"
                                              <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>>ตกลง</span>
                                    </div>

                                    <strong>
                                        <div id="coupon-amount" class=""></div>
                                    </strong>
                                </td>
                                <td colspan="2">
                                    <strong>
                                        <div id="coupon-total">0</div>
                                    </strong>
                                </td>
                            </tr>
                            <tr id="tr10k">
                                <td colspan="4"><strong>ซื้อครบ 100,000 บาท ลด 5% </strong></td>
                                <td colspan="2">
                                    <strong>
                                        <div id="discount-100k">0</div>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>ยอดรวมก่อนภาษี</strong></td>
                                <td colspan="2"><strong>
                                        <div id="before-vat">0</div>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>ภาษีมูลค่าเพิ่ม (7%)</strong></td>
                                <td colspan="2"><strong>
                                        <div id="vat">0</div>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>ค่าส่งสินค้า</strong></td>
                                <td colspan="2"><strong>
                                        <input type="text" id="shiping-amount" value="0" class="form-control digi"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>รวมสุทธิ</strong></td>
                                <td colspan="2"><strong>
                                        <div id="total-price">0</div>
                                    </strong></td>
                            </tr>
                            </tfoot>

                        </table>


                        <div class="clearfix"></div>
                        <?php if ($data['order_status'] == "pending") { ?>
                            <div class="box-footer">
                                <button type="button" id="save-order" class="btn btn-success">
                                    <i class="fa fa-check"></i> Save
                                </button>
                                <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-warning">
                                    <i class="fa fa-times-circle"></i> Back to list
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        <?php } ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in" id="document">


                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-warning">
                                <i class="fa fa-times-circle"></i> Back to list
                            </a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in" id="shping">


                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Save
                            </button>
                            <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-warning">
                                <i class="fa fa-times-circle"></i> Back to list
                            </a>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="logs">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($logs) {
                                foreach ($logs as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($value['log_date'])); ?></td>
                                        <td><?php echo $value['user']; ?></td>
                                        <td><?php echo $value['detail']; ?></td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>


        </div>
    </div>
</div>

<div class="modal fade" id="addproductModal" tabindex="-1" role="dialog" aria-labelledby="addproductModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add Product</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Product : </label>
                    <select id="product-select">
                        <option value="">== Select ==</option>
                        <?php foreach ($product_list as $product_item) { ?>
                            <option value="<?php echo $product_item['id']; ?>|<?php echo $product_item['title']; ?>"><?php echo $product_item['title']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Code : </label>
                    <select id="code-select">
                        <option value="">== Select ==</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Color : </label>
                    <div id="product-color"></div>
                </div>
                <div class="form-group">
                    <label>Price : </label>
                    <div id="add-product-price"></div>
                </div>
                <div class="form-group">
                    <label>Spacial Price : </label>
                    <div id="add-product-sp-price"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add-product-btn">Add product</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>