<section>
    <section id="product-detail">

        <div id="membber-form">


            <div class="col-6 wpcf7" id="div-login">
                <div align="center"><h2>Member Login</h2></div>
                <form action="<?php echo base_url('login'); ?>" method="post" id="login-form">
                    <fieldset>
                        <label>Email</label>
                        <input type="email" name="email" class="wpcf7-text" placeholder="Email address" maxlength="100"
                               required>
                    </fieldset>

                    <fieldset>
                        <label>Password</label>
                        <input type="password" name="password" class="wpcf7-text" placeholder="Password" maxlength="100"
                               required>
                    </fieldset>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button class="wpcf7-submit" type="submit" id="submit-login">Login</button>

                    <div align="right">
                        <a href="<?php echo base_url('login#forgot-password'); ?>" id="link-forgot-password">+ Forgot password?</a>
                        <br>
                        <a href="<?php echo base_url('login#register'); ?>" id="link-register">+ Don't have account? Register here.</a></div>

                </form>
            </div>
            <div class="col-6 wpcf7" id="div-forgot">
                <form action="<?php echo base_url('forgot-password'); ?>" method="post" id="forgot-form">
                    <div align="center"><h2>Forgot password?</h2></div>

                    <fieldset>
                        <label>Enter email to reset password.</label>
                        <input type="email" name="email" class="wpcf7-text" placeholder="example@domain.com"
                               maxlength="200"
                               required>
                    </fieldset>
                    <fieldset>
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                               value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <button class="wpcf7-submit" type="submit" id="submit-forgot">Reset password</button>
                        <div class="link-login"><a href="<?php echo base_url('login#login'); ?>">+ Already have account? Login here.</a></div>

                    </fieldset>

                </form>
            </div>
            <div class="clearfix"></div>

            <div id="div-register" class="wpcf7">
                <div align="center"><h2>Member Register</h2></div>

                <form action="<?php echo base_url('register'); ?>" method="post" id="register-form">
                    <div class="col-6">
                        <div class="box-border">
                            <h5>ข้อมูลผู้ใช้งาน(account information)</h5>
                            <fieldset>
                                <label>Email (ใช้เป็นชื่อใช้งาน / Will be your username)<span class="required-star">*</span></label>
                                <input type="email" name="email" class="wpcf7-text" placeholder="Email address" maxlength="100"
                                       required>
                            </fieldset>
                            <fieldset>
                                <label>โทรศัพท์ (Tel)<span class="required-star">*</span></label>
                                <input type="text" name="phone" class="wpcf7-text" placeholder="Phone number" maxlength="20" required>
                            </fieldset>
                            <fieldset>
                                <label>โทรสาร (Fax)</label>
                                <input type="text" name="fax" class="wpcf7-text" placeholder="Fax number" maxlength="20">
                            </fieldset>
                            <fieldset>
                                <label>ชื่อผู้ติดต่อ (Contact Person)<span class="required-star">*</span></label>
                                <input type="text" name="name" class="wpcf7-text" placeholder="Name" maxlength="200" required>
                            </fieldset>
                            <fieldset>
                                <label>รหัสผ่าน <span class="span-info-text">(อย่างน้อย 6 ตัวอักษร / At least 6 Characters)</span><span class="required-star">*</span></label>
                                <input type="password" minlength="6" name="password" class="wpcf7-text" placeholder="Password" maxlength="100"
                                       required>
                            </fieldset>
                            <fieldset>
                                <label>ยืนยันรหัสผ่าน (Confirm Password)<span class="required-star">*</span></label>
                                <input type="password" name="re-password" class="wpcf7-text" placeholder="Confirm Password"
                                       maxlength="100" minlength="6" required>
                            </fieldset>

                            <fieldset>
                                <label>ประเภทลูกค้า (Account Type)<span class="required-star">*</span></label>
                                <select name="type" class="wpcf7-text" id="account-type" required>
                                    <option value="personal">บุคคลธรรมดา (Personal)</option>
                                    <option value="business">นิติบุคคล (Business)</option>
                                </select>
                            </fieldset>
                            <fieldset class="biz">
                                <label>ชื่อในนิติบุคคล (Business Name) <span class="span-info-text">Ex: บจก. รักสะอาด</span><span class="required-star">*</span></label>
                                <input type="text" name="business_name" class="wpcf7-text" placeholder="Business Name" maxlength="200">
                            </fieldset>
                            <fieldset class="biz">
                                <label>เลขประจำตัวผู้เสียภาษี (Tax ID)<span class="required-star">*</span></label>
                                <input type="text" name="business_number" class="wpcf7-text" placeholder="Business Number" maxlength="50">
                            </fieldset>
                            <fieldset class="biz">
                                <label>เลขที่สาขา (Business Branch)<span class="required-star">*</span> <span class="span-info-text">Ex : 00000 หมายถึงสำนักงานใหญ่</span></label>
                                <input type="text" name="business_branch" class="wpcf7-text" placeholder="Business Branch" value="00000" minlength="5" maxlength="5">
                            </fieldset>
                            <fieldset class="biz">
                                <label>ที่อยู่ (Business Address)<span class="required-star">*</span></label>
                                <textarea name="business_address" class="wpcf7-text" placeholder="Business Address" rows="4" ></textarea>
                            </fieldset>
                            <fieldset class="biz">
                                <label>จังหวัด (Province)<span class="required-star">*</span></label>
                                <?php echo form_dropdown('business_province',list_province(),'','class="wpcf7-text"'); ?>
                            </fieldset>
                            <fieldset class="biz">
                                <label>หมายเหตุ(Note)</label>
                                <textarea name="business_note" class="wpcf7-textarea" id="business_note" cols="30" rows="4"></textarea>
                            </fieldset>
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="box-border">
                            <h5>ที่อยู่สำหรับ ใบเสร็จ/ใบกำกับภาษี(Billing Address)</h5>
                            <fieldset>
                                <label>ชื่อผู้รับ(Billing Name)<span class="required-star">*</span></label>
                                <input type="text" name="billing_name" id="billing_name" class="wpcf7-text"
                                       maxlength="200"
                                       value=""
                                       required>
                            </fieldset>

                            <fieldset>
                                <label>ที่อยู่(Billing Address)<span class="required-star">*</span></label>
                                <textarea name="billing_address" id="billing_address" class="wpcf7-text"
                                          rows="4"
                                          required></textarea>
                            </fieldset>
                            <fieldset>
                                <label>จังหวัด(Billing Province)<span class="required-star">*</span></label>
                                <?php echo form_dropdown('billing_province', list_province(), '', 'class="wpcf7-text" id="billing_province" required'); ?>
                            </fieldset>
                            <fieldset>
                                <label>รหัสไปรษณีย์(Billing Zip)<span class="required-star">*</span></label>
                                <input type="text" name="billing_zip" id="billing_zip" class="wpcf7-text number"
                                       placeholder="Zip code"
                                       maxlength="5"
                                       value=""
                                       minlength="5" required>
                            </fieldset>

                        </div>
                        <div class="box-border">
                            <h5>ที่อยู่สำหรับจัดส่งสินค้า(Shipping information)</h5>

                            <fieldset>
                                <input type="checkbox" style="float: left;" id="clone-address"> <label style="width:90%;margin-top: 4px;" for="clone-address">ใช้ที่อยู่เดียวกับ ใบเสร็จ/ใบกำกับภาษี(Billing Address)</label>
                            </fieldset>

                            <fieldset>
                                <label>ชื่อผู้รับสินค้า(Shipping Name)<span class="required-star">*</span></label>
                                <input type="text" name="shipping_name" id="shipping_name" class="wpcf7-text" placeholder="Shipping Name" maxlength="200" required>
                            </fieldset>

                            <fieldset>
                                <label>ที่อยู่จัดสิ่งสินค้า(Shipping Address)<span class="required-star">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" class="wpcf7-text" placeholder="Shipping Address" rows="4" required></textarea>
                            </fieldset>
                            <fieldset>
                                <label>จังหวัด(Shipping Province)<span class="required-star">*</span></label>
                                <?php echo form_dropdown('shipping_province',list_province(),'','class="wpcf7-text" id="shipping_province" required'); ?>
                            </fieldset>
                            <fieldset>
                                <label>รหัสไปรษณีย์(Shipping Zip)<span class="required-star">*</span></label>
                                <input type="text" name="shipping_zip" id="shipping_zip" class="wpcf7-text number" placeholder="Zip code" maxlength="5" minlength="5" required>
                            </fieldset>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_key'); ?>"></div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <fieldset style="text-align: center"><button class="wpcf7-submit" style="float: none;" type="submit" id="submit-register">I Accept Term of use and register now.</button></fieldset>
                    <div class="link-login" style="float: none; text-align: center;"><a href="<?php echo base_url('login#login'); ?>">+ Already have account? Login here.</a></div>
                </form>
            </div>

        </div>
    </section>
</section>
<script>
        $(document).ready(function () {
            if(window.location.hash) {
                var id = window.location.hash.substring(1);
                $('#link-'+id).click();
                $('.link-'+id).click();
            }
            $(window).on('hashchange', function() {
                var id = window.location.hash.substring(1);
                $('#link-'+id).click();
                $('.link-'+id).click();
            });
            $(document).on('change','#clone-address',function(){
                if($(this).is(":checked")){
                    $('#shipping_name').val($('#billing_name').val());
                    $('#shipping_address').val($('#billing_address').val());
                    $('#shipping_province').val($('#billing_province').val());
                    $('#shipping_zip').val($('#billing_zip').val());
                }
            });

        });
</script>