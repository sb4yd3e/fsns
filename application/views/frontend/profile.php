<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <div align="center"><h2>My Profile</h2></div>

            <form action="<?php echo base_url('profile'); ?>" method="post" id="profile-form">
                <div class="col-6">
                    <div>
                        <label>Email</label>
                        <input type="email" class="input" placeholder="Email address" maxlength="100"
                               value="<?php echo $user['email']; ?>"
                               disabled>
                    </div>
                    <div>
                        <label>Name</label>
                        <input type="text" name="name" class="input" placeholder="Name" maxlength="200" value="<?php echo $user['name']; ?>" required>
                    </div>
                    <div>
                        <label>New Password</label>
                        <input type="password" name="password" class="input" placeholder="Password" maxlength="100"
                        >
                    </div>
                    <div>
                        <label>Confirm new Password</label>
                        <input type="password" name="re-password" class="input" placeholder="Confirm Password"
                               maxlength="100">
                    </div>
                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone" class="input" placeholder="Phone number" maxlength="20"
                               value="<?php echo $user['phone']; ?>" required>
                    </div>

                </div>
                <div class="col-6">
                    <div>
                        <label>Account Type</label>
                        <input type="text" class="input" maxlength="100"
                               value="<?php echo strtoupper($user['account_type']); ?>"
                               disabled>
                    </div>
                    <?php if ($user['account_type'] == 'business') { ?>
                        <div class="biz">
                            <label>Business Name</label>
                            <input type="text" name="business_name" class="input" placeholder="Business Name" value="<?php echo $user['business_name']; ?>"
                                   maxlength="200" required>
                        </div>
                        <div class="biz">
                            <label>Business Number</label>
                            <input type="text" name="business_number" class="input" placeholder="Business Number" value="<?php echo $user['business_number']; ?>"
                                   maxlength="50" required>
                        </div>
                        <div class="biz">
                            <label>Business Address</label>
                            <textarea name="business_address" class="input" placeholder="Business Address"
                                      rows="4" required><?php echo $user['business_address']; ?></textarea>
                        </div>
                    <?php } ?>

                    <div>
                        <label>Shipping Name</label>
                        <input type="text" name="shipping_name" class="input" placeholder="Shipping Name"
                               maxlength="200"  value="<?php echo $user['shipping_name']; ?>" required>
                    </div>

                    <div>
                        <label>Shipping Address</label>
                        <textarea name="shipping_address" class="input" placeholder="Shipping Address" rows="4"
                                  required><?php echo $user['shipping_address']; ?></textarea>
                    </div>
                    <div>
                        <label>Shipping Province</label>
                        <?php echo form_dropdown('shipping_province', list_province(), $user['shipping_province'], 'class="input" style="width: calc(100% - 40px);height: 34px;" required'); ?>
                    </div>
                    <div>
                        <label>Shipping Zip</label>
                        <input type="text" name="shipping_zip" class="input" placeholder="Zip code" value="<?php echo $user['shipping_zip']; ?>" minlength="5" maxlength="5"
                               required>
                    </div>

                </div>
                <div class="clearfix"></div>

                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                <button class="btn" type="submit" id="submit-profile">Save Change</button>
            </form>
        </div>
    </section>
</section>