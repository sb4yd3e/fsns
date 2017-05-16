<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Product Edit</h3>
            </div>
			<?php echo form_open('product/edit/'.$product['pid']); ?>
			<div class="box-body">
				<?php echo validation_errors(); ?>			
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_category" class="control-label">Product Category</label>
						<div class="form-group">
							<select name="product_category" class="form-control">
								<option value="">select product_sub_category</option>
								<?php 
								foreach($all_product_sub_categories as $product_sub_category)
								{
									$selected = ($product_sub_category['sid'] == $product['product_category']) ? ' selected="selected"' : "";

									echo '<option value="'.$product_sub_category['sid'].'" '.$selected.'>'.$product_sub_category['sid'].'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_online" class="control-label">Product Online</label>
						<div class="form-group">
							<select name="product_online" class="form-control">
								<option value="">select</option>
								<?php 
								$product_online_values = array(
						'0'=>'ไม่ใช่',
						'1'=>'ใช่',
					);

								foreach($product_online_values as $value => $display_text)
								{
									$selected = ($value == $product['product_online']) ? ' selected="selected"' : "";

									echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
								} 
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_title" class="control-label">Product Title</label>
						<div class="form-group">
							<input type="text" name="product_title" value="<?php echo ($this->input->post('product_title') ? $this->input->post('product_title') : $product['product_title']); ?>" class="form-control" id="product_title" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_photo" class="control-label">Product Photo</label>
						<div class="form-group">
							<input type="text" name="product_photo" value="<?php echo ($this->input->post('product_photo') ? $this->input->post('product_photo') : $product['product_photo']); ?>" class="form-control" id="product_photo" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_group" class="control-label">Product Group</label>
						<div class="form-group">
							<input type="text" name="product_group" value="<?php echo ($this->input->post('product_group') ? $this->input->post('product_group') : $product['product_group']); ?>" class="form-control" id="product_group" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_pdf" class="control-label">Product Pdf</label>
						<div class="form-group">
							<input type="text" name="product_pdf" value="<?php echo ($this->input->post('product_pdf') ? $this->input->post('product_pdf') : $product['product_pdf']); ?>" class="form-control" id="product_pdf" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_price" class="control-label">Product Price</label>
						<div class="form-group">
							<input type="text" name="product_price" value="<?php echo ($this->input->post('product_price') ? $this->input->post('product_price') : $product['product_price']); ?>" class="form-control" id="product_price" />
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_spacial_price" class="control-label">Product Spacial Price</label>
						<div class="form-group">
							<input type="text" name="product_spacial_price" value="<?php echo ($this->input->post('product_spacial_price') ? $this->input->post('product_spacial_price') : $product['product_spacial_price']); ?>" class="form-control" id="product_spacial_price" />
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label for="product_description" class="control-label">Product Description</label>
						<div class="form-group">
							<textarea name="product_description" class="form-control" id="product_description"><?php echo ($this->input->post('product_description') ? $this->input->post('product_description') : $product['product_description']); ?></textarea>
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