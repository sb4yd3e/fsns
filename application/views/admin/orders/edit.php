<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Order #<?php echo sprintf("%06d", $data['oid']); ?></h3>
                <input type="hidden" id="oid" value="<?php echo $data['oid']; ?>">
            </div>

            <div class="box-body">
                <?php echo validation_errors(); ?>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#info" aria-controls="home" role="tab" data-toggle="tab">Order info</a>
                    </li>
                    <li role="presentation">
                        <a href="#status" aria-controls="status" role="tab" data-toggle="tab">Order Status</a>
                    </li>
                    <li role="presentation">
                        <a href="#shipping" aria-controls="shipping" role="tab" data-toggle="tab" id="tab-shipping"
                           class="disabled">Shipping</a>
                    </li>
                    <li role="presentation">
                        <a href="#document" aria-controls="document" role="tab" data-toggle="tab" id="tab-document"
                           class="disabled">Documents</a>
                    </li>

                    <li role="presentation">
                        <a href="#logs" aria-controls="business" role="tab" data-toggle="tab" id="tab-logs">Logs</a>
                    </li>
                </ul>
                <div class="tab-content" style="margin-top: 20px;">
                    <div role="tabpanel" class="tab-pane fade in active" id="info">

                        <?php if ($data['order_status'] == "pending" && !is_group('sale')) { ?>
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
                                               min="<?php echo $product['minimum']; ?>"
                                               id="product_qty-<?php echo $product['pa_id']; ?>"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>>
                                        <?php if ($product['minimum'] > 0){ ?>
                                        *ต้องสั่งขั้นต่ำอย่างน้อย <?php echo $product['minimum']; ?> หน่วย
                                        <?php } ?>
                                    </td>
                                    <td id="total_amount_p<?php echo $product['pa_id']; ?>"><?php echo $product['total_amount']; ?></td>
                                    <td>
                                        <?php if ($data['order_status'] == "pending"&&!is_group('sale')) { ?>
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
                                               <?php if ($data['order_status'] != "pending"&&!is_group('sale')){ ?>readonly<?php } ?>>
                                        <?php if(!is_group('sale')){ ?>
                                        <span class="input-group-addon" id="submit-coupon"
                                              <?php if ($data['order_status'] != "pending"&&!is_group('sale')){ ?>readonly<?php } ?>>ตกลง</span>
                                        <?php } ?>
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
                                <td colspan="4"><strong>ส่วนลดพิเศษ</strong></td>
                                <td colspan="2"><strong>
                                        <input type="text" id="custom-discount" value="0" class="form-control digi"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>/>
                                    </strong></td>
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
                                <td colspan="4"><strong>รวมเป็นเงิน</strong></td>
                                <td colspan="2"><strong>
                                        <div id="before-shipping">0</div>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>ค่าส่งสินค้า</strong></td>
                                <td colspan="2"><strong>
                                        <input type="text" id="shipping-amount" value="0" class="form-control digi"
                                               <?php if ($data['order_status'] != "pending"){ ?>readonly<?php } ?>>
                                    </strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>รวมเป็นเงินที่ต้องชำระ</strong></td>
                                <td colspan="2"><strong>
                                        <div id="total-price">0</div>
                                    </strong></td>
                            </tr>
                            </tfoot>

                        </table>


                        <div class="clearfix"></div>
                        <?php if ($data['order_status'] == "pending" && !is_group('sale')) { ?>
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
                    <div role="tabpanel" class="tab-pane fade" id="status">

                        <div class="col-md-6"><?php if(!is_group('sale')){ ?>
                            <form action="<?php echo base_url('admin/orders/save_status/' . $data['oid']); ?>"
                                  method="post" id="ajax-status">

                                <div class="form-group">
                                    <label>Status</label>
                                    <?php echo form_dropdown('status', array('pending' => 'รอตรวจสอบการสั่งซื้อ',
                                        'confirmed' => 'ยืนยันการสั่งซื้อ',
                                        'wait_payment' => 'ลูกค้าชำระเงิน/ส่งเอกสาร',
                                        'confirm_payment' => 'ยืนยันการชำระ/ส่งเอกสาร',
                                        'shipping' => 'มีการจัดส่ง',
                                        'success' => 'สำเร็จ',
                                        'cancel' => 'ยกเลิก'), $data['order_status'], 'class="form-control"'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea name="comment" id="comment" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" value="nomail" name="submit">Save
                                        without
                                        send email.
                                    </button>
                                    <button class="btn btn-success" type="submit" value="save" name="submit">Save and
                                        send
                                        email.
                                    </button>
                                    <button class="btn btn-info" type="submit" value="email" name="submit">Send email
                                        again.
                                    </button>
                                </div>
                            </form>
                            <?php } ?>
                            <div class="">
                                <?php foreach ($status_list as $status_item) { ?>
                                    <div class="status-detail">
                                        <div class="date">
                                            <?php if ($status_item['owner'] == 'Seller') {
                                                echo '<label class="label label-info">' . $status_item['owner'] . '</label>';
                                            } else {
                                                echo '<label class="label label-warning">' . $status_item['owner'] . '</label>';
                                            } ?>
                                            <?php echo date("d/m/Y H:i:s", $status_item['at_date']); ?> น.
                                        </div>
                                        <div class="status">
                                            Status : <?php echo order_status($status_item['status']); ?>
                                        </div>
                                        <div class="comment">
                                            <?php echo $status_item['text']; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in" id="document">
                        <div class="col-md-6">
                            <div class="panel  with-border">
                                <div class="box-header with-border">เอกสารของลูกค้า</div>
                                <div class="panel-body with-border">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>File Type</th>
                                            <th>File Size</th>
                                            <th>Download</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($custom_files as $cs_file) { ?>
                                            <tr>
                                                <td><?php echo $cs_file['file_title']; ?></td>
                                                <td><?php echo $cs_file['file_type']; ?></td>
                                                <td><?php echo number_format($cs_file['file_size'], 2); ?> KB</td>
                                                <td><?php echo date("d/m/Y H:i:s", strtotime($cs_file['file_date'])); ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/orders/download_file/' . $cs_file['ufid']); ?>"
                                                       target="_blank" class="label label-info">Download</a></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel  with-border">
                                <div class="box-header with-border">จัดการเอกสารส่งให้ลูกค้า
                                    <?php if(!is_group('sale')){ ?>
                                    <button class="btn btn-info btn-sm pull-right" id="add-file" data-toggle="modal"
                                            data-target="#addFileModal"><i
                                                class="fa fa-plus"></i></button>
                                    <?php } ?>
                                </div>
                                <div class="panel-body with-border">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>File Type</th>
                                            <th>File Size</th>
                                            <th>Download</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="ajax-file-result">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade in" id="shipping">

                        <div class="col-md-6">
                            <strong>Shipping Address</strong><br>
                            <label for="shipping_name" class="control-label">Name</label>
                            <div class="form-group">
                                <input type="text" name="shipping_name"
                                       value="<?php echo($this->input->post('shipping_name') ? $this->input->post('shipping_name') : $data['shipping_name']); ?>"
                                       class="form-control" id="shipping_name" required/>
                            </div>
                            <label for="shipping_address" class="control-label">Shipping Address</label>
                            <div class="form-group">
                                <textarea name="shipping_address" rows="4" class="form-control" id="shipping_address"
                                          required><?php echo($this->input->post('shipping_address') ? $this->input->post('shipping_address') : $data['shipping_address']); ?></textarea>
                            </div>
                            <label for="shipping_province" class="control-label">Shipping Province</label>
                            <div class="form-group">
                                <?php echo form_dropdown('shipping_province', list_province(), $data['shipping_province'], 'class="form-control" id="shipping_province" required'); ?>
                            </div>
                            <label for="shipping_zip" class="control-label">Shipping Zip</label>
                            <div class="form-group">
                                <input type="text" name="shipping_zip" maxlength="5"
                                       value="<?php echo($this->input->post('shipping_zip') ? $this->input->post('shipping_zip') : $data['shipping_zip']); ?>"
                                       class="form-control" id="shipping_zip" required/>
                            </div>
                            <hr>
                            <strong>Billing Address</strong><br>
                            <label for="billing_name" class="control-label">Name</label>
                            <div class="form-group">
                                <input type="text" name="billing_name"
                                       value="<?php echo($this->input->post('billing_name') ? $this->input->post('billing_name') : $data['billing_name']); ?>"
                                       class="form-control" id="billing_name" required/>
                            </div>
                            <label for="billing_address" class="control-label">Shipping Address</label>
                            <div class="form-group">
                                <textarea name="billing_address" rows="4" class="form-control" id="billing_address"
                                          required><?php echo($this->input->post('billing_address') ? $this->input->post('billing_address') : $data['billing_address']); ?></textarea>
                            </div>
                            <label for="billing_province" class="control-label">Shipping Province</label>
                            <div class="form-group">
                                <?php echo form_dropdown('billing_province', list_province(), $data['billing_province'], 'class="form-control" id="billing_province" required'); ?>
                            </div>
                            <label for="billing_zip" class="control-label">Shipping Zip</label>
                            <div class="form-group">
                                <input type="text" name="billing_zip" maxlength="5"
                                       value="<?php echo($this->input->post('billing_zip') ? $this->input->post('billing_zip') : $data['billing_zip']); ?>"
                                       class="form-control" id="billing_zip" required/>
                            </div>
                            <?php if(!is_group('sale')){ ?>
                            <div class="box-footer">
                                <button type="button" id="save-shipping" class="btn btn-success">
                                    <i class="fa fa-check"></i> Save
                                </button>
                                <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-warning">
                                    <i class="fa fa-times-circle"></i> Back to list
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="panel">
                                <div class="box-header with-border">
                                    จัดการการจัดส่งสินค้า
                                </div>
                                <div class="panel-body with-border">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Note</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php

                                        $color_arr = array('pending' => '', 'confirmed' => '', 'wait_payment' => '', 'confirm_payment' => '', 'shipping' => 'info', 'success' => 'success', 'cancel' => 'danger');
                                        foreach ($products as $product) { ?>
                                            <tr>
                                                <td class="<?php echo $color_arr[$product['status']]; ?>">
                                                    <?php echo $product['product_title']; ?>
                                                    [<?php echo $product['product_code']; ?>]
                                                    - <?php echo $product['product_value']; ?>
                                                </td>
                                                <td class="<?php echo $color_arr[$product['status']]; ?>">
                                                    <?php echo $product['note']; ?>
                                                </td>
                                                <td class="<?php echo $color_arr[$product['status']]; ?>">
                                                    <?php if (in_array($data['order_status'], array('confirm_payment', 'shipping')) && !in_array($product['status'], array('cancel', 'success', 'shipping'))) { ?>
                                                        <label><input type="checkbox" class="select-product"
                                                                      value="<?php echo $product['odid']; ?>">
                                                            select</label>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>


                                        </tbody>
                                    </table>
                                    <?php if(!is_group('sale')){ ?>
                                    <div>
                                        <form action="<?php echo base_url('admin/orders/change_shipping/' . $data['oid']); ?>"
                                              method="post">
                                            <input type="hidden" id="id-products" name="ids-product" value="">
                                            <div class="form-group">
                                                <label>Comment</label>
                                                <textarea name="comment" id="shipping-comment" class="form-control"
                                                          rows="3"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" name="type" value="save" class="btn btn-info">
                                                    Shipping
                                                    product
                                                </button>
                                                <button type="submit" name="type" value="save_all"
                                                        class="btn btn-success">Shipping all products
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>


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
<?php if(!is_group('sale')){ ?>
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
                <div class="form-group">
                    <label>Minimum QTY : </label>
                    <div id="add-minimum"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add-product-btn">Add product</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addFileModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo base_url('admin/orders/upload_document/' . $data['oid']); ?>"
                  enctype="multipart/form-data" id="ajax-upload-document">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add Document file</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File name : </label>
                        <input type="text" name="title" maxlength="50" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>File : </label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="add-file-btn">Upload</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>