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
                        <a href="#" id="link-forgot-password">+ Forgot password?</a>
                        <br>
                        <a href="<?php echo base_url('register'); ?>">+ Don't have account? Register here.</a></div>

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
                        <div id="link-login"><a href="#">+ Already have account? Login here.</a></div>

                    </fieldset>

                </form>
            </div>
            <div class="clearfix"></div>


        </div>
    </section>
</section>
<script>

    //    $(document).ready(function () {
    //        setTimeout(function () {
    //            $('.g-recaptcha').each(function () {
    //                var widgetId = grecaptcha.render(this, {'sitekey': '<?php //echo $this->config->item('recaptcha_key'); ?>//'});
    //                $(this).attr('data-widget-id', widgetId);
    //            });
    //        }, 1000);
    //
    //    });
</script>