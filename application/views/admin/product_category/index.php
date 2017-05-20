<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Product categories Listing</h3>
                 <div class="box-tools">
                    <a href="javascript:void(0);" id="parent_0" class="add_category  btn btn-success btn-sm"> <i class="fa fa-plus"></i> Create new category</a> 
                </div>
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
                <div class="wrapper-content product-category col-lg-12">

                    <?php
                    foreach ($product_category as $row_one) {
                        ?>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <div class="col-lg-6">
                             <strong> <?php echo strip_tags($row_one['title'])?> </strong>
                         </div>
                         <div class="col-lg-6" align="right">
                          <a href="javascript:void(0);" id="parent_<?php echo $row_one['term_id']?>" class="add_category label label-success">
                              <i class="fa fa-plus"></i> Create
                          </a> &nbsp;&nbsp;
                          <a href="javascript:void(0);" id="edit_<?php echo $row_one['term_id']?>" class="edit_category label label-warning" rel="<?php echo $row_one['title'].';'.$row_one['body'].';'.$row_one['weight']?>">
                              <i class="fa fa-pencil"></i> Edit
                          </a> &nbsp;&nbsp;
                          <a href="javascript:void(0);" id="delete_<?php echo $row_one['term_id']?>" class="delete_category label label-danger">
                            <i class="fa fa-times-circle"></i> Delete
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($row_one['children'])) {
                        ?>
                        <ul class="list-group">
                            <?php
                            foreach ($row_one['children'] as $row_two) {
                                ?>
                                <li class="list-group-item">
                                    <div class="col-lg-6">
                                        <?php echo $row_two['title'] ?> 
                                    </div>
                                    <div class="col-lg-6" align="right">
                                        <a href="javascript:void(0);" id="edit_<?php echo $row_two['term_id']?>" class="edit_category label label-warning"  rel="<?php echo $row_two['title'].';'.  htmlspecialchars($row_two['body']).';'.$row_two['weight']?>">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a> &nbsp;&nbsp;
                                        <a href="javascript:void(0);" id="delete_<?php echo $row_two['term_id']?>" class="delete_category label label-danger">
                                            <i class="fa fa-times-circle"></i> Delete
                                        </a>
                                    </div>
                                     <div class="clearfix"></div>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
</div>
</div>
</div>
</div>