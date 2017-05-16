<center style="padding-top:150px;">
    <div style="width:400px;" class="ui-form ui-widget-content ui-corner-all">
        <div class="ui-widget-header" style="border:0;padding:10px;">
            <?php echo ADMIN_TITLE?>
        </div>
        <?php 
        echo form_open('admin',array('style'=>'padding:10px;'))
        ?>
            <table>
                <!--
                <tr>
                    <td >Email</td>
                    <td><?php echo form_input('email')?></td>
                </tr>
                -->
                <tr>
                    <td style="width:80px;">Password</td>
                    <td><?php echo form_password('password')?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" class="button" value="Login"></td>
                    
                </tr>
            </table>
        <?php
        echo form_close();
        ?>
    </div>
</center>
<script>
    $(function() {
        $('input[name="password"]').focus();
    });
</script>
