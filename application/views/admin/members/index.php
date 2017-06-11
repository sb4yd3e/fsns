<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Members Listing</h3>
                <div class="box-tools">
                    <a href="<?php echo site_url('admin/members/add'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Create new member</a> 
                </div>
            </div>
            <div class="box-body">
                <div class="col-lg-12">
                   <div class="col-md-2">
                    <div class="form-group">
                        <label>Show</label>
                        <select name="table_length" id="show" aria-controls="table" class="form-control input-sm">
                         <option value="10">10</option>
                         <option value="25">25</option>
                         <option value="50">50</option>
                         <option value="100">100</option>
                     </select>
                 </div>
             </div>
             <div class="col-md-2">
                <div class="form-group">
                    <label>Account Type</label>
                    <select name="account_type" aria-controls="table" id="account_type" class="form-control  input-sm">
                        <option value="">All</option>
                        <option value="business">Business</option>
                        <option value="personal">Personal</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Seller Staff</label>
                    <select name="staf_id" id="staff_id" aria-controls="table" autocomplete="off" class="form-control input-sm">
                        <option value="">All</option>
                        <option value="0">No Seller Staff</option>
                        <?php 
                        foreach($all_admins as $admin)
                        {
                            echo '<option value="'.$admin['aid'].'" '.$selected.'>'.$admin['name'].'</option>';
                        } 
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Status</label>
                    <?php echo form_dropdown('is_active',array(''=>'All','1'=>'Actived','0'=>'Inactive'),'','id="is_active" aria-controls="table" class="form-control input-sm"'); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Search</label>
                    <input class="form-control input-sm" id="search" placeholder="" aria-controls="table" type="search">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
        <table class="table table-striped table-bordered" id="table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Account Type</th>
                        <th>Seller Staff</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
<style type="text/css" media="screen">
    #table_length,#table_filter{
        display: none;
    }
</style>