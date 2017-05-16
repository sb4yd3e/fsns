<div class="wrapper-content product-category">
    
    <?php
    if (isset($_GET['status']))
    {
        ?>
        <div class="ui-state-active ui-corner-all" style="padding: 1em;margin:0 0 10px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        <?php
        if ($_GET['status'] == 'add_complete') echo 'Product is <b>added!</b>';
        if ($_GET['status'] == 'update_complete') echo 'Product is <b>updated!</b>';
        if ($_GET['status'] == 'delete_complete') echo 'Product is <b>deleted!</b>';
        ?>
        </div>
        <?php
    }
    ?>
    
    <a href="<?php echo base_url() ?>admin/product_add" title="Create a new product" class="button">Create a new product</a>
    <div style="float:right">
                <select name="taxonomy_term_id" id="taxonomy_term_id">
                    <option value="0">All Product category</option>
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
                            }
                            else
                            {
                                 ?>
                            <option value="<?php echo $lv_two['term_id']?>" <?php echo isset($_GET['taxonomy_term_id']) && $_GET['taxonomy_term_id'] == $lv_two['term_id']?'selected="selected"':''?>><?php echo $lv_two['title'] ?></option>
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
        <script type="text/javascript">
            $(function(){
               $('#taxonomy_term_id').change(function(){
                  location.href="<?php echo base_url()?>admin/product_list?taxonomy_term_id="+$(this).val();
               }); 
            });
        </script>
    </div>
    <?php
    if (count($product_list) > 0):
        ?>
        <table>
            <thead class="ui-state-default">
                <th style="width:100px;">Cover</th>
                <th>Product title</th>
                <th style="width:150px;">Commands</th>
            </thead>
            <tbody>
                <?php
                foreach ($product_list as $product) {
                    ?>
                <tr>
                    <td style="vertical-align: middle"><img src="<?php echo base_url()?>timthumb.php?src=<?php echo base_url().PRODUCT_PATH.'/'.$product['cover']?>&w=100&h=100&zc=2" /></td>
                    <td>
                        <b style="font-size:15px;"><a href="<?php echo base_url() ?>product/<?php echo $product['id'] . '/' . url_title($product['title']) . '.html' ?>" target="_blank" style="color:inherit"><?php echo $product['title']?></a></b>
                        <br/>
                        <?php echo $product['body']?>
                        <?php
                        if ($product['pdf'] != '')
                        {
                            ?>
                        <br/>PDF File: <a target="_blank" href="<?php echo base_url()?>frontend/product_pdf_download/<?php echo $product['id']?>/<?php echo md5($product['id'].'suwichalala')?>/<?php echo url_title($product['title'])?>_Specification.pdf"><img src="<?php echo base_url()?>img/icons/pdf.png" /></a>
                            <?php
                        }
                        ?>
                        <br/>
                        Product category: <b><?php echo $product['term_title']?></b><br/>
                        Product Group: <b><?php echo $product['group']?></b>
                    </td>
                    
                    <td style="text-align: center;vertical-align: middle">
                        <a href="<?php echo base_url()?>admin/product_edit/<?php echo $product['id']?>" class="button">Edit</a>
                        <a href="<?php echo base_url()?>admin/product_delete/<?php echo $product['id']?>" class="button" onclick="return confirm('Confirm to delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>        
        </table>

    <?php
endif;
?>

</div>

