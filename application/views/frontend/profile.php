<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form" class="wpcf7">
            <div align="center"><h2>My Profile</h2></div>

            <form action="<?php echo base_url('profile'); ?>" method="post" id="profile-form">
                <div class="col-6">
                    <div class="box-border">
                        <h5>Account information</h5>
                        <fieldset>
                            <label>Email</label>
                            <input type="email" class="wpcf7-text" placeholder="Email address" maxlength="100"
                                   value="<?php echo $user['email']; ?>"
                                   disabled>
                        </fieldset>
                        <fieldset>
                            <label>Name</label>
                            <input type="text" name="name" class="wpcf7-text" placeholder="Name" maxlength="200"
                                   value="<?php echo $user['name']; ?>" required>
                        </fieldset>
                        <fieldset>
                            <label>New Password</label>
                            <input type="password" name="password" class="wpcf7-text" placeholder="Password"
                                   maxlength="100"
                            >
                        </fieldset>
                        <fieldset>
                            <label>Confirm new Password</label>
                            <input type="password" name="re-password" class="wpcf7-text" placeholder="Confirm Password"
                                   maxlength="100">
                        </fieldset>
                        <fieldset>
                            <label>Phone</label>
                            <input type="text" name="phone" class="wpcf7-text" placeholder="Phone number" maxlength="20"
                                   value="<?php echo $user['phone']; ?>" required>
                        </fieldset>
                    </div>
                    <div class="box-border">
                        <h5>Shipping Address</h5>
                        <fieldset>
                            <label>Shipping Name</label>
                            <input type="text" name="shipping_name" class="wpcf7-text" placeholder="Shipping Name"
                                   maxlength="200" value="<?php echo $user['shipping_name']; ?>" required>
                        </fieldset>

                        <fieldset>
                            <label>Shipping Address</label>
                            <textarea name="shipping_address" class="wpcf7-text" placeholder="Shipping Address" rows="4"
                                      required><?php echo $user['shipping_address']; ?></textarea>
                        </fieldset>
                        <fieldset>
                            <label>Shipping Province</label>
                            <?php echo form_dropdown('shipping_province', list_province(), $user['shipping_province'], 'class="wpcf7-text"  required'); ?>
                        </fieldset>
                        <fieldset>
                            <label>Shipping Zip</label>
                            <input type="text" name="shipping_zip" class="wpcf7-text" placeholder="Zip code"
                                   value="<?php echo $user['shipping_zip']; ?>" minlength="5" maxlength="5"
                                   required>
                        </fieldset>
                    </div>
                </div>
                <div class="col-6">
                    <div class="box-border">
                        <h5>Billing Address</h5>
                        <fieldset>
                            <label> Name</label>
                            <input type="text" name="billing_name" class="wpcf7-text"
                                   maxlength="200"
                                   value="<?php echo $user['billing_name'] ? $user['billing_name'] : $user['shipping_name']; ?>"
                                   required>
                        </fieldset>

                        <fieldset>
                            <label> Address</label>
                            <textarea name="billing_address" class="wpcf7-text"
                                      rows="4"
                                      required><?php echo $user['billing_address'] ? $user['billing_address'] : $user['shipping_address']; ?></textarea>
                        </fieldset>
                        <fieldset>
                            <label> Province</label>
                            <?php echo form_dropdown('billing_province', list_province(), ($user['billing_province'] ? $user['billing_province'] : $user['shipping_province']), 'class="wpcf7-text"required'); ?>
                        </fieldset>
                        <fieldset>
                            <label> Zip</label>
                            <input type="text" name="billing_zip" id="billing_zip" class="wpcf7-text number"
                                   placeholder="Zip code"
                                   maxlength="5"
                                   value="<?php echo $user['billing_zip'] ? $user['billing_zip'] : $user['shipping_zip']; ?>"
                                   minlength="5" required>
                        </fieldset>
                    </div>
                    <div class="box-border">
                        <h5>Business information</h5>
                        <fieldset>
                            <label>Account Type</label>
                            <input type="text" class="wpcf7-text" maxlength="100"
                                   value="<?php echo strtoupper($user['account_type']); ?>"
                                   disabled>
                        </fieldset>
                        <?php if ($user['account_type'] == 'business') { ?>
                            <fieldset>
                                <label>Business Name</label>
                                <input type="text" name="business_name" class="wpcf7-text" placeholder="Business Name"
                                       value="<?php echo $user['business_name']; ?>"
                                       maxlength="200" required>
                            </fieldset>
                            <fieldset>
                                <label>Business Tax ID</label>
                                <input type="text" name="business_number" class="wpcf7-text"
                                       placeholder="Business Number" value="<?php echo $user['business_number']; ?>"
                                       maxlength="50" required>
                            </fieldset>
                            <fieldset>
                                <label>Business Address</label>
                                <textarea name="business_address" class="wpcf7-text" placeholder="Business Address"
                                          rows="4" required><?php echo $user['business_address']; ?></textarea>
                            </fieldset>
                        <?php } ?>
                    </div>


                </div>
                <div class="clearfix"></div>

                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                <fieldset style="text-align: center">
                    <button class="wpcf7-submit" style="float: none;" type="submit" id="submit-profile">Save Change
                    </button>
                </fieldset>

            </form>
        </div>
    </section>
</section>