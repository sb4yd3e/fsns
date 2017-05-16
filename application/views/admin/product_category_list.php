<script type="text/javascript">
    $(function(){
        $('.dialog').hide();
        $('.icon').hover(function(){
            $(this).css('opacity',1);
        },function(){
            $(this).css('opacity',0.3);
        })
        $('.add_category').click(function(){
            $('#add-category-dialog .parent_id').val($(this).attr('id').replace('parent_',''));
            
            $('#add-category-dialog').dialog({
                width: 500,
                buttons:{
                    'Add new Category':function(){
                        $('#add_form').submit();
                    },
                    'Cancel':function(){
                        $(this).dialog( "close" );
                    }
                }
            });
        });
        $('.edit_category').click(function(){
            var edit_value = $(this).attr('rel').split(';');
            $('#edit-category-title-en').val(edit_value[0]);
            $('#edit-category-title-th').val(edit_value[1]);
            $('#edit-category-caption').val(edit_value[2]);
            $('#edit-category-weight').val(edit_value[3]);
            $('#edit-category-id').val($(this).attr('id').replace('edit_',''));
            $('#edit-category-dialog #add-category_weight').val(0);
            $('#edit-category-dialog').dialog({
                width: 500,
                buttons:{
                    'Edit Category':function(){
                        $('#edit_form').submit();
                    },
                    'Cancel':function(){
                        $(this).dialog( "close" );
                    }
                }
            });
        });
        
        $('.delete_category').click(function(){
            var term_id = $(this).attr('id').replace('delete_','');
            var current_button = $(this);
            var dialog = $('#delete-category-dialog').dialog({
                width: 300,
                buttons:{
                    'Confirm':function(){
                        $.post('<?php echo base_url()?>admin/product_category_delete',{term_id:term_id},function(){
                            
                            current_button.parent('li').slideUp();
                            dialog.dialog( "close" );
                            /*window.location.reload();*/
                        });
                        
                    },
                    'Cancel':function(){
                        $(this).dialog( "close" );
                    }
                }
            });
        });
    });
</script>

<div class="dialog ui-form" id="add-category-dialog" title="Add New Category">
    <form action="<?php echo base_url()?>admin/product_category_add" method="post" id="add_form" enctype="multipart/form-data">
   
    
    <label>Category Title: </label>
    <input type="text" name="title_en" value="" />
    <!--
    <label>Category Title (TH): </label>
    <input type="text" name="title_th" value="" />
    -->
    <label>Category Caption</label>
    <textarea name="body" style="width:450px;height:100px;" rows="5"></textarea>
    <!--
    <label>Category Header (Size: 960x204px): </label>
    <input type="file" name="header_img" size="20" />
    
    <label>Category Cover (Size: 225x183px): </label>
    <input type="file" name="cover_img" size="20" />
    -->
    <label>Category Weight: </label>
    <input type="text" name="weight" value="0" id="add-category-weight"/>
    
    <input type="hidden" name="parent_id" class="parent_id" value=""/>
    </form>
</div>

<div class="dialog ui-form" id="edit-category-dialog" title="Edit Category">
    <form action="<?php echo base_url()?>admin/product_category_edit" method="post" id="edit_form" enctype="multipart/form-data">
    
    <label>Category Title: </label>
    <input type="text" name="title_en" value="" id="edit-category-title-en" />
    
    <!--
    <label>Category Title (TH): </label>
    <input type="text" name="title_th" value="" id="edit-category-title-th"/>
    -->
    <label>Category Caption</label>
    <textarea name="body" style="width:450px;height:100px;" id="edit-category-caption"></textarea>
    <!--
    <label>Category Header (Size: 960x204px): </label>
    <input type="file" name="header_img" size="20" />
    
    <label>Category Cover (Size: 225x183px): </label>
    <input type="file" name="cover_img" size="20" />
    -->
    <label>Category Weight: </label>
    <input type="text" name="weight" value="0" id="edit-category-weight"/>
    
    <input type="hidden" name="term_id" value="" id="edit-category-id"/>
    </form>
</div>

<div class="dialog ui-form" id="delete-category-dialog" title="Are you you to delete this product category?">
    คุณแน่ใจหรือไม่ที่จะลบหมวดสินค้านี้?
</div>

<div class="wrapper-content product-category">
    <ul>
        <li>
            <b style="font-size:15px;">หมวดของสินค้า <a href="javascript:void(0);" id="parent_0" class="add_category icon" title="Add Category"><img src="<?php echo base_url()?>img/icons/add.png" alt="Add Category" ></a></b>
            <ul>
        <?php
        foreach ($product_category as $row_one) {
            ?>
            <li>
                <?php echo $row_one['title']?> 
                <a href="javascript:void(0);" id="parent_<?php echo $row_one['term_id']?>" class="add_category icon" title="Add Category"><img src="<?php echo base_url()?>img/icons/add.png" alt="Add Category"></a>
                <a href="javascript:void(0);" id="edit_<?php echo $row_one['term_id']?>" class="edit_category icon" title="Edit Category" rel="<?php echo $row_one['title'].';'.$row_one['title_th'].';'.$row_one['body'].';'.$row_one['weight']?>"><img src="<?php echo base_url()?>img/icons/edit.png" alt="Edit Category"></a>
                <a href="javascript:void(0);" id="delete_<?php echo $row_one['term_id']?>" class="delete_category icon" title="Delete Category"><img src="<?php echo base_url()?>img/icons/remove.png" alt="Delete Category"></a>
                <?php
                if (isset($row_one['children'])) {
                    ?>
                    <ul>
                        <?php
                        foreach ($row_one['children'] as $row_two) {
                            ?>
                            <li>
                                <?php echo $row_two['title'] ?> 
                                <a href="javascript:void(0);" id="edit_<?php echo $row_two['term_id']?>" class="edit_category icon" title="Edit Category" rel="<?php echo $row_two['title'].';'.$row_two['title_th'].';'.  htmlspecialchars($row_two['body']).';'.$row_two['weight']?>"><img src="<?php echo base_url()?>img/icons/edit.png" alt="Edit Category"></a>
                                <a href="javascript:void(0);" id="delete_<?php echo $row_two['term_id']?>" class="delete_category icon" title="Delete Category"><img src="<?php echo base_url()?>img/icons/remove.png" alt="Delete Category"></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </li>
            <?php
        }
        ?>
    </ul></li>
        </ul>
</div>