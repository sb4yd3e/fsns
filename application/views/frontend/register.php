<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form" class="wpcf7">
            <div align="center"><h2>Member Register</h2></div>

            <form action="<?php echo base_url('register'); ?>" method="post" id="register-form">
                <div class="col-6">
                    <fieldset>
                        <label>Email</label>
                        <input type="email" name="email" class="wpcf7-text" placeholder="Email address" maxlength="100"
                               required>
                    </fieldset>
                    <fieldset>
                        <label>Name</label>
                        <input type="text" name="name" class="wpcf7-text" placeholder="Name" maxlength="200" required>
                    </fieldset>
                    <fieldset>
                        <label>Password</label>
                        <input type="password" name="password" class="wpcf7-text" placeholder="Password" maxlength="100"
                               required>
                    </fieldset>
                    <fieldset>
                        <label>Confirm Password</label>
                        <input type="password" name="re-password" class="wpcf7-text" placeholder="Confirm Password"
                               maxlength="100" required>
                    </fieldset>
                    <fieldset>
                        <label>Phone</label>
                        <input type="text" name="phone" class="wpcf7-text" placeholder="Phone number" maxlength="20" required>
                    </fieldset>

                </div>
                <div class="col-6">
                    <fieldset>
                        <label>Account Type</label>
                        <select name="type" class="wpcf7-text" id="account-type" required>
                            <option value="personal">บุคคลธรรมดา</option>
                            <option value="business">นิติบุคคล</option>
                        </select>
                    </fieldset>
                    <fieldset class="biz">
                        <label>Business Name</label>
                        <input type="text" name="business_name" class="wpcf7-text" placeholder="Business Name" maxlength="200">
                    </fieldset>
                    <fieldset class="biz">
                        <label>Business Tax ID</label>
                        <input type="text" name="business_number" class="wpcf7-text" placeholder="Business Number" maxlength="50">
                    </fieldset>
                    <fieldset class="biz">
                        <label>Business Address</label>
                        <textarea name="business_address" class="wpcf7-text" placeholder="Business Address" rows="4" ></textarea>
                    </fieldset>


                    <fieldset>
                        <label>Shipping Name</label>
                        <input type="text" name="shipping_name" class="wpcf7-text" placeholder="Shipping Name" maxlength="200" required>
                    </fieldset>

                    <fieldset>
                        <label>Shipping Address</label>
                        <textarea name="shipping_address" class="wpcf7-text" placeholder="Shipping Address" rows="4" required></textarea>
                    </fieldset>
                    <fieldset>
                        <label>Shipping Province</label>
                        <?php echo form_dropdown('shipping_province',list_province(),'','class="wpcf7-text" required'); ?>
                    </fieldset>
                    <fieldset>
                        <label>Shipping Zip</label>
                        <input type="text" name="shipping_zip" class="wpcf7-text" placeholder="Zip code" maxlength="5" minlength="5" required>
                    </fieldset>

                </div>
                <div class="clearfix"></div>
                <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_key'); ?>"></div>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <fieldset style="text-align: center"><button class="wpcf7-submit" style="float: none;" type="submit" id="submit-register">I Accept Term of use and register now.</button></fieldset>
            </form>
        </div>
    </section>
</section>