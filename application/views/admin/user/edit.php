<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">User Edit</h3>
            </div>
			<?php echo form_open('user/edit/'.$user['uid']); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>			
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="account_type" class="control-label">Account Type</label>
						<div class="form-group">
							<select name="account_type" class="form-control">
								<option value="">select</option>
								<?php 
								$account_type_values = array(
						'bussiness'=>'นิติบุคคล',
						'personal'=>'บุคคลธรรมดา',
					);

								foreach($account_type_values as $value => $display_text)
								{
									$selected = ($value == $user['account_type']) ? ' selected="selected"' : "";

									echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="staf_id" class="control-label">Staf Id</label>
						<div class="form-group">
							<select name="staf_id" class="form-control">
								<option value="">select admin</option>
								<?php 
								foreach($all_admins as $admin)
								{
									$selected = ($admin['aid'] == $user['staf_id']) ? ' selected="selected"' : "";

									echo '<option value="'.$admin['aid'].'" '.$selected.'>'.$admin['aid'].'</option>';
								} 
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="password" class="control-label">Password</label>
						<div class="form-group">
							<input type="text" name="password" value="<?php echo ($this->input->post('password') ? $this->input->post('password') : $user['password']); ?>" class="form-control" id="password" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="email" class="control-label">Email</label>
						<div class="form-group">
							<input type="text" name="email" value="<?php echo ($this->input->post('email') ? $this->input->post('email') : $user['email']); ?>" class="form-control" id="email" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="name" class="control-label">Name</label>
						<div class="form-group">
							<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $user['name']); ?>" class="form-control" id="name" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="sname" class="control-label">Sname</label>
						<div class="form-group">
							<input type="text" name="sname" value="<?php echo ($this->input->post('sname') ? $this->input->post('sname') : $user['sname']); ?>" class="form-control" id="sname" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="phone" class="control-label">Phone</label>
						<div class="form-group">
							<input type="text" name="phone" value="<?php echo ($this->input->post('phone') ? $this->input->post('phone') : $user['phone']); ?>" class="form-control" id="phone" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="shiping_name" class="control-label">Shiping Name</label>
						<div class="form-group">
							<input type="text" name="shiping_name" value="<?php echo ($this->input->post('shiping_name') ? $this->input->post('shiping_name') : $user['shiping_name']); ?>" class="form-control" id="shiping_name" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="shiping_province" class="control-label">Shiping Province</label>
						<div class="form-group">
							<input type="text" name="shiping_province" value="<?php echo ($this->input->post('shiping_province') ? $this->input->post('shiping_province') : $user['shiping_province']); ?>" class="form-control" id="shiping_province" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="shiping_zip" class="control-label">Shiping Zip</label>
						<div class="form-group">
							<input type="text" name="shiping_zip" value="<?php echo ($this->input->post('shiping_zip') ? $this->input->post('shiping_zip') : $user['shiping_zip']); ?>" class="form-control" id="shiping_zip" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="shiping_address" class="control-label">Shiping Address</label>
						<div class="form-group">
							<textarea name="shiping_address" class="form-control" id="shiping_address"><?php echo ($this->input->post('shiping_address') ? $this->input->post('shiping_address') : $user['shiping_address']); ?></textarea>
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