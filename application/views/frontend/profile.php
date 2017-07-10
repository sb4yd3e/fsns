<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form" class="wpcf7">
            <div align="center"><h2>My Profile</h2></div>

            <form action="<?php echo base_url('profile'); ?>" method="post" id="profile-form">
                <div class="col-6">
                    <div class="box-border">
                        <h5>Account information</h5>
                        <fieldset>
                            <label>Email (ใช้เป็นชื่อใช้งาน / Will be your username)</label>
                            <input type="email" class="wpcf7-text" placeholder="Email address" maxlength="100"
                                   value="<?php echo $user['email']; ?>"
                                   disabled>
                        </fieldset>
                        <fieldset>
                            <label>โทรศัพท์ (Tel)<span class="required-star">*</span></label>
                            <input type="text" name="phone" class="wpcf7-text" placeholder="Phone number" maxlength="20"
                                   value="<?php echo $user['phone']; ?>" required>
                        </fieldset>
                        <fieldset>
                            <label>โทรสาร (Fax)</label>
                            <input type="text" name="fax" class="wpcf7-text" value="<?php echo $user['fax']; ?>"
                                   placeholder="Fax number" maxlength="20">
                        </fieldset>
                        <fieldset>
                            <label>ชื่อผู้ติดต่อ (Contact Person)<span class="required-star">*</span></label>
                            <input type="text" name="name" class="wpcf7-text" placeholder="Name" maxlength="200"
                                   value="<?php echo $user['name']; ?>" required>
                        </fieldset>
                        <fieldset>
                            <label>รหัสผ่านใหม่ <span class="span-info-text">(อย่างน้อย 6 ตัวอักษร / At least 6 Characters)</span></label>
                            <input type="password" name="password" class="wpcf7-text" placeholder="Password"
                                   maxlength="100"
                            >
                        </fieldset>
                        <fieldset>
                            <label>ยืนยันรหัสผ่านใหม่ (Confirm new Password)</label>
                            <input type="password" name="re-password" class="wpcf7-text" placeholder="Confirm Password"
                                   maxlength="100">
                        </fieldset>
                        <fieldset>
                            <label>ประเภทลูกค้า (Account Type)<span class="required-star">*</span></label>
                            <input type="text" class="wpcf7-text" maxlength="100"
                                   value="<?php echo strtoupper($user['account_type']); ?>"
                                   disabled>
                        </fieldset>
                        <?php if ($user['account_type'] == 'business') { ?>
                            <fieldset>
                                <label>ชื่อในนิติบุคคล (Business Name) <span
                                            class="span-info-text">Ex: บจก. รักสะอาด</span><span
                                            class="required-star">*</span></label>
                                <input type="text" name="business_name" class="wpcf7-text" placeholder="Business Name"
                                       value="<?php echo $user['business_name']; ?>"
                                       maxlength="200" required>
                            </fieldset>
                            <fieldset>
                                <label>เลขประจำตัวผู้เสียภาษี (Tax ID)<span class="required-star">*</span></label>
                                <input type="text" name="business_number" class="wpcf7-text"
                                       placeholder="Business Number" value="<?php echo $user['business_number']; ?>"
                                       maxlength="50" required>
                            </fieldset>
                            <fieldset>
                                <label>เลขที่สาขา (Business Branch)<span class="required-star">*</span> <span
                                            class="span-info-text">Ex : 00000 หมายถึงสำนักงานใหญ่</span></label>
                                <input type="text" name="business_branch"
                                       value="<?php echo $user['business_branch']; ?>" class="wpcf7-text"
                                       placeholder="Business Branch" value="00000" minlength="5" maxlength="5" required>
                            </fieldset>
                            <fieldset>
                                <label>ที่อยู่ (Business Address)<span class="required-star">*</span></label>
                                <textarea name="business_address" class="wpcf7-text" placeholder="Business Address"
                                          rows="4" required><?php echo $user['business_address']; ?></textarea>
                            </fieldset>
                            <fieldset>
                                <label>จังหวัด (Province)<span class="required-star">*</span></label>
                                <?php echo form_dropdown('business_province', list_province(), $user['business_province'], 'class="wpcf7-text" required'); ?>
                            </fieldset>
                            <fieldset>
                                <label>หมายเหตุ(Note)</label>
                                <textarea name="business_note" class="wpcf7-textarea" id="business_note" cols="30"
                                          rows="4"><?php echo $user['business_note']; ?></textarea>
                            </fieldset>
                        <?php } ?>
                    </div>

                </div>
                <div class="col-6">
                    <div class="box-border">
                        <h5>Shipping Address</h5>
                        <fieldset>
                            <label>ชื่อผู้รับสินค้า(Shipping Name)<span class="required-star">*</span></label>
                            <input type="text" name="shipping_name" class="wpcf7-text" placeholder="Shipping Name"
                                   maxlength="200" value="<?php echo $user['shipping_name']; ?>" required>
                        </fieldset>

                        <fieldset>
                            <label>ที่อยู่จัดสิ่งสินค้า(Shipping Address)<span class="required-star">*</span></label>
                            <textarea name="shipping_address" class="wpcf7-text" placeholder="Shipping Address" rows="4"
                                      required><?php echo $user['shipping_address']; ?></textarea>
                        </fieldset>
                        <fieldset>
                            <label>จังหวัด(Shipping Province)<span class="required-star">*</span></label>
                            <?php echo form_dropdown('shipping_province', list_province(), $user['shipping_province'], 'class="wpcf7-text"  required'); ?>
                        </fieldset>
                        <fieldset>
                            <label>รหัสไปรษณีย์(Shipping Zip)<span class="required-star">*</span></label>
                            <input type="text" name="shipping_zip" class="wpcf7-text" placeholder="Zip code"
                                   value="<?php echo $user['shipping_zip']; ?>" minlength="5" maxlength="5"
                                   required>
                        </fieldset>
                    </div>
                    <div class="box-border">
                        <h5>Billing Address</h5>
                        <fieldset>
                            <label> ชื่อผู้รับ(Billing Name)<span class="required-star">*</span></label>
                            <input type="text" name="billing_name" class="wpcf7-text"
                                   maxlength="200"
                                   value="<?php echo $user['billing_name'] ? $user['billing_name'] : $user['shipping_name']; ?>"
                                   required>
                        </fieldset>

                        <fieldset>
                            <label> ที่อยู่(Billing Address)<span class="required-star">*</span></label>
                            <textarea name="billing_address" class="wpcf7-text"
                                      rows="4"
                                      required><?php echo $user['billing_address'] ? $user['billing_address'] : $user['shipping_address']; ?></textarea>
                        </fieldset>
                        <fieldset>
                            <label> จังหวัด(Billing Province)<span class="required-star">*</span></label>
                            <?php echo form_dropdown('billing_province', list_province(), ($user['billing_province'] ? $user['billing_province'] : $user['shipping_province']), 'class="wpcf7-text"required'); ?>
                        </fieldset>
                        <fieldset>
                            <label> รหัสไปรษณีย์(Billing Zip)<span class="required-star">*</span></label>
                            <input type="text" name="billing_zip" id="billing_zip" class="wpcf7-text number"
                                   placeholder="Zip code"
                                   maxlength="5"
                                   value="<?php echo $user['billing_zip'] ? $user['billing_zip'] : $user['shipping_zip']; ?>"
                                   minlength="5" required>
                        </fieldset>
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