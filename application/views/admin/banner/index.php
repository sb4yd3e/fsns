<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Banner Setting</h3>
            </div>
            <?php echo form_open_multipart('admin/banner'); ?>
            <div class="box-body">

                <div class="row clearfix">
                    <?php echo validation_errors(); ?>
                    <div class="col-lg-8">
                        <div class="form-group">
                        <label class="control-label">Banner image (Size: 000x000px)*:</label><br>
                            <img src="<?php echo base_url()?>timthumb.php?src=<?php echo base_url().BANNER_PATH.'/'.$banner['image']?>&w=100" style="border:2px solid #eee"/><br>
                            <input type="file" name="image" size="20" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Link*</label>
                            <input name="link" value="<?php echo set_value('link',$banner['link']) ?>" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Endable</label>
                           <?php echo form_dropdown('visible',array('1'=>'Endable','0'=>'Disable'),$banner['visible'],'class="form-control" required'); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Delay to show next time.(Seconds)*</label>
                           <input name="delay" value="<?php echo set_value('delay',$banner['delay']) ?>" type="number" class="form-control" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check"></i> Save Setting
                </button>
            </div>              
            <?php echo form_close(); ?>
        </div>
    </div>
</div>