<section>
    <section id="product-detail">

        <div id="membber-form">


            <div class="col-6" id="div-login">
                <div align="center"><h2>Member Login</h2></div>
                <form action="<?php echo base_url('login'); ?>" method="post" id="login-form">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" class="input" placeholder="Email address" maxlength="100"
                               required>
                    </div>

                    <div>
                        <label>Password</label>
                        <input type="password" name="password" class="input" placeholder="Password" maxlength="100"
                               required>
                    </div>

                    <div class="g-recaptcha" id="re-form-login"
                    ></div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button class="btn" type="submit" id="submit-login">Login</button>
                    <div id="link-forgot-password"><a href="#">+ Forgot password?</a> </div>
                </form>
            </div>
            <div class="col-6" id="div-forgot">
                <form action="<?php echo base_url('forgot-password'); ?>" method="post" id="forgot-form">
                    <div align="center"><h2>Forgot password?</h2></div>

                    <div>
                        <label>Enter email to reset password.</label>
                        <input type="email" name="email" class="input" placeholder="example@domain.com" maxlength="200"
                               required>
                    </div>
                    <div class="g-recaptcha" id="re-form-reset"
                    ></div>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                           value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button class="btn" type="submit" id="submit-forgot">Reset password</button>
                    <div id="link-login"><a href="#">+ Go to Login page</a> </div>
                </form>
            </div>
            <div class="clearfix"></div>


        </div>
    </section>
</section>
<script>

    $(document).ready(function () {
        setTimeout(function () {
            $('.g-recaptcha').each(function () {
                var widgetId = grecaptcha.render(this, {'sitekey': '<?php echo $this->config->item('recaptcha_key'); ?>'});
                $(this).attr('data-widget-id', widgetId);
            });
        }, 1000);

    });
</script>