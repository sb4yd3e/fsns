<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Product Sub Category Edit</h3>
            </div>
			<?php echo form_open('product_sub_category/edit/'.$product_sub_category['sid']); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>			
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="cid" class="control-label">Cid</label>
						<div class="form-group">
							<select name="cid" class="form-control">
								<option value="">select product_category</option>
								<?php 
								foreach($all_product_categories as $product_category)
								{
									$selected = ($product_category['cid'] == $product_sub_category['cid']) ? ' selected="selected"' : "";

									echo '<option value="'.$product_category['cid'].'" '.$selected.'>'.$product_category['cid'].'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="sub_title" class="control-label">Sub Title</label>
						<div class="form-group">
							<input type="text" name="sub_title" value="<?php echo ($this->input->post('sub_title') ? $this->input->post('sub_title') : $product_sub_category['sub_title']); ?>" class="form-control" id="sub_title" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="priority" class="control-label">Priority</label>
						<div class="form-group">
							<input type="text" name="priority" value="<?php echo ($this->input->post('priority') ? $this->input->post('priority') : $product_sub_category['priority']); ?>" class="form-control" id="priority" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="sub_description" class="control-label">Sub Description</label>
						<div class="form-group">
							<textarea name="sub_description" class="form-control" id="sub_description"><?php echo ($this->input->post('sub_description') ? $this->input->post('sub_description') : $product_sub_category['sub_description']); ?></textarea>
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