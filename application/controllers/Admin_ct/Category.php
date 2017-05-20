<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Category extends CI_Controller {

    public $render_data = array();

    public function __construct() {
        parent::__construct();

        $this->template->set_template('admin');
        $this->load->model('Product_category_model','category');
    }


    public function index() {
        if(!is_group('admin')){
            redirect('admin');
            exit();
        }
        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Product Categories');
        $this->template->write('user_id', $render_data['user']['aid']);
        $this->template->write('user_name', $render_data['user']['name']);
        $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//

        // ====== Java script Data tabale ======= //
        $js = "$(function(){
                        $('.dialog').hide();
                        $('.icon').hover(function(){
                            $(this).css('opacity',1);
                        },function(){
                            $(this).css('opacity',0.3);
                        })
                        $('.add_category').click(function(){
                            $('#add-category-dialog .parent_id').val($(this).attr('id').replace('parent_',''));
                            
                            $('#add-category-dialog').dialog({
                                width: 500,
                                buttons:{
                                    'Add new Category':function(){
                                        $('#add_form').submit();
                                    },
                                    'Cancel':function(){
                                        $(this).dialog( \"close\" );
                                    }
                                }
                            });
                        });
                        $('.edit_category').click(function(){
                            var edit_value = $(this).attr('rel').split(';');
                            $('#edit-category-title-en').val(edit_value[0]);
                            $('#edit-category-caption').val(edit_value[1]);
                            $('#edit-category-weight').val(edit_value[2]);
                            $('#edit-category-id').val($(this).attr('id').replace('edit_',''));
                            $('#edit-category-dialog #add-category_weight').val(0);
                            $('#edit-category-dialog').dialog({
                                width: 500,
                                buttons:{
                                    'Edit Category':function(){
                                        $('#edit_form').submit();
                                    },
                                    'Cancel':function(){
                                        $(this).dialog( \"close\" );
                                    }
                                }
                            });
                        });
                        
                        $('.delete_category').click(function(){
                            var term_id = $(this).attr('id').replace('delete_','');
                            var current_button = $(this);
                            var dialog = $('#delete-category-dialog').dialog({
                                width: 300,
                                buttons:{
                                    'Confirm':function(){
                                        $.post('".base_url('admin/category/delete')."',{term_id:term_id},function(){
                                            current_button.parent().parent().parent().slideUp();
                                            dialog.dialog( \"close\" );
                                            /*window.location.reload();*/
                                        });
                                        
                                    },
                                    'Cancel':function(){
                                        $(this).dialog( \"close\" );
                                    }
                                }
                            });
                        });
                    });";
        if($this->input->get('add')=="true"){
            $js .= '$.notify("Add new data success.", "success");';
        }
        if($this->input->get('delete')=="true"){
            $js .= '$.notify("Delete data success", "success");';
        }
        if($this->input->get('save')=="true"){
            $js .= '$.notify("Save data success.", "success");';
        }
        $render_data['product_category'] = $this->category->get_taxonomy_term('product_category');
        $this->template->write('js', $js);
        $this->template->write_view('content', 'admin/product_category/index', $render_data);
        $this->template->render();

    }

    



    public function add() {
        $data_save = array();
        $data_save['parent_id'] = $this->input->post('parent_id', TRUE);

        if ($data_save['parent_id'] == 0) {
            $data_save['parent_id'] = null;
        }

        $data_save['title'] = $this->input->post('title_en', TRUE);
        $data_save['body'] = $this->input->post('body', TRUE);
        $data_save['weight'] = floor($this->input->post('weight', TRUE));

        // Upload Header //
        $config['upload_path'] = './uploads/category_header_img/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('header_img')) {
            echo 'upload!!';
            $upload_data = $this->upload->data();
            $data_save['header_img'] = $upload_data['file_name'];
        }


        // Upload Cover //
        $config['upload_path'] = './uploads/category_cover_img/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);
        if ($this->upload->do_upload('cover_img')) {
            echo 'upload!!';
            $upload_data = $this->upload->data();
            $data_save['cover_img'] = $upload_data['file_name'];
        }

        $this->category->add_taxonomy_term('product_category', $data_save);

        redirect('admin/category');
    }

    public function edit() {
        $term_id = $this->input->post('term_id', TRUE);

        if ($term_id > 0) {
            $edit_save = array();

            $data_save['title'] = $this->input->post('title_en', TRUE);
            $data_save['body'] = $this->input->post('body', TRUE);
            $data_save['weight'] = floor($this->input->post('weight', TRUE));

            // Upload Header //
            $config['upload_path'] = './uploads/category_header_img/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('header_img')) {
                echo 'upload!!';
                $upload_data = $this->upload->data();
                $data_save['header_img'] = $upload_data['file_name'];
            }


            // Upload Cover //
            $config['upload_path'] = './uploads/category_cover_img/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);
            if ($this->upload->do_upload('cover_img')) {
                echo 'upload!!';
                $upload_data = $this->upload->data();
                $data_save['cover_img'] = $upload_data['file_name'];
            }
            $this->category->edit_taxonomy_term('product_category', $data_save, $term_id);
        }
        redirect('admin/category');
    }

    public function delete() {
        $term_id = $this->input->post('term_id', TRUE);
        if ($term_id > 0) {
            $this->category->delete_taxonomy_term('product_category', $term_id);
            echo 'success';
        }
    }

}

?>