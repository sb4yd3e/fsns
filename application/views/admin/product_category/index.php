<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Product categories Listing</h3>
               
            </div>
            <div class="box-body">
                <div class="dialog ui-form" id="add-category-dialog" title="Add New Category">
                <form action="<?php echo base_url()?>admin/category/add" method="post" id="add_form" enctype="multipart/form-data">


                        <label>Category Title: </label>
                        <input type="text" name="title_en" value="" />
                        <label>Category Caption</label>
                        <textarea name="body" style="width:450px;height:100px;" rows="5"></textarea>
                        <label>Category Weight: </label>
                        <input type="text" name="weight" value="0" id="add-category-weight"/>

                        <input type="hidden" name="parent_id" class="parent_id" value=""/>
                    </form>
                </div>

                <div class="dialog ui-form" id="edit-category-dialog" title="Edit Category">
                    <form action="<?php echo base_url()?>admin/category/edit" method="post" id="edit_form" enctype="multipart/form-data">
                        <label>Category Title: </label>
                        <input type="text" name="title_en" value="" id="edit-category-title-en" />
                        <label>Category Caption</label>
                        <textarea name="body" style="width:450px;height:100px;" id="edit-category-caption"></textarea>
                        <label>Category Weight: </label>
                        <input type="text" name="weight" value="0" id="edit-category-weight"/>
                        <input type="hidden" name="term_id" value="" id="edit-category-id"/>
                    </form>
                </div>
                <div class="dialog ui-form" id="delete-category-dialog" title="Are you you to delete this product category?">
                    คุณแน่ใจหรือไม่ที่จะลบหมวดสินค้านี้?
                </div>
                <div class="wrapper-content product-category">
                    <ul class="list-group">
                        <?php
                        foreach ($product_category as $row_one) {
                            ?>
                            <li class="list-group-item">
                                <h4><?php echo strip_tags($row_one['title'])?> 
                                    <a href="javascript:void(0);" id="parent_<?php echo $row_one['term_id']?>" class="add_category icon" title="Add Category"><img src="<?php echo base_url()?>img/icons/add.png" alt="Add Category"></a>
                                    <a href="javascript:void(0);" id="edit_<?php echo $row_one['term_id']?>" class="edit_category icon" title="Edit Category" rel="<?php echo $row_one['title'].';'.$row_one['title_th'].';'.$row_one['body'].';'.$row_one['weight']?>"><img src="<?php echo base_url()?>img/icons/edit.png" alt="Edit Category"></a>
                                    <a href="javascript:void(0);" id="delete_<?php echo $row_one['term_id']?>" class="delete_category icon" title="Delete Category"><img src="<?php echo base_url()?>img/icons/remove.png" alt="Delete Category"></a>
                                </h4>
                                <hr>
                                <?php
                                if (isset($row_one['children'])) {
                                    ?>
                                    <ul class="list-group">
                                        <?php
                                        foreach ($row_one['children'] as $row_two) {
                                            ?>
                                            <li class="list-group-item">
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>