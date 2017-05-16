<div class="wrapper-content product-add">
    <?php echo validation_errors(); ?>
   
    <form action="<?php echo base_url() ?>admin/content_edit/<?php echo $content['id']?>" method="post" class="ui-form" enctype="multipart/form-data">
        <label>Replace content cover (Size: 450x235px):</label>
        <img src="<?php echo base_url()?>timthumb.php?src=<?php echo base_url().NEWS_PATH.'/'.$content['cover']?>&w=100" style="border:2px solid #eee"/>
        <br/>
        <input type="file" name="cover" size="20" />

        <label>Content title*:</label>
        <input name="title" value="<?php echo set_value('title',$content['title']) ?>" style="width:200px;"/>

        <label>Content body*:</label>
        <textarea name="body" style="width:200px;height:100px;" id="body"><?php echo set_value('body',$content['body']) ?></textarea>

        
        <br/>
        <input type="submit" value="Edit this content" class="button"/>

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