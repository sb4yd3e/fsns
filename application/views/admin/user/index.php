<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Users Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('user/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Uid</th>
						<th>Account Type</th>
						<th>Staf Id</th>
						<th>Password</th>
						<th>Email</th>
						<th>Name</th>
						<th>Sname</th>
						<th>Phone</th>
						<th>Shiping Name</th>
						<th>Shiping Province</th>
						<th>Shiping Zip</th>
						<th>Shiping Address</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($users as $u){ ?>
                    <tr>
						<td><?php echo $u['uid']; ?></td>
						<td><?php echo $u['account_type']; ?></td>
						<td><?php echo $u['staf_id']; ?></td>
						<td><?php echo $u['password']; ?></td>
						<td><?php echo $u['email']; ?></td>
						<td><?php echo $u['name']; ?></td>
						<td><?php echo $u['sname']; ?></td>
						<td><?php echo $u['phone']; ?></td>
						<td><?php echo $u['shiping_name']; ?></td>
						<td><?php echo $u['shiping_province']; ?></td>
						<td><?php echo $u['shiping_zip']; ?></td>
						<td><?php echo $u['shiping_address']; ?></td>
						<td>
                            <a href="<?php echo site_url('user/edit/'.$u['uid']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('user/remove/'.$u['uid']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
