<div class="wrapper-content product-add">
    <?php echo validation_errors(); ?>
    <?php echo isset($error_upload) ? $error_upload : '' ?>
    <form action="<?php echo base_url() ?>admin/product_edit/<?php echo $product['id'] ?>" method="post" class="ui-form" enctype="multipart/form-data">
        <label>Replace product cover (Size: 450x235px):</label>
        <img src="<?php echo base_url()?>timthumb.php?src=<?php echo base_url().PRODUCT_PATH.'/'.$product['cover']?>&w=100" style="border:2px solid #eee"/>
        <br/>
        <input type="file" name="cover" size="20" />

        <label>Product title*:</label>
        <input name="title" value="<?php echo set_value('title', $product['title']) ?>" style="width:200px;"/>
        
        <label>Product category*:</label>
        <select name="taxonomy_term_id" id="taxonomy_term_id">
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
                            <optgroup label="<?php echo $lv_two['title'] ?>"></optgroup>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $lv_two['term_id'] ?>" <?php echo set_select('taxonomy_term_id', $lv_two['term_id'], $product['term_id'] == $lv_two['term_id'] ? TRUE : FALSE ); ?>><?php echo $lv_two['title'] ?></option>
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
        
        
         <label>Product Group*:</label>
        <input name="group" value="<?php echo set_value('group', $product['group']) ?>" style="width:200px;"/>
        <style>
            .ui-autocomplete-loading {
                background: white url("<?php echo base_url() ?>img/slider-loading.gif") right center no-repeat;
            }
        </style>
        <script>
            $('select[name="taxonomy_term_id"]').change(function(){
                $("input[name='group']").val('');
            });
            $("input[name='group']").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo base_url() ?>admin/ajax_get_group",
                        dataType: "json",
                        type: "POST",
                        data: {
                            keyword: request.term,
                            term_id: $('select[name="taxonomy_term_id"]').val()
                        },
                        complete: function (data) {
                            $("input[name='group']").removeClass('ui-autocomplete-loading');
                        },
                        success: function (data) {

                            response(data);
                        }
                    });
                },
                minLength: 3

            });
        </script>
        
        

        <label>Replace specification PDF:</label>
        <?php
        if (is_file('./'.PDF_PATH.'/'.$product['pdf']))
        {
            ?>
        Current PDF: <a target="_blank" href="<?php echo base_url()?>frontend/product_pdf_download/<?php echo $product['id']?>/<?php echo md5($product['id'].'suwichalala')?>/<?php echo url_title($product['title'])?>_Specification.pdf"><?php echo url_title($product['title'])?>_Specification.pdf <img src="<?php echo base_url()?>img/icons/pdf.png" /></a><br/>
            <?php
        }
        ?>
        <input type="file" name="pdf" size="20" />

        <label>Product description*:</label>
        <textarea name="body"  style="width:500px;height:100px;" id="body"><?php echo set_value('body', $product['body']) ?></textarea>

        


        <br/><br/>

        <br/>
        <input type="submit" value="Edit this product" class="button"/>
        <a href="<?php echo base_url() ?>admin/product_list" class="button">Back to product list</a>
    </form>

</div>
