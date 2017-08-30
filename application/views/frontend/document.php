<section id="content-wrapper">
    <section class="container_12 clearfix" id="product-detail">

        <div id="membber-form">
            <div align="center"><h2>เอกสารประกอบคำสั่งซื้อ :
                    #<?php echo str_pad($order['oid'], 6, "0", STR_PAD_LEFT); ?></h2></div>
            <div class="col-6">
                <div class="panel">
                    <div class="box-header">เอกสารจากระบบ</div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ชื่อไฟล์</th>
                                <th>ดาวน์โหลด</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($seller_documents as $seller_file) { ?>
                                <tr>
                                    <td>
                                        <?php echo date("d/m/Y H:i:s", strtotime($seller_file['file_date'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $seller_file['file_title']; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('download/document/' . $seller_file['ufid']); ?>"
                                           target="_blank" class="label label-info">Download</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="panel">
                    <div class="box-header">เอกสารของฉัน</div>
                    <div class="panel-body">

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ชื่อไฟล์</th>
                                <th>ดาวน์โหลด</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($my_documents as $my_file) { ?>
                                <tr>
                                    <td>
                                        <?php echo date("d/m/Y H:i:s", strtotime($my_file['file_date'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $my_file['file_title']; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('download/document/' . $my_file['ufid']); ?>"
                                           target="_blank" class="label label-info">Download</a>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>
                        <?php if ($order['order_status'] != "success" && $order['order_status'] != "cancel") { ?>
                            <div id="upload-box" class="wpcf7">
                                <form method="post" enctype="multipart/form-data" id="ajax-upload-document">
                                    <br>
                                    <fieldset>
                                        <label>ชื่อไฟล์ : </label>
                                        <input type="text" name="title" maxlength="50" class="wpcf7-text" required>
                                    </fieldset>
                                    <fieldset>
                                        <label>เลือกไฟล์เอกสาร (jpg,png,doc,pdf) : </label>
                                        <input type="file" name="file" class="wpcf7-text" accept="image/*,.doc,.pdf"
                                               required>
                                    </fieldset>
                                    <button type="submit" class="btn-medium black" id="add-file-btn">อัพโหลด</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</section>

<script>
    $(document).ready(function () {
        var options_uploadfile = {
            beforeSubmit: showRequest_uploadfile,
            success: showResponse_uploadfile
        };
        $('#ajax-upload-document').ajaxForm(options_uploadfile);
    });
    function showRequest_uploadfile() {
        $('#add-file-btn').html('Uploading...').attr('disabled', 'disabled');
        return true;
    }

    function showResponse_uploadfile(responseText) {
        var obj = jQuery.parseJSON(responseText);
        if (obj.status === 'success') {

            swal({
                    title: "สำเร็จ",
                    text: "อัพโหลดเอกสารสำเร็จ",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "ตกลง",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.reload();
                    }
                });
        } else {
            swal({
                title: "ผิดพลาด!",
                text: obj.message,
                html: true
            });
            grecaptcha.reset();
            $('#add-file-btn').html('อัพโหลด').removeAttr('disabled');
        }

    }
</script>