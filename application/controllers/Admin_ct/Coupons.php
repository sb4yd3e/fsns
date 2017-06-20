<?php


class Coupons extends CI_Controller
{
    public $render_data = array();

    function __construct()
    {
        parent::__construct();
        $this->template->set_template('admin');
        $this->load->model('Coupons_model', 'coupons');
    }

    public function index()
    {
        if (!is_group('admin')) {
            redirect('admin');
            exit();
        }

        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Coupons');
        $this->template->write('user_id', $render_data['user']['aid']);
        $this->template->write('user_name', $render_data['user']['name']);
        $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//

        // ====== Java script Data tabale ======= //
        $js = 'var table;
        $(document).ready(function() {
            table = $("#table").DataTable({ 
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "' . base_url('admin/coupons/ajax') . '",
                    "type": "POST"
                },
				"columnDefs": [
				{ 
					"targets": [3,4,5], 
					"orderable": false,
				},
				],
            });
        });';

        if($this->input->get('add')=="true"){
            $js .= '$.notify("Add new coupon success.", "success");';
        }
        if($this->input->get('delete')=="true"){
            $js .= '$.notify("Delete coupon success", "success");';
        }
        if($this->input->get('save')=="true"){
            $js .= '$.notify("Save coupon success.", "success");';
        }
        $this->template->write('js', $js);
        $this->template->write_view('content', 'admin/coupons/index', $render_data);
        $this->template->render();
    }

    public function ajax()
    {
        if (!$this->input->is_ajax_request() || !is_group('admin')) {
            exit('No direct script access allowed');
        }

        $list = $this->coupons->get_all_coupons();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $coupon) {
            $no++;
            $row = array();
            if ($coupon->expired < time()) {
                $ex = '<label class="label label-danger">' . date("d/m/Y H:i:s", $coupon->expired) . '</label>';
            } else {
                $ex = '<label class="label label-success">' . date("d/m/Y H:i:s", $coupon->expired) . '</label>';
            }

            $row[] = strtoupper($coupon->code);
            $row[] = $coupon->discount;
            $row[] = $ex;
            $row[] = date("d/m/Y H:i:s", $coupon->created_at);
            $row[] = date("d/m/Y H:i:s", $coupon->modified_at);
            $row[] = '<a href="' . base_url('admin/coupons/edit/' . $coupon->coid) . '" class="label label-warning"><i class="fa fa-pencil"></i> Edit</a> 
                    <a href="' . base_url('admin/coupons/delete/' . $coupon->coid) . '" class="label label-danger"  onclick="return confirm(\'Are you sure?\')"><i class="fa fa-times-circle"></i> Delete</a>
			';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->coupons->count_all(),
            "recordsFiltered" => $this->coupons->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    function add()
    {
        $this->load->library('form_validation');
        if (!is_group(array('admin'))) {
            redirect('admin');
            exit();
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
        $this->form_validation->set_rules('code', 'Promotion Code', 'required|max_length[20]');
        $this->form_validation->set_rules('discount', 'Promotion Discount', 'required|max_length[5]');
        $this->form_validation->set_rules('expired', 'Promotion Expired', 'required');
        if ($this->form_validation->run()) {
            $data_create = array(
                'code' => strtolower($this->input->post('code', TRUE)),
                'discount' => $this->input->post('discount', FALSE),
                'expired' => dateToTime($this->input->post('expired', FALSE)),
                'created_at' => time(),
                'modified_at' => time()
            );

            $this->coupons->add_coupon($data_create);
            redirect('admin/coupons?add=true');

        } else {
            $js = 'jQuery(\'#datetimepicker\').datetimepicker();';
            $this->template->write('js', $js);
            //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Add new coupon');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
            //******* Defalut ********//
            $this->template->write_view('content', 'admin/coupons/add', $render_data);
            $this->template->render();
        }

    }


    function edit($id='')
    {
        $this->load->library('form_validation');
        if (!is_group(array('admin'))) {
            redirect('admin');
            exit();
        }

        if ($id == "" || !$data = $this->coupons->get_coupon($id)) {
            redirect('admin/coupons');
            exit();
        }
        $render_data['data'] = $data;
        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
        $this->form_validation->set_rules('code', 'Promotion Code', 'required|max_length[20]');
        $this->form_validation->set_rules('discount', 'Promotion Discount', 'required|max_length[5]');
        $this->form_validation->set_rules('expired', 'Promotion Expired', 'required');
        if ($this->form_validation->run()) {
            $data_create = array(
                'code' => strtolower($this->input->post('code', TRUE)),
                'discount' => $this->input->post('discount', FALSE),
                'expired' => dateToTime($this->input->post('expired', FALSE)),
                'modified_at' => time()
            );

            $this->coupons->save_coupon($id,$data_create);
            redirect('admin/coupons/edit/' . $id . '?save=true');

        } else {
            $js = 'jQuery(\'#datetimepicker\').datetimepicker();';
            if($this->input->get('save')=="true"){
                $js .= '$.notify("Save coupon success.", "success");';
            }
            $this->template->write('js', $js);
            //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Edit coupon');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
            //******* Defalut ********//
            $this->template->write_view('content', 'admin/coupons/edit', $render_data);
            $this->template->render();
        }

    }

    public function delete($id){
        if(!is_group(array('admin'))){
            redirect('admin');
            exit();
        }
        $coupon = $this->coupons->get_coupon($id);
        if(isset($coupon['coid']))
        {
            $this->coupons->delete_coupon($id);
            redirect('admin/coupons?delete=true');
        }
        else{
            show_error('The coupon you are trying to delete does not exist.');
        }
    }
}
