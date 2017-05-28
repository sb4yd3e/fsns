<div class="row">
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Edit coupond</h3>
            </div>
            <?php echo form_open('admin/coupons/edit/'.$data['coid']); ?>
            <div class="box-body">
                <?php echo validation_errors(); ?>

                <label class="control-label">Coupon Code</label>
                <div class="form-group">
                    <input type="text" name="code" value="<?php echo $data['code']; ?>"
                           class="form-control" required/>
                </div>

                <label class="control-label">Coupon Discount(%)</label>
                <div class="form-group">
                    <input type="number" name="discount" value="<?php echo $data['discount']; ?>"
                           class="form-control" required/>
                </div>

                <label for="email" class="control-label">Coupon Expired</label>
                <div class="form-group">
                    <input type="text" name="expired" value="<?php echo date("Y/m/d H:i",$data['expired']); ?>"
                           class="form-control"
                           id="datetimepicker" required/>
                </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check"></i> Save
                </button>
                <a href="<?php echo base_url('admin/coupons'); ?>" class="btn btn-warning">
                    <i class="fa fa-times-circle"></i> Back to list
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>