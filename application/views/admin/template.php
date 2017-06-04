<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?> - FSNS Thailand</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo site_url('css/smoothness/jquery-ui.min.css'); ?>" type="text/css"
          media="screen" title="default"/>
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo site_url('assets/admin/css/bootstrap.min.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo site_url('assets/admin/css/AdminLTE.min.css'); ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo site_url('assets/admin/css/_all-skins.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/admin/datatables/dataTables.bootstrap.css'); ?>">
    <link rel="stylesheet" media="screen" type="text/css"
          href="<?php echo site_url('assets/admin/colorpicker/css/colorpicker.css'); ?>"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo site_url('assets/admin/datetimepicker/jquery.datetimepicker.css'); ?>"
    / >
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url('admin'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>FSNS</b> Thailand</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>FSNS</b> Thailand</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo site_url('assets/admin/img/user2-160x160.png'); ?>" class="user-image"
                                 alt="User Image">
                            <span class="hidden-xs"><?php echo $user_name; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo site_url('assets/admin/img/user2-160x160.png'); ?>"
                                     class="img-circle" alt="User Image">

                                <p>
                                    <?php echo $user_name; ?>
                                    <small><?php echo $user_group; ?></small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo base_url('admin/admin/edit/' . $user_id); ?>"
                                       class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url('admin/logout'); ?>" class="btn btn-default btn-flat">Sign
                                        out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?php echo site_url('assets/admin/img/user2-160x160.png'); ?>" class="img-circle"
                         alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php echo $user_name; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>

                <li>
                    <a href="<?php echo site_url('admin/orders/index/'); ?>">
                        <i class="fa fa-shopping-basket"></i> <span>Orders</span>
                    </a>

                </li>
                <?php if (is_group(array('admin'))) { ?>
                    <li>
                        <a href="<?php echo site_url('admin/admin/index'); ?>">
                            <i class="fa fa-user-md"></i> <span>Admin/Sale Management</span>
                        </a>

                    </li>

                    <li>
                        <a href="<?php echo site_url('admin/news/index'); ?>">
                            <i class="fa fa-newspaper-o"></i> <span>News Management</span>
                        </a>

                    </li>
                    <li>
                        <a href="<?php echo site_url('admin/category/index'); ?>">
                            <i class="fa fa-cube"></i> <span>Product Categories</span>
                        </a>

                    </li>

                    <li>
                        <a href="<?php echo site_url('admin/products/index'); ?>">
                            <i class="fa fa-cubes"></i> <span>Products</span>
                        </a>

                    </li>
                    <li>
                        <a href="<?php echo site_url('admin/coupons/index'); ?>">
                            <i class="fa fa-cc-discover"></i> <span>Promotion Coupons</span>
                        </a>

                    </li>
                <?php } ?>
                <li>
                    <a href="<?php echo site_url('admin/members/index'); ?>">
                        <i class="fa fa-users"></i> <span>Members</span>
                    </a>

                </li>
                <?php if (is_group(array('admin'))) { ?>
                    <li>
                        <a href="<?php echo site_url('admin/banner'); ?>">
                            <i class="fa fa-picture-o"></i> <span>Popup Banner</span>
                        </a>

                    </li>
                    <li>
                        <a href="<?php echo site_url('admin/setting'); ?>">
                            <i class="fa fa-cogs"></i> <span>Settings</span>
                        </a>
                    </li>


                    <!--<li>
                        <a href="<?php /*echo site_url('admin/log'); */ ?>">
                            <i class="fa fa-bars"></i> <span>Logs</span>
                        </a>
                    </li>-->
                <?php } ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <?php
            echo $content;
            ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright © <?php echo date("Y"); ?> <a href="http://www.fsns-thailand.com" target="_blank">FSNS
                Thailand </a></strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">

        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->

        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo site_url('assets/admin/js/jquery-2.2.3.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/fastclick.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/app.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/js/notify.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo site_url('js/jquery-ui.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo site_url('js/ckeditor/ckeditor.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/admin/colorpicker/js/colorpicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/admin/colorpicker/js/eye.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/admin/colorpicker/js/utils.js'); ?>"></script>
<script src="<?php echo site_url('assets/admin/datetimepicker/build/jquery.datetimepicker.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/admin/js/order.js'); ?>"></script>
<script>
    <?php echo $js; ?>
</script>
</body>
</html>
