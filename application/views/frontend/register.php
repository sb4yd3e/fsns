<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <div align="center"><h2>Member Register</h2></div>

            <form action="<?php echo base_url('register'); ?>" method="post" id="register-form">
                <div class="col-6">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" class="input" placeholder="Email address" maxlength="100"
                               required>
                    </div>
                    <div>
                        <label>Name</label>
                        <input type="text" name="name" class="input" placeholder="Name" maxlength="200" required>
                    </div>
                    <div>
                        <label>Password</label>
                        <input type="password" name="password" class="input" placeholder="Password" maxlength="100"
                               required>
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input type="password" name="re-password" class="input" placeholder="Confirm Password"
                               maxlength="100" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone" class="input" placeholder="Phone number" maxlength="20" required>
                    </div>

                </div>
                <div class="col-6">
                    <div>
                        <label>Account Type</label>
                        <select name="type" class="input" id="account-type" style="width: calc(100% - 40px);height: 34px;" required>
                            <option value="personal">Personal</option>
                            <option value="business">Business</option>
                        </select>
                    </div>
                    <div class="biz">
                        <label>Business Name</label>
                        <input type="text" name="business_name" class="input" placeholder="Business Name" maxlength="200">
                    </div>
                    <div class="biz">
                        <label>Business Number</label>
                        <input type="text" name="business_number" class="input" placeholder="Business Number" maxlength="50">
                    </div>
                    <div class="biz">
                        <label>Business Address</label>
                        <textarea name="business_address" class="input" placeholder="Business Address" rows="4" ></textarea>
                    </div>


                    <div>
                        <label>Shipping Name</label>
                        <input type="text" name="shipping_name" class="input" placeholder="Shipping Name" maxlength="200" required>
                    </div>

                    <div>
                        <label>Shipping Address</label>
                        <textarea name="shipping_address" class="input" placeholder="Shipping Address" rows="4" required></textarea>
                    </div>
                    <div>
                        <label>Shipping Province</label>
                        <?php echo form_dropdown('shipping_province',list_province(),'','class="input" style="width: calc(100% - 40px);height: 34px;" required'); ?>
                    </div>
                    <div>
                        <label>Shipping Zip</label>
                        <input type="text" name="shipping_zip" class="input" placeholder="Zip code" maxlength="5" minlength="5" required>
                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_key'); ?>"></div>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <button class="btn" type="submit" id="submit-register">I Accept Term of use and register now.</button>
            </form>
        </div>
    </section>
</section>