<div class="wrapper-content product-add">
    <?php echo validation_errors(); ?>
    <?php echo isset($error_upload) ? $error_upload : '' ?>
    <form action="<?php echo base_url() ?>admin/content_add" method="post" class="ui-form" enctype="multipart/form-data">
        
        <label>Content Cover (Size: 450x235px)*:</label>
        <input type="file" name="cover" size="20" />

        <label>Content title*:</label>
        <input name="title" value="<?php echo set_value('title') ?>" style="width:200px;"/>

        <label>Content body*:</label>
        <textarea name="body" style="width:200px;height:100px;" id="body"><?php echo set_value('body') ?></textarea>


        <br/>
        <input type="submit" value="Create a new content" class="button"/>

        <a href="<?php echo base_url() ?>admin/content_list" class="button">Back to content list</a>
    </form>

</div>

<script type="text/javascript">
    $(function(){
        CKEDITOR.replace( 'body' ,{
            filebrowserBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Images',
            filebrowserFlashBrowseUrl : '<?php echo base_url()?>js/ckfinder/ckfinder.html?type=Flash',
            filebrowserUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : '<?php echo base_url()?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
        $('#slideshow_upload').hide();
        if ($('#is_slideshow').attr('checked') == 'checked')
        {
            $('#slideshow_upload').show();
        }
        $('#is_slideshow').click(function(){
            if ($(this).attr('checked') == 'checked')
            {
                $('#slideshow_upload').slideDown();
            }
            else
            {
                $('#slideshow_upload').slideUp();
            }
    });  
});
    
</script>