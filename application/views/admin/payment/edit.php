<div class="row">
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Edit payment gateway</h3>
            </div>
            <?php echo form_open('admin/payment/edit/'.$payment['pm_id']); ?>
            <div class="box-body">


                <label for="title" class="control-label">Title</label>
                <div class="form-group">
                    <input type="text" name="title" value="<?php echo ($this->input->post('title'))?$this->input->post('title'):$payment['title']; ?>"
                           class="form-control" id="title" required/>
                </div>


                <label for="type" class="control-label">Payment for group</label>
                <div class="form-group">
                    <?php echo form_dropdown('type', array('personal' => 'บุคคลทั่วไป', 'business' => 'นิติบุคคล'), ($this->input->post('type'))?$this->input->post('type'):$payment['type'], ' class="form-control"'); ?>
                </div>


                <label for="file_paht" class="control-label">Bank Name</label>
                <div class="form-group">
                    <?php echo form_dropdown('bank_name', payment_list(), ($this->input->post('bank_name'))?$this->input->post('bank_name'):$payment['bank_name'], ' class="form-control"'); ?>
                </div>

                <label for="bank_acc" class="control-label">Bank Acc</label>
                <div class="form-group">
                    <input type="text" name="bank_acc" value="<?php echo ($this->input->post('bank_acc'))?$this->input->post('bank_acc'):$payment['bank_acc']; ?>"
                           class="form-control" id="bank_acc"/>
                </div>


                <label for="file_paht" class="control-label">Bank Type</label>
                <div class="form-group">
                    <?php echo form_dropdown('bank_type', array(''=>'Select','ออมทรัพย์'=>'ออมทรัพย์','กระแสรายวัน'=>'กระแสรายวัน'), ($this->input->post('bank_type'))?$this->input->post('bank_type'):$payment['bank_type'], ' class="form-control"'); ?>
                </div>


                <label for="bank_branch" class="control-label">Bank Branch</label>
                <div class="form-group">
                    <input type="text" name="bank_branch" value="<?php echo ($this->input->post('bank_branch'))?$this->input->post('bank_branch'):$payment['bank_branch']; ?>"
                           class="form-control" id="bank_branch"/>
                </div>
                <label for="file_paht" class="control-label">Detail (Only Business group)</label>
                <div class="form-group">
                    <textarea name="detail" id="body" cols="30" rows="10"><?php echo ($this->input->post('detail'))?$this->input->post('detail'):$payment['detail']; ?></textarea>
                </div>


                <div class="clearfix"></div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <a href="<?php echo base_url('admin/payment'); ?>" class="btn btn-warning">
                        <i class="fa fa-times-circle"></i> Back to list
                    </a>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>