<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Banner Edit</h3>
            </div>
			<?php echo form_open('banner/edit/'.$banner['bid']); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>			
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="visible" class="control-label">Visible</label>
						<div class="form-group">
							<select name="visible" class="form-control">
								<option value="">select</option>
								<?php 
								$visible_values = array(
						'T'=>'แสดงผล',
						'F'=>'ปิดการแสดงผล',
					);

								foreach($visible_values as $value => $display_text)
								{
									$selected = ($value == $banner['visible']) ? ' selected="selected"' : "";

									echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="image" class="control-label">Image</label>
						<div class="form-group">
							<input type="text" name="image" value="<?php echo ($this->input->post('image') ? $this->input->post('image') : $banner['image']); ?>" class="form-control" id="image" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="link" class="control-label">Link</label>
						<div class="form-group">
							<input type="text" name="link" value="<?php echo ($this->input->post('link') ? $this->input->post('link') : $banner['link']); ?>" class="form-control" id="link" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="delay" class="control-label">Delay</label>
						<div class="form-group">
							<input type="text" name="delay" value="<?php echo ($this->input->post('delay') ? $this->input->post('delay') : $banner['delay']); ?>" class="form-control" id="delay" />
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