<div class="wrapper-content product-category">
    
    <?php
    if (isset($_GET['status'])) {
        ?>
        <div class="ui-state-active ui-corner-all" style="padding: 1em;margin:0 0 10px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <?php
           
            if ($_GET['status'] == 'update_complete')
                echo 'Slideshow is <b>updated!</b>';
           
            ?>
        </div>
        <?php
    }
    ?>
    
    <table>
        <thead class="ui-state-default" style="color:white">
        <th>Slideshow Image (Size:920x380px)</th>
        <th style="width:200px;">Caption</th>
        
        <th style="width:80px;">Destination Url</th>
        <th style="width:20px;">Weight</th>
        <th style="width:150px;">Command</th>
        </thead>
        <tbody>
            <?php
            foreach ($slideshows as $slideshow) {
                ?>
                <form enctype="multipart/form-data" action="<?php echo base_url()?>admin/slideshow_edit/<?php echo $slideshow['slideshow_id']?>" method="POST">
                <tr <?php echo ($slideshow['slideshow_image'] != '' && $slideshow['slideshow_caption'] != '')?'style="background:#BAF46E"':'style="background:#f1b1bf"'?>>
                    <td>
                        <?php
                        if ($slideshow['slideshow_image'] != '')
                        {
                            ?>
                            <img src="<?php echo base_url()?>timthumb.php?src=<?php echo base_url()?>uploads/slideshow/<?php echo $slideshow['slideshow_image']?>&w=350&h=140&zc=2" />
                            <?php
                        }
                        ?>
                        <input type="file" name="userfile" size="20" />
                    </td>
                    <td><input style="width:200px;" type="text" name="slideshow_caption" value="<?php echo $slideshow['slideshow_caption']?>"></td>
                    
                    <td><input style="width:80px;" type="text" name="slideshow_url" value="<?php echo $slideshow['slideshow_url']?>"></td>
                    <td><input type="text" name="weight" value="<?php echo $slideshow['weight']?>" style="width:40px;text-align:center"></td>
                    <td>
                        <input type="submit" value="Save" class="button"/>
                        <input type="submit" name="delete" value="Delete" class="button" onclick="return confirm('Are you confirm to delete this slideshow?')"/>
                    </td>
                </tr>
                </form>
                <?php
            }
            ?>

        </tbody>
    </table>
</div>