<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Uploads Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('upload/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Ufid</th>
						<th>File Title</th>
						<th>File Paht</th>
						<th>File Date</th>
						<th>File Size</th>
						<th>File Type</th>
						<th>Uid</th>
						<th>Aid</th>
						<th>Oid</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($uploads as $u){ ?>
                    <tr>
						<td><?php echo $u['ufid']; ?></td>
						<td><?php echo $u['file_title']; ?></td>
						<td><?php echo $u['file_paht']; ?></td>
						<td><?php echo $u['file_date']; ?></td>
						<td><?php echo $u['file_size']; ?></td>
						<td><?php echo $u['file_type']; ?></td>
						<td><?php echo $u['uid']; ?></td>
						<td><?php echo $u['aid']; ?></td>
						<td><?php echo $u['oid']; ?></td>
						<td>
                            <a href="<?php echo site_url('upload/edit/'.$u['ufid']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('upload/remove/'.$u['ufid']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
