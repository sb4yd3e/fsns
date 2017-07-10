<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Payment Information Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('admin/payment/add'); ?>" class="btn btn-success btn-sm">Add new gateway</a>
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tr>
						<th>Title</th>
						<th>Bank Name</th>
						<th>Bank ACC</th>
						<th>Bank Branch</th>
						<th>Bank Type</th>
						<th>Type</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($payments as $u){ ?>
                    <tr>
						<td><?php echo $u['title']; ?></td>
						<td><?php $pay = payment_list(); echo ($u['bank_name'])?$pay[$u['bank_name']]:''; ?></td>
						<td><?php echo $u['bank_acc']; ?></td>
						<td><?php echo $u['bank_branch']; ?></td>
						<td><?php echo $u['bank_type']; ?></td>
						<td><?php echo order_type($u['type']); ?></td>
						<td>
                            <a href="<?php echo site_url('admin/payment/edit/'.$u['pm_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a>
                            <a href="<?php echo site_url('admin/payment/remove/'.$u['pm_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
