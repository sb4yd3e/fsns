<div class="wrapper-content product-add">
    <?php
    if (isset($done))
    {
        echo $done;
    }
    ?>
    <form action="<?php echo base_url() ?>admin/setting" method="post" class="ui-form">

<!---
        <fieldset style="border:1px solid #eee;padding:10px;margin-top:20px;">
            <legend style="font-weight:bold;font-size:18px;">Contact us</legend>
            <div style="margin-left:20px;">
                <label>Email for Contact us:</label>
                <input type="text" name="email_for_contact" style="width:200px" value="<?php echo set_value('email_for_contact', $setting['email_for_contact']) ?>"/>
            </div>
        </fieldset>
-->
        
        <fieldset style="border:1px solid #eee;padding:10px;margin-top:20px;">
            <legend style="font-weight:bold;font-size:18px;">Admin Password</legend>
            <div style="margin-left:20px;">
                <label>Old Password:</label>
                <input type="password" name="old_password" style="width:200px" value=""/>
                <label>New Password:</label>
                <input type="password" name="new_password" style="width:200px" value=""/>
                <label>Confirm new Password:</label>
                <input type="password" name="new_password_confirm" style="width:200px" value=""/>
                
            </div>
        </fieldset>
        
        <br/>
        <input type="submit" class="button" value="Save a setting" />
    </form>
    
</div>