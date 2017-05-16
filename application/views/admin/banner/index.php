<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Banner Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('banner/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Bid</th>
						<th>Visible</th>
						<th>Image</th>
						<th>Link</th>
						<th>Delay</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($banner as $b){ ?>
                    <tr>
						<td><?php echo $b['bid']; ?></td>
						<td><?php echo $b['visible']; ?></td>
						<td><?php echo $b['image']; ?></td>
						<td><?php echo $b['link']; ?></td>
						<td><?php echo $b['delay']; ?></td>
						<td>
                            <a href="<?php echo site_url('banner/edit/'.$b['bid']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('banner/remove/'.$b['bid']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
