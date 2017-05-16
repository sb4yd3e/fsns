<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Product Sub Categories Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('product_sub_category/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Sid</th>
						<th>Cid</th>
						<th>Sub Title</th>
						<th>Priority</th>
						<th>Sub Description</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($product_sub_categories as $p){ ?>
                    <tr>
						<td><?php echo $p['sid']; ?></td>
						<td><?php echo $p['cid']; ?></td>
						<td><?php echo $p['sub_title']; ?></td>
						<td><?php echo $p['priority']; ?></td>
						<td><?php echo $p['sub_description']; ?></td>
						<td>
                            <a href="<?php echo site_url('product_sub_category/edit/'.$p['sid']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('product_sub_category/remove/'.$p['sid']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
