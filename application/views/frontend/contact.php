
<section class="page-title-container">
    <div class="container_12">
        <div class="page-title grid_12">
            <div class="title">
                <h1><?php echo $web_title ?></h1>
                
            </div>
        </div>
    </div>
</section>


<section id="content-wrapper">
    <div class="container_12">
        <div class="grid_8 map_canvas"></div>
        <article class="grid_4" style="margin-bottom: 0px;">
            <section class="grid_4 alpha omega">
                <h5>Food Service and Solution Co.,Ltd</br>
                    บริษัท ฟู้ด เซอร์วิส แอนด์ โซลูชั่น จำกัด</h5>

                <p>
                    29 S.Chalaemnimit,</br>
                    Bangkhlo, Bangkorlaem,</br>
                    Bangkok 10120
                </p>

                <p>
                    Tel: <a href="tel:0838392929">083-839-2929</a>, <a href="tel:0816152621">081-615-2621</a></br>
                    Fax: 02-6885755
                </p>
            </section>

            <section class="grid_4 alpha omega">
                <h5>CONTACT</h5>

                <ul class="contact-info">
                    <li>
                        <span class="text-color" style="width:20px;display:inline-block"><b>E:</b> </span> <a target="_blank" href="mailto:<?php echo $this->setting_data['email_for_contact']; ?>" style="font-size:13px;"><?php echo $this->setting_data['email_for_contact']; ?></a>
                    </li>
                    <li>
                        <span class="text-color" style="width:20px;display:inline-block"><b>W:</b> </span> <a target="_blank" href="http://www.fsns-thailand.com" style="font-size:13px;">www.fsns-thailand.com</a>
                    </li>
                    <li>
                        <span class="text-color" style="width:20px;display:inline-block"><b>F:</b> </span> <a target="_blank" href="<?php echo $this->setting_data['facebook']; ?>" style="font-size:13px;">FSNS Facebook Fanpage</a>
                    </li>
                     <li>
                        <span class="text-color" style="width:20px;display:inline-block"><b>I:</b> </span> <a target="_blank" href="<?php echo $this->setting_data['instagram']; ?>" style="font-size:13px;">FSNS Instagram</a>
                    </li>
                    <li>
                        <span class="text-color" style="width:20px;display:inline-block"><b>G:</b> </span> <a target="_blank" href="<?php echo $this->setting_data['google_plus']; ?>" style="font-size:13px;">FSNS Google+</a>
                    </li>
                </ul>
            </section>
        </article>
    </div>
</section>

<script  src="http://maps.google.com/maps/api/js?sensor=true"></script> <!-- google maps -->
<script  src="js/jquery.ui.map.full.min.js"></script> <!-- jquery plugin for google maps -->
<script>
    $(function() {
        var yourStartLatLng = new google.maps.LatLng(<?php echo $map_position ?>);
        $('.map_canvas').gmap({'center': yourStartLatLng, 'zoom': 18, 'disableDefaultUI': true, 'callback': function() {
                var self = this;
                self.addMarker({'position': this.get('map').getCenter()});
            }});
    });
</script>