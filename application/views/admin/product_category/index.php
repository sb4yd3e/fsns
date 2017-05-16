<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Product Categories Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('product_category/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Cid</th>
						<th>Title</th>
						<th>Priority</th>
						<th>Description</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($product_categories as $p){ ?>
                    <tr>
						<td><?php echo $p['cid']; ?></td>
						<td><?php echo $p['title']; ?></td>
						<td><?php echo $p['priority']; ?></td>
						<td><?php echo $p['description']; ?></td>
						<td>
                            <a href="<?php echo site_url('product_category/edit/'.$p['cid']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('product_category/remove/'.$p['cid']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
