<div class="wrapper-content product-category">
    
    <?php
    if (isset($_GET['status']))
    {
        ?>
        <div class="ui-state-active ui-corner-all" style="padding: 1em;margin:0 0 10px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        <?php
        if ($_GET['status'] == 'add_complete') echo 'Content is <b>added!</b>';
        if ($_GET['status'] == 'update_complete') echo 'Content is <b>updated!</b>';
        if ($_GET['status'] == 'delete_complete') echo 'Content is <b>deleted!</b>';
        ?>
        </div>
        <?php
    }
   
    
    ?>
    
    <a href="<?php echo base_url() ?>admin/content_add" title="Create a new product" class="button">Create a new content</a>

    <?php
    if (count($content_list) > 0):
        ?>
        <table>
            <thead class="ui-state-default">
                <!--<th style="width:100px;">Cover</th>-->
                <th>Content title</th>
                <!--
                <th >Is page?</th>
                <th>Is Slide show?</th>
                -->
                <th style="width:150px;">Commands</th>
            </thead>
            <tbody>
                <?php
                foreach ($content_list as $content) {
                    ?>
                <tr>
                    <!--<td style="vertical-align: middle"><img src="<?php echo base_url()?>timthumb.php?src=<?php echo base_url().UPLOAD_PATH.'/'.$content['cover']?>&w=100&h=100&zc=2" /></td>-->
                    <td>
                        <b style="font-size:15px;"><a href="<?php echo base_url()?>news/<?php echo $content['id']?>/<?php echo url_title($content['title'])?>.html" target="_blank" style="color:inherit"><?php echo $content['title']?></a></b>
                        <p>
                            <?php echo character_limiter(strip_tags($content['body']),350)?>
                        </p>
                    </td>
                    <!--
                    <td style="vertical-align: middle;text-align: center;">
                        <?php
                        if ($content['is_page'])
                        {
                            echo '<img src="'.base_url().'images/icons/tick.png">';
                        }
                        else
                        {
                             echo '<img src="'.base_url().'images/icons/cross.png">';
                        }
                        
                        ?>
                    </td>
                    <td style="vertical-align: middle;text-align: center;">
                         <?php
                        if ($content['is_slideshow'])
                        {
                            echo '<img src="'.base_url().'images/icons/tick.png">';
                        }
                        else
                        {
                             echo '<img src="'.base_url().'images/icons/cross.png">';
                        }
                        
                        ?>
                       
                    </td>
                    -->
                    
                    <td style="text-align: center;vertical-align: middle">
                        <a href="<?php echo base_url()?>admin/content_edit/<?php echo $content['id']?>" class="button">Edit</a>
                        <?php
                        if (!$content['is_page']):
                        ?>
                        <a href="<?php echo base_url()?>admin/content_delete/<?php echo $content['id']?>" class="button" onclick="return confirm('Confirm to delete this content?')">Delete</a>
                        <?php
                        endif;
                        ?>
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

