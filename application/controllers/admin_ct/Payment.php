<?php


class Payment extends CI_Controller
{
    public $render_data = array();

    function __construct()
    {
        parent::__construct();
        $this->template->set_template('admin');
        $this->load->model('Payment_model','payment');
    }

    /*
     * Listing of payments
     */
    function index()
    {
        if(!is_group('admin')){
            redirect('admin');
            exit();
        }
        $render_data['payments'] = $this->payment->get_all_payments();
        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Payment gateway setting');
        $this->template->write('user_id', $render_data['user']['aid']);
        $this->template->write('user_name', $render_data['user']['name']);
        $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
        $this->template->write_view('content', 'admin/payment/index', $render_data);
        $this->template->render();

    }

    /*
     * Adding a new payment
     */
    function add()
    {
        if(!is_group('admin')){
            redirect('admin');
            exit();
        }
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'title' => $this->input->post('title'),
                'bank_name' => $this->input->post('bank_name',true),
                'bank_acc' => $this->input->post('bank_acc',true),
                'bank_branch' => $this->input->post('bank_branch',true),
                'bank_type' => $this->input->post('bank_type',true),
                'type' => $this->input->post('type',true),
                'detail' => $this->input->post('detail',true)
            );

            $payment_id = $this->payment->add_payment($params);
            redirect('admin/payment/index');
        } else {
            $js = '  $(function(){
				CKEDITOR.replace( "body" ,{
					filebrowserBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html').'",
					filebrowserImageBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Images').'",
					filebrowserFlashBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Flash').'",
					filebrowserUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files').'",
					filebrowserImageUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images').'",
					filebrowserFlashUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash').'"
				});
				$("#slideshow_upload").hide();
				if ($("#is_slideshow").attr("checked") == "checked")
				{
					$("#slideshow_upload").show();
				}
				$("#is_slideshow").click(function(){
					if ($(this).attr("checked") == "checked")
					{
						$("#slideshow_upload").slideDown();
					}
					else
					{
						$("#slideshow_upload").slideUp();
					}
				});  
			});';
            $this->template->write('js', $js);
            //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Payment gateway setting');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
            //******* Defalut ********//
            $this->template->write_view('content', 'admin/payment/add', $render_data);
            $this->template->render();
        }
    }

    /*
     * Editing a payment
     */
   public function edit($pm_id)
    {
        if(!is_group('admin')){
            redirect('admin');
            exit();
        }
        // check if the payment exists before trying to edit it
        $render_data['payment'] = $this->payment->get_payment($pm_id);

        if (isset($render_data['payment']['pm_id'])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'title' => $this->input->post('title'),
                    'bank_name' => $this->input->post('bank_name',true),
                    'bank_acc' => $this->input->post('bank_acc',true),
                    'bank_branch' => $this->input->post('bank_branch',true),
                    'bank_type' => $this->input->post('bank_type',true),
                    'type' => $this->input->post('type',true),
                    'detail' => $this->input->post('detail',true)
                );

                $this->payment->update_payment($pm_id, $params);
                redirect('admin/payment/index');
            } else {
                $js = '  $(function(){
				CKEDITOR.replace( "body" ,{
					filebrowserBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html').'",
					filebrowserImageBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Images').'",
					filebrowserFlashBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Flash').'",
					filebrowserUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files').'",
					filebrowserImageUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images').'",
					filebrowserFlashUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash').'"
				});
				$("#slideshow_upload").hide();
				if ($("#is_slideshow").attr("checked") == "checked")
				{
					$("#slideshow_upload").show();
				}
				$("#is_slideshow").click(function(){
					if ($(this).attr("checked") == "checked")
					{
						$("#slideshow_upload").slideDown();
					}
					else
					{
						$("#slideshow_upload").slideUp();
					}
				});  
			});';
                $this->template->write('js', $js);
                //******* Defalut ********//
                $render_data['user'] = $this->session->userdata('fnsn');
                $this->template->write('title', 'Payment gateway setting');
                $this->template->write('user_id', $render_data['user']['aid']);
                $this->template->write('user_name', $render_data['user']['name']);
                $this->template->write('user_group', $render_data['user']['group']);
                //******* Defalut ********//
                $this->template->write_view('content', 'admin/payment/edit', $render_data);
                $this->template->render();
            }
        } else
            show_error('The payment you are trying to edit does not exist.');
    }

    /*
     * Deleting payment
     */
    function remove($pm_id)
    {
        if(!is_group('admin')){
            redirect('admin');
            exit();
        }
        $payment = $this->payment->get_payment($pm_id);

        // check if the payment exists before trying to delete it
        if (isset($payment['pm_id'])) {
            $this->payment->delete_payment($pm_id);
            redirect('admin/payment/index');
        } else
            show_error('The payment you are trying to delete does not exist.');
    }

}
