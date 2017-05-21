<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Products Listing</h3>
                <div class="box-tools">
                    <a href="<?php echo site_url('admin/products/add'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add new product</a> 
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
             <div class="col-md-3">
                <div class="form-group">
                    <label>product Group</label>
                     <select name="group" id="group" aria-controls="table" class="form-control input-sm">
                     <option value="">Show All</option>
                   <?php 
                        foreach($groups as $group)
                        {
                            echo '<option value="'.$group['group'].'">'.$group['group'].'</option>';
                        } 
                        ?>
                        </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Shoping Online</label>
                    <?php echo form_dropdown('online',array(''=>'Show All','1'=>'Yes','0'=>'No'),'','id="online" aria-controls="table" class="form-control input-sm"'); ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Stock</label>
                    <?php echo form_dropdown('in_stock',array(''=>'Show All','1'=>'In Stock','0'=>'Out of stock'),'','id="in_stock" aria-controls="table" class="form-control input-sm"'); ?>
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
                        <th>Cover</th>
                        <th>Product Name</th>
                        <th>Model Code</th>
                        <th>Group</th>
                        <th>Online</th>
                        <th>Normal Price</th>
                        <th>Special Price</th>
                        <th>In Stock</th>
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