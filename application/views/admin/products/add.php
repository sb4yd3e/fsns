<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Add new product</h3>
            </div>
            <form action="<?php echo base_url() ?>admin/products/add" method="post" class="ui-form"
                  enctype="multipart/form-data">
                <div class="box-body">
                    <?php echo validation_errors(); ?>
                    <div class="row clearfix">

                        <div class="col-md-6">


                            <div class="form-group">
                                <label>Product Cover (Size: 450x235px)*:</label>
                                <input type="file" name="cover" size="20" required/>
                            </div>
                            <div class="form-group">
                                <label>Product title*:</label>
                                <input name="title" value="<?php echo set_value('title') ?>" class="form-control"
                                       required/>
                            </div>
                            <div class="form-group">
                                <label>Techical Info:</label>
                                <textarea name="info" id="info"><?php echo set_value('info') ?></textarea>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="product_spacial_price" class="control-label">Product Model
                                            Code*:</label>
                                        <input type="text" name="model_code"
                                               value="<?php echo $this->input->post('model_code'); ?>"
                                               class="form-control" id="product_model_code" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Specification PDF  (Maximum 3 MB):</label><br>
                                        <input type="file" name="pdf" size="20" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product category*:</label>
                                        <select name="taxonomy_term_id" id="taxonomy_term_id" class="form-control"
                                                required>
                                            <?php
                                            $options = array();
                                            foreach ($product_category as $lv_one) {
                                                ?>
                                                <optgroup label="<?php echo $lv_one['title'] ?>">
                                                    <?php
                                                    if (isset($lv_one['children'])) {
                                                        foreach ($lv_one['children'] as $lv_two) {
                                                            if (isset($lv_two['children'])) {
                                                                ?>
                                                                <optgroup
                                                                        label="<?php echo $lv_two['title'] ?>"></optgroup>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $lv_two['term_id'] ?>"><?php echo $lv_two['title'] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </optgroup>
                                                <?php
                                            }
                                            echo form_dropdown('taxonomy_term_id', $options);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Group*:</label>
                                        <input name="group" value="<?php echo set_value('group') ?>" required
                                               class="form-control"/>
                                        <style>
                                            .ui-autocomplete-loading {
                                                background: white url("<?php echo base_url("img/slider-loading.gif"); ?>") right center no-repeat;
                                            }
                                        </style>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Product description*:</label>
                                <textarea name="body" rows="5" id="body" class="form-control"
                                          required><?php echo set_value('body') ?></textarea>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="product_online" class="control-label">Is Shopping Online*:</label><br>
                                        <?php echo form_dropdown('product_online', array(
                                            '1' => 'YES',
                                            '0' => 'NO'
                                        ), '', 'class="form-control" required'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="product_instock" class="control-label">Product In Stock*:</label><br>

                                        <?php echo form_dropdown('product_in_stock', array(
                                            '1' => 'YES',
                                            '0' => 'NO'
                                        ), '', 'class="form-control" required'); ?>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="product_price" class="control-label">Default Display
                                        Price(Baht)*:</label>
                                    <div class="form-group">
                                        <input type="text" name="product_price"
                                               value="<?php echo ($this->input->post('product_price')?$this->input->post('product_price'):0); ?>"
                                               class="form-control digi" id="product_price" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="product_spacial_price" class="control-label">Default Special Display
                                        Price(Baht)*:</label>
                                    <div class="form-group">
                                        <input type="text" name="product_spacial_price"
                                               value="<?php echo ($this->input->post('product_spacial_price'))?$this->input->post('product_spacial_price'):0; ?>"
                                               class="form-control digi" id="product_spacial_price" required/>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <h4>Product Attribute</h4>

                            <input type="hidden" name="type" value="" id="type"/>
                            <div class="clearfix row" style="padding-right: 20px;" id="first-box">

                                <div class="thumbnail clearfix ">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Code*:</label>
                                            <input type="text" name="code[]" class="form-control" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>Photo</label>
                                            <input type="file" name="photo[]" class="form-control"/>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Price*:</label>
                                                <input type="text" name="price[]" value="0" class="form-control digi"
                                                       required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Special Price*:</label>
                                                <input type="text" name="sp_price[]" value="0" class="form-control digi"
                                                       required/>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="col-md-6" id="edit-type">
                                        <div class="form-group" id="color-boxed">
                                            <label>Color*:</label>
                                            <input type="hidden" name="color[]" value="#ffffff" id="color-selector-0"
                                                   class="form-control color-input" required/>
                                            <div class="color-box">
                                                <div class="color-active"></div>
                                                <div class="color-select color-1" data-hex="#ffffff"
                                                     data-text="สีขาว"></div>
                                                <div class="color-select color-2" data-hex="#1B88CB"
                                                     data-text="สีฟ้า"></div>
                                                <div class="color-select color-3" data-hex="#12A144"
                                                     data-text="สีเขียว"></div>
                                                <div class="color-select color-4" data-hex="#FDDA1A"
                                                     data-text="สีเหลือง"></div>
                                                <div class="color-select color-5" data-hex="#0E1522"
                                                     data-text="สีดำ"></div>
                                                <div class="color-select color-6" data-hex="#CD2026"
                                                     data-text="สีแดง"></div>
                                                <div class="color-select color-7" data-hex="#7E2683"
                                                     data-text="สีม่วง"></div>
                                                <div class="color-select color-8" data-hex="#F05C21"
                                                     data-text="สีส้ม"></div>
                                                <div class="color-select-picker" id="color-picker-0"></div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="other-boxed">
                                            <label>Color (Text)*:</label>
                                            <input type="text" name="value[]" class="form-control" required/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Product In Stock*:</label>
                                            <?php echo form_dropdown('stock[]', array(
                                                '1' => 'YES',
                                                '0' => 'NO',
                                            ), '', 'class="form-control" required'); ?>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Minimum Qty*:</label>
                                            <input type="text" name="minimum[]" value="0" class="form-control number"
                                                   required/>
                                        </div>
                                        <div id="box-type">
                                            <button type="button" class="btn btn-info btn-sm" id="add-color">Add
                                                Colors
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" id="add-model">Add
                                                Models
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" id="add-size">Add
                                                Size
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="more-att">


                            </div>
                            <button type="button" class="btn btn-danger pull-right" id="reset-all"
                                    style="display: none;"><i class="fa fa-times-circle"></i> Reset All
                            </button>
                            <button type="button" class="btn btn-success pull-right" id="add-at"
                                    style="display: none; margin-right: 10px;">
                                <i class="fa fa-plus"></i> Add more
                            </button>

                        </div>

                    </div>


                </div>


                <div class="box-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-warning">
                        <i class="fa fa-times-circle"></i> Back to list
                    </a>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</div>