<div class="row">
	<div class="col-md-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Create new content</h3>
			</div>
			<form action="<?php echo base_url() ?>admin/news/add" method="post" class="ui-form" enctype="multipart/form-data">
				<div class="box-body">
					<?php echo validation_errors(); ?>
					<?php echo isset($error_upload) ? $error_upload : '' ?>
					<div class="row clearfix">
						<div class="col-lg-8">
							<div class="form-group">
								<label class="control-label">Content Cover (Size: 450x235px)*:</label>
								<input type="file" name="cover" size="20" />
							</div>
							<div class="form-group">
								<label class="control-label">Content title*:</label>
								<input name="title" value="<?php echo set_value('title') ?>" class="form-control" required/>
							</div>
							<div class="form-group">
							<label class="control-label">Content body*:</label>
								<textarea name="body" style="width:200px;height:100px;" id="body"><?php echo set_value('body') ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-success">
						<i class="fa fa-check"></i> Save
					</button>
					<a href="<?php echo base_url('admin/news'); ?>" class="btn btn-warning">
						<i class="fa fa-times-circle"></i> Back to list
					</a>
				</div>
			</form>
		</div>
	</div>
</div>