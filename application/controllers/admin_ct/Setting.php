<?php


class Setting extends CI_Controller
{
    public $render_data = array();

    public function __construct()
    {
        parent::__construct();

        $this->template->set_template('admin');
        $this->load->model('Setting_model', 'setting');
    }

    public function index()
    {
        if (!is_group('admin')) {
            redirect('admin');
            exit();
        }
        $render_data['setting'] = $this->setting->get_setting();
        $this->load->library('form_validation');
        if (isset($render_data['setting']['sid'])) {
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
            $this->form_validation->set_rules('site_title', 'Site title', 'required');
            $this->form_validation->set_rules('site_keyword', 'Site keyword', 'required');
            $this->form_validation->set_rules('site_description', 'Site description', 'required');
            $this->form_validation->set_rules('email_for_contact', 'Email for contact', 'required');
            $this->form_validation->set_rules('phone_for_contact', 'Phone for contact', 'required');
            if ($this->form_validation->run()) {
                $params = array(
                    'site_title' => $this->input->post('site_title'),
                    'site_keyword' => $this->input->post('site_keyword'),
                    'facebook' => $this->input->post('facebook'),
                    'google_plus' => $this->input->post('google'),
                    'instagram' => $this->input->post('instagram'),
                    'site_description' => $this->input->post('site_description'),
                    'email_for_contact' => $this->input->post('email_for_contact'),
                    'email_for_order' => $this->input->post('email_for_order'),
                    'shipping_zip' => $this->input->post('shipping_zip'),
                    'shipping_inarea' => $this->input->post('shipping_inarea'),
                    'shipping_outarea' => $this->input->post('shipping_outarea'),
                    'email_for_member' => $this->input->post('email_for_member'),
                    'phone' => $this->input->post('phone_for_contact')
                );

                $this->setting->update_setting($params);
                redirect('admin/setting?save=true');
            } else {
                if ($this->input->get('save') == "true") {
                    $js = '$.notify("Save setting success.", "success");';
                    $this->template->write('js', $js);
                }
                //******* Defalut ********//
                $render_data['user'] = $this->session->userdata('fnsn');
                $this->template->write('title', 'Site setting');
                $this->template->write('user_id', $render_data['user']['aid']);
                $this->template->write('user_name', $render_data['user']['name']);
                $this->template->write('user_group', $render_data['user']['group']);
                //******* Defalut ********//
                $this->template->write_view('content', 'admin/setting/edit', $render_data);
                $this->template->render();
            }
        } else {
            redirect('admin');
        }
    }

    function term(){

    }
}
