<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Upload Edit</h3>
            </div>
			<?php echo form_open('upload/edit/'.$upload['ufid']); ?>
			<div class="box-body">
							
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="file_title" class="control-label">File Title</label>
						<div class="form-group">
							<input type="text" name="file_title" value="<?php echo ($this->input->post('file_title') ? $this->input->post('file_title') : $upload['file_title']); ?>" class="form-control" id="file_title" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="file_paht" class="control-label">File Paht</label>
						<div class="form-group">
							<input type="text" name="file_paht" value="<?php echo ($this->input->post('file_paht') ? $this->input->post('file_paht') : $upload['file_paht']); ?>" class="form-control" id="file_paht" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="file_date" class="control-label">File Date</label>
						<div class="form-group">
							<input type="text" name="file_date" value="<?php echo ($this->input->post('file_date') ? $this->input->post('file_date') : $upload['file_date']); ?>" class="form-control" id="file_date" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="file_size" class="control-label">File Size</label>
						<div class="form-group">
							<input type="text" name="file_size" value="<?php echo ($this->input->post('file_size') ? $this->input->post('file_size') : $upload['file_size']); ?>" class="form-control" id="file_size" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="file_type" class="control-label">File Type</label>
						<div class="form-group">
							<input type="text" name="file_type" value="<?php echo ($this->input->post('file_type') ? $this->input->post('file_type') : $upload['file_type']); ?>" class="form-control" id="file_type" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="uid" class="control-label">Uid</label>
						<div class="form-group">
							<input type="text" name="uid" value="<?php echo ($this->input->post('uid') ? $this->input->post('uid') : $upload['uid']); ?>" class="form-control" id="uid" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="aid" class="control-label">Aid</label>
						<div class="form-group">
							<input type="text" name="aid" value="<?php echo ($this->input->post('aid') ? $this->input->post('aid') : $upload['aid']); ?>" class="form-control" id="aid" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="oid" class="control-label">Oid</label>
						<div class="form-group">
							<input type="text" name="oid" value="<?php echo ($this->input->post('oid') ? $this->input->post('oid') : $upload['oid']); ?>" class="form-control" id="oid" />
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
            	<button type="submit" class="btn btn-success">
					<i class="fa fa-check"></i> Save
				</button>
	        </div>				
			<?php echo form_close(); ?>
		</div>
    </div>
</div>