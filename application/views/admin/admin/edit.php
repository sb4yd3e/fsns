<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Admin Edit</h3>
            </div>
			<?php echo form_open('admin/admin/edit/'.$admin['aid']); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>			
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="admin_group" class="control-label">Admin Group</label>
						<div class="form-group">
							<select name="admin_group" class="form-control" required>
								<option value="">select</option>
								<?php 
								$admin_group_values = array(
						'admin'=>'Admin',
						'staff'=>'SALE Support',
						'sale'=>'Sale',
					);

								foreach($admin_group_values as $value => $display_text)
								{
									$selected = ($value == $admin_data['admin_group']) ? ' selected="selected"' : "";

									echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="name" class="control-label">Name</label>
						<div class="form-group">
							<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $admin_data['name']); ?>" class="form-control" id="name" required />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="username" class="control-label">Username</label>
						<div class="form-group">
							<input type="text" name="username" value="<?php echo ($this->input->post('username') ? $this->input->post('username') : $admin_data['username']); ?>" class="form-control" id="username" required />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="password" class="control-label">New Password</label>
						<div class="form-group">
							<input type="text" name="password" class="form-control" id="password" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="email" class="control-label">Email</label>
						<div class="form-group">
							<input type="text" name="email" value="<?php echo ($this->input->post('email') ? $this->input->post('email') : $admin_data['email']); ?>" class="form-control" id="email" required />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="phone" class="control-label">Phone</label>
						<div class="form-group">
							<input type="text" name="phone" value="<?php echo ($this->input->post('phone') ? $this->input->post('phone') : $admin_data['phone']); ?>" class="form-control" id="phone" required />
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
            	<button type="submit" class="btn btn-success">
					<i class="fa fa-check"></i> Save
				</button>

				<a href="<?php echo base_url('admin/admin'); ?>" class="btn btn-warning">
					<i class="fa fa-times-circle"></i> Back to list
				</a>
	        </div>				
			<?php echo form_close(); ?>
		</div>
    </div>
</div>