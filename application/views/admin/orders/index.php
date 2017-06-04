<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Orders Listing</h3>
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
                            <label>Customer Type</label>
                            <select name="order_type" id="order_type" aria-controls="table"
                                    class="form-control input-sm">
                                <option value="">Show All</option>
                                <option value="personal">บุคคลทั่วไป</option>
                                <option value="bussiness">นิติบุคคล</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer</label>
                            <?php echo form_dropdown('uid', $members, '', 'id="uid" aria-controls="table" class="form-control input-sm"'); ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <?php echo form_dropdown('status', array('' => 'Show All',
                                'pending' => 'รอตรวจสอบการสั่งซื้อ',
                                'confirmed' => 'ยืนยันการสั่งซื้อ',
                                'wait_payment' => 'ลูกค้าชำระเงิน/ส่งเอกสาร',
                                'confirm_payment' => 'ยืนยันการชำระ/ส่งเอกสาร',
                                'shping' => 'มีการจัดส่ง',
                                'success' => 'สำเร็จ',
                                'cancel' => 'ยกเลิก'), '', 'id="status" aria-controls="table" class="form-control input-sm"'); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <input class="form-control input-sm" id="search" placeholder="" aria-controls="table"
                                   type="search">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>#คำสั่งซื้อ</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ประเภทลูกค้า</th>
                            <th>จำนวนรายการ</th>
                            <th>สถานะ</th>
                            <th>ยอดรวม</th>
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
    #table_length, #table_filter {
        display: none;
    }
</style>

<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-info"></i> Info</h4>
            </div>
            <div class="modal-body" id="ajax-result">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>