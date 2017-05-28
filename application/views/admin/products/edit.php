<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Edit product</h3>
            </div>
            <form action="<?php echo base_url() ?>admin/products/edit/<?php echo $product['id']; ?>" method="post"
                  class="ui-form"
                  enctype="multipart/form-data">
                <div class="box-body">
                    <?php echo validation_errors(); ?>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Cover (Size: 450x235px)*:</label>
                                <img src="<?php echo base_url() ?>timthumb.php?src=<?php echo base_url() . PRODUCT_PATH . '/' . $product['cover'] ?>&w=100"
                                     style="border:2px solid #eee" class="thumbnail"/>
                                <input type="file" name="cover" size="20"/>
                            </div>
                            <div class="form-group">
                                <label>Product title*:</label>
                                <input name="title" value="<?php echo set_value('title', $product['title']) ?>"
                                       class="form-control" required/>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="product_spacial_price" class="control-label">Product Model
                                            Code</label>
                                        <input type="text" name="model_code"
                                               value="<?php echo set_value('title', $product['model_code']) ?>"
                                               class="form-control" id="product_model_code" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Specification PDF:</label><br>
                                        <div class="col-md-8 no-padding">
                                            <input type="file" name="pdf" size="20"
                                                   class="form-control"/>
                                        </div>
                                        <div class="col-md-4">
                                            <?php if ($product['pdf']) { ?>

                                                <a href="<?php echo base_url('frontend/product_pdf_download/' . $product['id'] . '/' . md5($product['id'] . 'suwichalala') . '/' . url_title($product['title'])) . '_Specification.pdf'; ?>"
                                                   class="btn  btn-info" target="_blank" style="margin-left: 10px;"
                                                   id="download-pdf"><i class="fa fa-download"></i></a>
                                                <a href="#" id="remove-pdf" class="btn btn-danger"
                                                   target="_blank">
                                                    <i class="fa fa-times-circle"></i>
                                                </a>

                                            <?php } ?>
                                        </div>
                                        <input type="hidden" id="delete-pdf" value="false" name="delete-pdf">
                                        <div class="clearfix"></div>
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
                                                                <option value="<?php echo $lv_two['term_id'] ?>" <?php if ($product['taxonomy_term_id'] == $lv_two['term_id']) {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $lv_two['title'] ?></option>
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
                                        <input name="group" value="<?php echo set_value('group', $product['group']) ?>"
                                               required
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
                                          required><?php echo set_value('body', $product['body']) ?></textarea>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="product_online" class="control-label">Is online product</label><br>
                                        <?php echo form_dropdown('product_online', array(
                                            '' => 'Select',
                                            '0' => 'NO',
                                            '1' => 'YES',
                                        ), $product['online'], 'class="form-control" required'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="product_instock" class="control-label">Product In Stock</label><br>

                                        <?php echo form_dropdown('product_in_stock', array(
                                            '' => 'Select',
                                            '0' => 'NO',
                                            '1' => 'YES',
                                        ), $product['in_stock'], 'class="form-control" required'); ?>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="product_price" class="control-label">Default Display
                                        Price(Baht)</label>
                                    <div class="form-group">
                                        <input type="number" name="product_price"
                                               value="<?php echo set_value('product_price', $product['normal_price']) ?>"
                                               class="form-control" id="product_price" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="product_spacial_price" class="control-label">Default Special Display
                                        Price(Baht)</label>
                                    <div class="form-group">
                                        <input type="number" name="product_spacial_price"
                                               value="<?php echo set_value('product_spacial_price', $product['special_price']) ?>"
                                               class="form-control" id="product_spacial_price" required/>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" value="" name="deleted-alt" id="deleted-alt">
                        <div class="col-md-6">
                            <h4>Product Attribute</h4>
                            <div class="clearfix row" style="padding-right: 20px;">
                                <div class="thumbnail clearfix ">
                                    <div class="col-md-6">
                                        <input type="hidden" name="at-id[]" value="<?php echo isset($product_alts[0]['pa_id'])?$product_alts[0]['pa_id']:""; ?>">
                                        <div class="form-group">
                                            <label>Code</label>
                                            <input type="text" name="code[]"
                                                   value="<?php echo isset($product_alts[0]['code'])?$product_alts[0]['code']:""; ?>"
                                                   class="form-control" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="number" name="price[]"
                                                   value="<?php echo isset($product_alts[0]['normal_price'])?$product_alts[0]['normal_price']:"0"; ?>"
                                                   class="form-control" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>Special Price</label>
                                            <input type="number" name="sp_price[]"
                                                   value="<?php echo isset($product_alts[0]['special_price'])?$product_alts[0]['special_price']:"0"; ?>"
                                                   class="form-control" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Color</label>
                                            <input type="hidden" name="color[]"
                                                   value="<?php echo isset($product_alts[0]['color'])?$product_alts[0]['color']:"#FFFFFF"; ?>"
                                                   id="color-selector-0"
                                                   class="form-control color-input" required/>
                                            <div class="color-box">
                                                <div class="color-active"
                                                     style="background-color: <?php echo isset($product_alts[0]['color'])?$product_alts[0]['color']:"#FFFFFF"; ?>"></div>
                                                <div class="color-select color-1" data-hex="#ffffff"></div>
                                                <div class="color-select color-2" data-hex="#1B88CB"></div>
                                                <div class="color-select color-3" data-hex="#12A144"></div>
                                                <div class="color-select color-4" data-hex="#FDDA1A"></div>
                                                <div class="color-select color-5" data-hex="#0E1522"></div>
                                                <div class="color-select color-6" data-hex="#CD2026"></div>
                                                <div class="color-select color-7" data-hex="#7E2683"></div>
                                                <div class="color-select color-8" data-hex="#F05C21"></div>
                                                <div class="color-select-picker"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Value (Text)</label>
                                            <input type="text" name="value[]"
                                                   value="<?php echo isset($product_alts[0]['p_value'])?$product_alts[0]['p_value']:""; ?>"
                                                   class="form-control" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>Product In Stock</label>
                                            <?php echo form_dropdown('stock[]', array(
                                                '' => 'Select',
                                                '0' => 'NO',
                                                '1' => 'YES',
                                            ), isset($product_alts[0]['in_stock'])?$product_alts[0]['in_stock']:"", 'class="form-control" required'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="more-att">
                                <?php
                                array_shift($product_alts);
                                foreach ($product_alts as $k => $pa) { ?>
                                    <div class="clearfix row sub-alt" id="at-<?php echo $k; ?>" style="padding-right: 20px;">
                                        <div class="thumbnail clearfix ">
                                            <button type="button" class="btn btn-sm btn-danger pull-right delete-at"
                                                    data-id="<?php echo $k; ?>" data-aid="<?php echo isset($pa['pa_id'])?$pa['pa_id']:""; ?>"><i class="fa fa-times-circle"></i></button>
                                            <div class="clearfix"></div>
                                            <input type="hidden" name="at-id[]" value="<?php echo isset($pa['pa_id'])?$pa['pa_id']:""; ?>">
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Code</label>
                                                    <input type="text" name="code[]" class="form-control" value="<?php echo isset($pa['code'])?$pa['code']:""; ?>" required/>
                                                </div>
                                                <div class="form-group"><label>Price</label>
                                                    <input type="number" name="price[]" class="form-control" value="<?php echo isset($pa['normal_price'])?$pa['normal_price']:""; ?>" required/>
                                                </div>
                                                <div class="form-group"><label>Special Price</label>
                                                    <input type="number" name="sp_price[]" value="<?php echo isset($pa['special_price'])?$pa['special_price']:""; ?>" class="form-control"
                                                           required/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Color</label>
                                                    <input type="hidden" name="color[]" id="color-selector-<?php echo $k; ?>"
                                                           class="form-control color-input" value="<?php echo isset($pa['color'])?$pa['color']:""; ?>" required/>
                                                    <div class="color-box">
                                                        <div class="color-active" style="background-color: <?php echo isset($pa['color'])?$pa['color']:""; ?>"></div>
                                                        <div class="color-select color-1" data-hex="#ffffff"></div>
                                                        <div class="color-select color-2" data-hex="#1B88CB"></div>
                                                        <div class="color-select color-3" data-hex="#12A144"></div>
                                                        <div class="color-select color-4" data-hex="#FDDA1A"></div>
                                                        <div class="color-select color-5" data-hex="#0E1522"></div>
                                                        <div class="color-select color-6" data-hex="#CD2026"></div>
                                                        <div class="color-select color-7" data-hex="#7E2683"></div>
                                                        <div class="color-select color-8" data-hex="#F05C21"></div>
                                                        <div class="color-select-picker"
                                                             id="color-picker-<?php echo $k; ?>"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group"><label>Value (Text)</label>
                                                    <input type="text" name="value[]" class="form-control" value="<?php echo isset($pa['p_value'])?$pa['p_value']:""; ?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label>Product In Stock</label>
                                                    <?php echo form_dropdown('stock[]', array(
                                                        '' => 'Select',
                                                        '0' => 'NO',
                                                        '1' => 'YES',
                                                    ), isset($pa['in_stock'])?$pa['in_stock']:"", 'class="form-control" required'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <button type="button" class="btn btn-success pull-right" id="add-at"><i
                                        class="fa fa-plus"></i> Add more
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