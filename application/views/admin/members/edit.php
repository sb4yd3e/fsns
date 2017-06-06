<div class="row">
	<div class="col-md-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">User Edit</h3>
			</div>
			<?php echo form_open('admin/members/edit/'.$content['uid']); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>
				<div class="row clearfix">
					<div class="col-lg-8">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Account info</a></li>
							<li role="presentation"><a href="#shping" aria-controls="shping" role="tab" data-toggle="tab">Shipping Detail</a></li>
							<li role="presentation"><a href="#bussiness" aria-controls="bussiness" role="tab" data-toggle="tab" id="tab-bussiness" class="disabled">Bussiness Info</a></li>
							<li role="presentation"><a href="#logs" aria-controls="logs" role="tab" data-toggle="tab">Logs</a></li>
						</ul>
						<div class="tab-content" style="margin-top: 20px;">
							<div role="tabpanel" class="tab-pane fade in active" id="info">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="account_type" class="control-label">Account Type</label>
									<div class="form-group">
										<select name="account_type" id="account_type" class="form-control" required>
											<option value="">==== select ====</option>
											<?php 
											$account_type_values = array(
												'bussiness'=>'Bussiness',
												'personal'=>'Personal',
												);

											foreach($account_type_values as $value => $display_text)
											{
												$selected = ($value == $content['account_type']) ? ' selected="selected"' : "";

												echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
											} 
											?>
										</select>
									</div>
									<label for="staf_id" class="control-label">Seller Staff</label>
									<div class="form-group">
										<select name="staff_id" class="form-control">
											<option value="0">No Seller Staff</option>
											<?php 
											foreach($all_admins as $admin)
											{
												$selected = ($admin['aid'] == $content['staff_id']) ? ' selected="selected"' : "";

												echo '<option value="'.$admin['aid'].'" '.$selected.'>'.$admin['name'].'</option>';
											} 
											?>
										</select>
									</div>
									<label for="name" class="control-label">Name</label>
									<div class="form-group">
										<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $content['name']); ?>" class="form-control" id="name"  required />
									</div>
									<label for="email" class="control-label">Email</label>
									<div class="form-group">
										<input type="text" name="email" value="<?php echo ($this->input->post('email') ? $this->input->post('email') : $content['email']); ?>" class="form-control" id="email" required />
									</div>
									<label for="password" class="control-label">New Password</label>
									<div class="form-group">
										<input type="password" name="password" value="" class="form-control" id="password" />
									</div>
									<label for="phone" class="control-label">Phone</label>
									<div class="form-group">
										<input type="text" name="phone" value="<?php echo ($this->input->post('phone') ? $this->input->post('phone') : $content['phone']); ?>" class="form-control" id="phone" required />
									</div>
									<label for="phone" class="control-label">Active user</label>
									<div class="form-group">
										<?php echo form_dropdown('is_active',array(0=>'Inactive',1=>'Active'),$content['is_active'],'class="form-control"  required'); ?>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="shping">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="shipping_name" class="control-label">Name</label>
									<div class="form-group">
										<input type="text" name="shipping_name" value="<?php echo ($this->input->post('shipping_name') ? $this->input->post('shipping_name') : $content['shipping_name']); ?>" class="form-control" id="shipping_name"  required />
									</div>
									<label for="shipping_address" class="control-label">Shipping Address</label>
									<div class="form-group">
										<textarea name="shipping_address" class="form-control" id="shipping_address"  required><?php echo ($this->input->post('shipping_address') ? $this->input->post('shipping_address') : $content['shipping_address']); ?></textarea>
									</div>
									<label for="shipping_province" class="control-label">Shipping Province</label>
									<div class="form-group">
										<?php echo form_dropdown('shipping_province',list_province(),$content['shipping_province'],'class="form-control" required'); ?>
									</div>
									<label for="shipping_zip" class="control-label">Shipping Zip</label>
									<div class="form-group">
										<input type="text" name="shipping_zip" value="<?php echo ($this->input->post('shipping_zip') ? $this->input->post('shipping_zip') : $content['shipping_zip']); ?>" class="form-control" id="shipping_zip"  required />
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="bussiness">
								<label for="bussiness_name" class="control-label">Bussiness Name</label>
								<div class="form-group">
									<input type="text" name="bussiness_name" value="<?php echo ($this->input->post('bussiness_name') ? $this->input->post('bussiness_name') : $content['bussiness_name']); ?>" class="form-control" autocomplete="off" id="bussiness_name" />
								</div>
								<label for="bussiness_address" class="control-label">Bussiness  Address</label>
								<div class="form-group">
									<textarea name="bussiness_address" class="form-control" id="bussiness_address" autocomplete="off"><?php echo ($this->input->post('bussiness_address') ? $this->input->post('bussiness_address') : $content['bussiness_address']); ?></textarea>
								</div>
								<label for="bussiness_number" class="control-label">Federal tax identification number</label>
								<div class="form-group">
									<input type="text" name="bussiness_number" value="<?php echo ($this->input->post('bussiness_number') ? $this->input->post('bussiness_number') : $content['bussiness_number']); ?>" class="form-control" id="bussiness_number" autocomplete="off" />
								</div>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="logs">
								<table class="table table-striped table-bordered">
									<thead>
										<tr><th>Date</th><th>User</th><th>Detail</th></tr>
									</thead>
									<tbody>
										<?php if($logs){ foreach ($logs as $key => $value) { ?>
										<tr>
											<td><?php echo date("d/m/Y H:i:s",strtotime($value['log_date'])); ?></td>
											<td><?php echo $value['user']; ?></td>
											<td><?php echo $value['detail']; ?></td>
										</tr>
										<?php	} } ?>
									</tbody>
								</table>
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