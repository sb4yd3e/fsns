<div class="row">
	<div class="col-md-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">User Add</h3>
			</div>
			<?php echo form_open('admin/members/add'); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>
				<div class="row clearfix">
					<div class="col-lg-8">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#info" aria-controls="home" role="tab" data-toggle="tab">Account info</a></li>
							<li role="presentation"><a href="#shping" aria-controls="shping" role="tab" data-toggle="tab">Shipping Detail</a></li>
							<li role="presentation"><a href="#business" aria-controls="business" role="tab" data-toggle="tab" id="tab-business" class="disabled">Business Info</a></li>
							
						</ul>
						<div class="tab-content" style="margin-top: 20px;">
							<div role="tabpanel" class="tab-pane fade in active" id="info">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="account_type" class="control-label">Account Type</label>
									<div class="form-group">
										<select name="account_type" id="account_type" class="form-control" autocomplete="off" required>
											<option value="">==== select ====</option>
											<?php 
											$account_type_values = array(
												'business'=>'Business',
												'personal'=>'Personal',
												);

											foreach($account_type_values as $value => $display_text)
											{
												$selected = ($value == $this->input->post('account_type')) ? ' selected="selected"' : "";

												echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
											} 
											?>
										</select>
									</div>
									<label for="staff_id" class="control-label">Sale</label>
									<div class="form-group">
										<select name="staff_id" autocomplete="off" class="form-control">
                                            <?php if(!is_group('sale')){ ?>
                                                <option value="0">No Sale</option>
                                            <?php } ?>
											<?php 
											foreach($all_admins as $admin)
											{
												$selected = ($admin['aid'] == $this->input->post('staff_id')) ? ' selected="selected"' : "";

												echo '<option value="'.$admin['aid'].'" '.$selected.'>'.$admin['name'].'</option>';
											} 
											?>
										</select>
									</div>
									<label for="name" class="control-label">Name</label>
									<div class="form-group">
										<input type="text" name="name" autocomplete="off" value="<?php echo $this->input->post('name'); ?>" class="form-control" id="name"  required />
									</div>
									<label for="email" class="control-label">Email</label>
									<div class="form-group">
										<input type="text" name="email" autocomplete="off" value="<?php echo $this->input->post('email'); ?>" class="form-control" id="email" required />
									</div>
									<label for="password" class="control-label">Password</label>
									<div class="form-group">
										<input type="password" name="password" autocomplete="off" value="<?php echo $this->input->post('password'); ?>" class="form-control" id="password" required />
									</div>
									<label for="phone" class="control-label">Phone</label>
									<div class="form-group">
										<input type="text" name="phone" autocomplete="off" value="<?php echo $this->input->post('phone'); ?>" class="form-control" id="phone" required />
									</div>
									<label for="phone" class="control-label">Active user</label>
									<div class="form-group">
										<?php echo form_dropdown('is_active',array(0=>'Inactive',1=>'Active'),$this->input->post('is_active'),'class="form-control" autocomplete="off" required'); ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="shping">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="shipping_name" class="control-label">Name</label>
									<div class="form-group">
										<input type="text" name="shipping_name" value="<?php echo $this->input->post('shipping_name'); ?>" class="form-control" autocomplete="off" id="shipping_name"  required />
									</div>
									<label for="shipping_address" class="control-label">Shipping Address</label>
									<div class="form-group">
										<textarea name="shipping_address" class="form-control" id="shipping_address" autocomplete="off" required><?php echo $this->input->post('shipping_address'); ?></textarea>
									</div>
									<label for="shipping_province" class="control-label">Shipping Province</label>
									<div class="form-group">
										<?php echo form_dropdown('shipping_province',list_province(),$this->input->post('shipping_province'),'class="form-control" autocomplete="off" required'); ?>
									</div>
									<label for="shipping_zip" class="control-label">Shipping Zip</label>
									<div class="form-group">
										<input type="text" name="shipping_zip" value="<?php echo $this->input->post('shipping_zip'); ?>" class="form-control" id="shipping_zip" autocomplete="off" required />
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="business">
								<label for="business_name" class="control-label">Business Name</label>
								<div class="form-group">
									<input type="text" name="business_name" value="<?php echo $this->input->post('business_name'); ?>" class="form-control" autocomplete="off" id="business_name" />
								</div>
								<label for="business_address" class="control-label">Business  Address</label>
								<div class="form-group">
									<textarea name="business_address" class="form-control" id="business_address" autocomplete="off"><?php echo $this->input->post('business_address'); ?></textarea>
								</div>
								<label for="business_number" class="control-label">Business Tax ID</label>
								<div class="form-group">
									<input type="text" name="business_number" value="<?php echo $this->input->post('business_number'); ?>" class="form-control" id="business_number" autocomplete="off" />
								</div>
							</div>
						</div>

					</div>

				</div>
			</div>
			<div class="box-footer">
				<button type="submit" class="btn btn-success">
					<i class="fa fa-check"></i> Save
				</button>
				<a href="<?php echo base_url('admin/members'); ?>" class="btn btn-warning">
					<i class="fa fa-times-circle"></i> Back to list
				</a>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>