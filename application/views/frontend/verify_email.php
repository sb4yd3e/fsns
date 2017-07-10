<section id="content-wrapper">

    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form" style="min-height: 400px;">
            <div align="center"><h2>ยืนยันอีเมล</h2></div>
            <?php if($status=='danger'){ ?>
                <section class="error-box">
                    <p><?php echo $html; ?></p>
                </section>
            <?php }else{ ?>
                <section class="success-box">
                    <p><?php echo $html; ?></p>
                </section>
            <?php } ?>

        </div>
    </section>
</section>