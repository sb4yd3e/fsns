<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Site Setting</h3>
            </div>
			<?php echo form_open('admin/setting'); ?>
			<div class="box-body">
							
				<div class="row clearfix">
				<?php echo validation_errors(); ?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="site_title" class="control-label">Site Title</label>
						<div class="form-group">
							<input type="text" name="site_title" value="<?php echo ($this->input->post('site_title') ? $this->input->post('site_title') : $setting['site_title']); ?>" class="form-control" id="site_title" required />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="site_keyword" class="control-label">Site Keyword</label>
						<div class="form-group">
							<input type="text" name="site_keyword" value="<?php echo ($this->input->post('site_keyword') ? $this->input->post('site_keyword') : $setting['site_keyword']); ?>" class="form-control" id="site_keyword" required />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="site_description" class="control-label">Site Description</label>
						<div class="form-group">
							<textarea name="site_description" class="form-control" id="site_description"  required><?php echo ($this->input->post('site_description') ? $this->input->post('site_description') : $setting['site_description']); ?></textarea>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="site_description" class="control-label">Email for contact</label>
						<div class="form-group">
                            <input type="text" name="email_for_contact" value="<?php echo ($this->input->post('email_for_contact') ? $this->input->post('email_for_contact') : $setting['email_for_contact']); ?>" class="form-control" id="email_for_contact" required />
                        </div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="facebook" class="control-label">Facebook</label>
						<div class="form-group">
							<input type="text" name="facebook" value="<?php echo ($this->input->post('facebook') ? $this->input->post('facebook') : $setting['facebook']); ?>" class="form-control" id="facebook"  required />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="google" class="control-label">Google</label>
						<div class="form-group">
							<input type="text" name="google" value="<?php echo ($this->input->post('google') ? $this->input->post('google') : $setting['google_plus']); ?>" class="form-control" id="google"  required />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="instagram" class="control-label">Instagram</label>
						<div class="form-group">
							<input type="text" name="instagram" value="<?php echo ($this->input->post('instagram') ? $this->input->post('instagram') : $setting['instagram']); ?>" class="form-control" id="instagram"  required />
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