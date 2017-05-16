<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>FSNS Thailand - Administration Area </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo site_url('assets/admin/css/bootstrap.min.css');?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo site_url('assets/admin/css/AdminLTE.min.css');?>">

  <link rel="stylesheet" href="<?php echo site_url('assets/admin/css/_all-skins.min.css');?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>FSNS</b> Thailand</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">
        <?php echo validation_errors(); ?>
      </p>

      <form action="<?php echo base_url('admin'); ?>" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Username" maxlength="50" name="username" required>
          <span class="fa fa-user-md form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" maxlength="50" minlength="6" placeholder="Password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-12" align="content">
            <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha_key'); ?>"></div>
            <hr>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">


      </div>

    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo site_url('assets/admin/js/jquery-2.2.3.min.js');?>"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="<?php echo site_url('assets/admin/js/bootstrap.min.js');?>"></script>
  <!-- FastClick -->
  <script src="<?php echo site_url('assets/admin/js/fastclick.js');?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo site_url('assets/admin/js/app.min.js');?>"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>
