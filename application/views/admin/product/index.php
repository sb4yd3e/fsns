<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Products Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('product/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Pid</th>
						<th>Product Category</th>
						<th>Product Online</th>
						<th>Product Title</th>
						<th>Product Photo</th>
						<th>Product Group</th>
						<th>Product Pdf</th>
						<th>Product Price</th>
						<th>Product Spacial Price</th>
						<th>Product Description</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($products as $p){ ?>
                    <tr>
						<td><?php echo $p['pid']; ?></td>
						<td><?php echo $p['product_category']; ?></td>
						<td><?php echo $p['product_online']; ?></td>
						<td><?php echo $p['product_title']; ?></td>
						<td><?php echo $p['product_photo']; ?></td>
						<td><?php echo $p['product_group']; ?></td>
						<td><?php echo $p['product_pdf']; ?></td>
						<td><?php echo $p['product_price']; ?></td>
						<td><?php echo $p['product_spacial_price']; ?></td>
						<td><?php echo $p['product_description']; ?></td>
						<td>
                            <a href="<?php echo site_url('product/edit/'.$p['pid']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('product/remove/'.$p['pid']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
