<?php


class Products extends CI_Controller
{
    public $render_data = array();

    public function __construct()
    {
        parent::__construct();

        $this->template->set_template('admin');
        $this->load->model('Products_model', 'products');
    }

    /*
     * Listing of products
     */
    function index()
    {
        if (!is_group('admin')) {
            redirect('admin');
            exit();
        }
        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Products ');
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
                    "url": "' . base_url('admin/products/ajax') . '",
                    "type": "POST",
                    data:function(data){
                        data.group = $("#group").val();
                        data.online = $("#online").val();
                        data.in_stock = $("#in_stock").val();
                    }
                },
                "columnDefs": [
                { 
                    "targets": [5], 
                    "orderable": false,
                },
                ],
            });
            $("#show").change(function () {
                $("#table_length select").val($(this).val());
                $("#table_length select").trigger("change");
            });
            $("#search").on("keyup", function () {
                $("#table_filter input[type=\"search\"]").val($(this).val());
                $("#table_filter input[type=\"search\"]").trigger("keyup");
            });
            $("#group").change(function(){
                var table = $("#table").DataTable();
                table.ajax.reload();
            });$("#online").change(function(){
                var table = $("#table").DataTable();
                table.ajax.reload();
            });$("#in_stock").change(function(){
                var table = $("#table").DataTable();
                table.ajax.reload();
            });
        });

        ';
        if ($this->input->get('add') == "true") {
            $js .= '$.notify("Add new product success.", "success");';
        }
        if ($this->input->get('delete') == "true") {
            $js .= '$.notify("Delete product success", "success");';
        }
        if ($this->input->get('save') == "true") {
            $js .= '$.notify("Save product success.", "success");';
        }
        $render_data["groups"] = $this->products->group_all();
        $this->template->write('js', $js);
        $this->template->write_view('content', 'admin/products/index', $render_data);
        $this->template->render();
    }


    public function ajax()
    {
        if (!$this->input->is_ajax_request() || !is_group('admin')) {
            exit('No direct script access allowed');
        }

        $list = $this->products->get_all();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $products) {
            $no++;
            $row = array();
            $row[] = '<a href="' . base_url('product/' . $products->id . '/' . url_title($products->title)) . '" target="_blank"><img src="' . base_url('timthumb.php?src=') . base_url('uploads/products/' . $products->cover) . '&w=150&h=150&z=c" target="_blank" style="width:150px; height:auto;"></a>';
            $row[] = '<a href="' . base_url('admin/products/edit/'. $products->id) . '">' . $products->title . '</a>';
            $row[] = $products->model_code;
            $row[] = $products->group;
            $row[] = is_online($products->online);
            $row[] = $products->normal_price;
            $row[] = $products->special_price;
            $row[] = in_stock($products->in_stock);
            $btnpdf = ($products->pdf) ? '<a href="' . base_url('frontend/product_pdf_download/' . $products->id . '/' . md5($products->id . 'suwichalala') . '/' . url_title($products->title)) . '_Specification.pdf' . '" class="label label-info"><i class="fa fa-download"></i> PDF</a> ' : '';
            $row[] = $btnpdf . '
<a href="' . base_url('admin/products/edit/' . $products->id) . '" class="label label-warning"><i class="fa fa-pencil"></i> Edit</a> 
            <a href="' . base_url('admin/products/delete/' . $products->id) . '" class="label label-danger"  onclick="return confirm(\'Are you sure?\')"><i class="fa fa-times-circle"></i> Delete</a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->products->count_all(),
            "recordsFiltered" => $this->products->count_filtered(),
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
        $this->load->library('upload');
        $config['upload_path'] = './' . PRODUCT_PATH . '/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);
        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
        $this->form_validation->set_rules('title', 'Product title', 'required|max_length[80]');
        $this->form_validation->set_rules('group', 'Product group', 'required|max_length[80]');
        $this->form_validation->set_rules('body', 'Product Description', 'required|min_length[10]');
        $this->form_validation->set_rules('product_price', 'Product normal price', 'required');
        $this->form_validation->set_rules('product_spacial_price', 'Product special price', 'required');
        $this->form_validation->set_rules('model_code', 'Product Model Code', 'required');
        $this->form_validation->set_rules('product_in_stock', 'Product In Stock', 'required');
        $this->form_validation->set_rules('taxonomy_term_id', 'Product Category', 'required');
        $this->form_validation->set_rules('product_online', 'Product is color', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');
        if ($this->form_validation->run()) {
            $params = array(
                'title' => $this->input->post('title'),
                'info' => $this->input->post('info'),
                'body' => $this->input->post('body'),
                'group' => strtolower($this->input->post('group', TRUE)),
                'normal_price' => $this->input->post('product_price'),
                'special_price' => $this->input->post('product_spacial_price'),
                'model_code' => $this->input->post('model_code'),
                'in_stock' => $this->input->post('product_in_stock'),
                'taxonomy_term_id' => $this->input->post('taxonomy_term_id'),
                'online' => $this->input->post('product_online'),
                'att_type'=> $this->input->post('type')
            );
            if ($this->upload->do_upload('cover')) {
                //Get Cover DATA
                $upload_data = $this->upload->data();
                $params['cover'] = $upload_data['file_name'];

                // Upload PDF //
                if (!empty($_FILES['pdf']['name'])) {
                    $config = array();
                    $config['upload_path'] = './' . PDF_PATH . '/';
                    $config['allowed_types'] = 'pdf';
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('pdf')) {
                        $upload_data = $this->upload->data();
                        $params['pdf'] = $upload_data['file_name'];
                    } else {
                        redirect('admin/products/add/?pdf=error');
                    }
                }
            } else {
                redirect('admin/products/add/?upload=error');
            }
            if ($iid = $this->products->add_product($params)) {
                //add product alt
                foreach ($_POST['code'] as $k => $code) {
                    if ($this->input->post('type') == 'color') {
                        $color = strtoupper($_POST['color'][$k]);
                    } elseif ($this->input->post('type') == 'model') {
                        $color = '';
                    } else {
                        $color = '';
                    }
                    $p_cover = '';
                    if (!empty($_FILES['photo']['tmp_name'][$k])) {

                        $config = array();
                        $config['upload_path'] = './' . PRODUCT_PATH . '/';
                        $config['allowed_types'] = 'gif|jpg|png';
                        $config['encrypt_name'] = true;
                        $this->upload->initialize($config);

                        $_FILES['images[]']['name']= $_FILES['photo']['name'][$k];
                        $_FILES['images[]']['type']= $_FILES['photo']['type'][$k];
                        $_FILES['images[]']['tmp_name']= $_FILES['photo']['tmp_name'][$k];
                        $_FILES['images[]']['error']= $_FILES['photo']['error'][$k];
                        $_FILES['images[]']['size']= $_FILES['photo']['size'][$k];


                        if ($this->upload->do_upload('images[]')) {
                            $upload_data = $this->upload->data();
                            $p_cover = $upload_data['file_name'];
                        }
                    }

                    $param_alt = array(
                        'pid' => $iid,
                        'code' => $code,
                        'p_type' => $this->input->post('type'),
                        'normal_price' => $_POST['price'][$k],
                        'special_price' => $_POST['sp_price'][$k],
                        'p_value' => $_POST['value'][$k],
                        'color' => $color,
                        'p_cover' => $p_cover,
                        'in_stock' => $_POST['stock'][$k]
                    );
                    $this->products->add_product_alt($param_alt);

                }

                redirect('admin/products/?add=true');
            } else {
                redirect('admin/products/add');
            }

        } else {
            $js = '$(\'select[name="taxonomy_term_id"]\').change(function(){
                $("input[name=\'group\']").val("");
            });
            $("input[name=\'group\']").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "' . base_url() . 'admin/products/ajax_get_group",
                        dataType: "json",
                        type: "POST",
                        data: {
                            keyword: request.term,
                            term_id: $(\'select[name="taxonomy_term_id"]\').val()
                        },
                        complete: function (data) {
                            $("input[name=\'group\']").removeClass(\'ui-autocomplete-loading\');
                        },
                        success: function (data) {

                            response(data);
                        }
                    });
                },
                minLength: 3
            });
            var attr_type;
            $("#add-color").click(function(){
                $("#type").val("color");
                attr_type = "color";
                $("#box-type").hide();
                $("#first-box").show();
                $("#add-at,#reset-all").show();
                $("#color-boxed").show();
                $("#other-boxed label").html("Color (Text)*:");
            });
            
            $("#add-model").click(function(){
                $("#type").val("model");
                attr_type = "model";
                $("#box-type").hide();
                $("#first-box").show();
                $("#add-at,#reset-all").show();
                $("#color-boxed").hide();
                $("#other-boxed label").html("Model (Text)*:");
            });
            $("#add-size").click(function(){
                $("#type").val("size");
                attr_type = "size";
                $("#box-type").hide();
                $("#first-box").show();
                $("#add-at,#reset-all").show();
                $("#color-boxed").hide();
                $("#other-boxed label").html("Size (Text)*:");
            });
            $("#reset-all").click(function(){
                attr_type = "";
                $("#box-type").show();
                $("#more-att").html("");
                $("#add-at,#reset-all").hide();
                $("#color-boxed").show();
                $("#other-boxed label").html("Color (Text)*:");
            });
            
            $(\'#add-at\').click(function(){
var num = $(\'.sub-alt\').length + 1;
var html = \'<div class="clearfix row sub-alt" id="at-\'+num+\'" style="padding-right: 20px;"><div class="thumbnail clearfix ">\';
html += \'<button type="button" class="btn btn-sm btn-danger pull-right delete-at" data-id="\'+num+\'"><i class="fa fa-times-circle"></i> </button>\';
html += \'<div class="clearfix"></div> <div class="col-md-6"> <div class="form-group"> <label>Code*:</label>\';
html += \'<input type="text" name="code[]" value="\' + $("#product_model_code").val() + \'" class="form-control" required/>\';
html += \'</div><div class="form-group"><label>Photo</label><input type="file" name="photo[]" class="form-control"/></div><div class="col-md-6 no-padding"><div class="form-group"><label>Price*:</label>\';
html += \'<input type="text" name="price[]" value="\' + $("#product_price").val() + \'" class="form-control digi" required/></div></div><div class="col-md-6 no-padding">\';
html += \'<div class="form-group"><label>Special Price*:</label>\';
html += \'<input type="text" name="sp_price[]"  value="\' + $("#product_spacial_price").val() + \'" class="form-control digi" required/>\';
html += \'</div></div></div><div class="col-md-6">\';

if(attr_type==="color"){
html += \'<div class="form-group"><label>Color*:</label>\';
html += \'<input type="hidden" name="color[]" id="color-selector-\'+num+\'" value="#ffffff" class="form-control color-input" required/>\';
html += \'<div class="color-box"><div class="color-active"></div>\';
html += \'<div class="color-select color-1" data-hex="#ffffff" data-text="สีขาว"></div>\';
html += \'<div class="color-select color-2" data-hex="#1B88CB" data-text="สีฟ้า"></div>\';
html += \'<div class="color-select color-3" data-hex="#12A144" data-text="สีเขียว"></div>\';
html += \'<div class="color-select color-4" data-hex="#FDDA1A" data-text="สีเหลือง"></div>\';
html += \'<div class="color-select color-5" data-hex="#0E1522" data-text="สีดำ"></div>\';
html += \'<div class="color-select color-6" data-hex="#CD2026" data-text="สีแดง"></div>\';
html += \'<div class="color-select color-7" data-hex="#7E2683" data-text="สีม่วง"></div>\';
html += \'<div class="color-select color-8" data-hex="#F05C21" data-text="สีส้ม"></div>\';
html += \'<div class="color-select-picker" id="color-picker-\'+num+\'"></div><div class="clearfix"></div></div></div>\';
html += \'<div class="form-group"><label>Color (Text)*:</label>\';
}
if(attr_type==="model"){
html += \'<div class="form-group"><label>Model (Text)*:</label>\';
}
if(attr_type==="size"){
html += \'<div class="form-group"><label>Size (Text)*:</label>\';
}
html += \'<input type="text" name="value[]" class="form-control" required/></div><div class="form-group">\';
html += \'<label>Product In Stock*:</label><select name="stock[]" class="form-control" required>\';
html += \'<option value="1">YES</option><option value="0">NO</option>\';
html += \'</select></div></div></div></div>\';
$(\'#more-att\').append(html);
$(\'#color-picker-\'+num).ColorPicker({
  color: \'#0000ff\',
  onShow: function (colpkr) {
    $(colpkr).fadeIn(200);
    return false;
  },
  onHide: function (colpkr) {
    $(colpkr).fadeOut(200);
    return false;
  },
  onChange: function (hsb, hex, rgb) {

  $(\'#color-picker-\'+num).parent().find(\'.color-active\').css(\'backgroundColor\', "#"+hex);
  $(\'#color-picker-\'+num).parent().parent().find(\'input\').val("#"+hex);
  }
});
});

$(document).on("click",".delete-at",function(){
$(\'#at-\'+$(this).data("id")).remove();
});
$(\'#color-picker-0\').ColorPicker({
  color: \'#0000ff\',
  onShow: function (colpkr) {
    $(colpkr).fadeIn(200);
    return false;
  },
  onHide: function (colpkr) {
    $(colpkr).fadeOut(200);
    return false;
  },
  onChange: function (hsb, hex, rgb) {

  $(\'#color-picker-0\').parent().find(\'.color-active\').css(\'backgroundColor\', "#"+hex);
  $(\'#color-picker-0\').parent().parent().find(\'input\').val("#"+hex);
  }
});
$(document).on(\'click\', \'.color-select\', function () {
  var hex = $(this).data(\'hex\');
  var text = $(this).data("text");
  $(this).parent().find(\'.color-active\').css(\'backgroundColor\', hex);
  $(this).parent().parent().find(\'input\').val(hex);
  $(this).parent().parent().parent().find("input[name^=value]").val(text);
});
$(function(){
				CKEDITOR.replace( "info" ,{
					filebrowserBrowseUrl : "' . base_url('js/ckfinder/ckfinder.html') . '",
					filebrowserImageBrowseUrl : "' . base_url('js/ckfinder/ckfinder.html?type=Images') . '",
					filebrowserFlashBrowseUrl : "' . base_url('js/ckfinder/ckfinder.html?type=Flash') . '",
					filebrowserUploadUrl : "' . base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') . '",
					filebrowserImageUploadUrl : "' . base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') . '",
					filebrowserFlashUploadUrl : "' . base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') . '"
				});
				
			});
';
            if ($this->input->get('upload') == "error") {
                $js .= '$.notify("Can\'n upload cover photo.", "warning");';
            }
            if ($this->input->get('pdf') == "error") {
                $js .= '$.notify("Can\'n upload PDF file!.", "warning");';
            }
            $this->template->write('js', $js);
            //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Add new product');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
            //******* Defalut ********//
            $this->load->model('taxonomy_model', 'taxonomy');
            $render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
            $this->template->write_view('content', 'admin/products/add', $render_data);
            $this->template->render();
        }
    }


    public function edit($pid = '')
    {
        $this->load->library('form_validation');
        if (!is_group(array('admin'))) {
            redirect('admin');
            exit();
        }
        if ($pid == "" || !$product = $this->products->get_product($pid)) {
            redirect('admin/products');
            exit();
        }
        $this->load->library('upload');
        $config['upload_path'] = './' . PRODUCT_PATH . '/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['encrypt_name'] = true;
        $this->upload->initialize($config);
        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
        $this->form_validation->set_rules('title', 'Product title', 'required|max_length[80]');
        $this->form_validation->set_rules('group', 'Product group', 'required|max_length[80]');
        $this->form_validation->set_rules('body', 'Product Description', 'required|min_length[10]');
        $this->form_validation->set_rules('product_price', 'Product normal price', 'required');
        $this->form_validation->set_rules('product_spacial_price', 'Product special price', 'required');
        $this->form_validation->set_rules('model_code', 'Product Model Code', 'required');
        $this->form_validation->set_rules('product_in_stock', 'Product In Stock', 'required');
        $this->form_validation->set_rules('taxonomy_term_id', 'Product Category', 'required');
        $this->form_validation->set_rules('product_online', 'Product is color', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');
        if ($this->form_validation->run()) {
            $params = array(
                'title' => $this->input->post('title'),
                'info' => $this->input->post('info'),
                'body' => $this->input->post('body'),
                'group' => strtolower($this->input->post('group', TRUE)),
                'normal_price' => $this->input->post('product_price'),
                'special_price' => $this->input->post('product_spacial_price'),
                'model_code' => $this->input->post('model_code'),
                'in_stock' => $this->input->post('product_in_stock'),
                'taxonomy_term_id' => $this->input->post('taxonomy_term_id'),
                'att_type'=> $this->input->post('type'),
                'online' => $this->input->post('product_online')
            );
            if (!empty($_FILES['cover']['name'])) {
                if ($this->upload->do_upload('cover')) {
                    //Get Cover DATA
                    $upload_data = $this->upload->data();
                    $params['cover'] = $upload_data['file_name'];
                    if (file_exists('./' . UPLOAD_PATH . '/' . $product['cover'])) {
                        unlink('./' . UPLOAD_PATH . '/' . $product['cover']);
                    }
                } else {
                    redirect('admin/products/edit/' . $pid . '?upload=error');
                }
            } else {
                $params['cover'] = $product['cover'];
            }
            // Upload PDF //
            if (!empty($_FILES['pdf']['name'])) {
                $config = array();
                $config['upload_path'] = './' . PDF_PATH . '/';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if ($this->upload->do_upload('pdf')) {
                    $upload_data = $this->upload->data();
                    $params['pdf'] = $upload_data['file_name'];
                    if (file_exists('./' . PDF_PATH . '/' . $product['pdf'])) {
                        unlink('./' . PDF_PATH . '/' . $product['pdf']);
                    }
                } else {
                    redirect('admin/products/edit/' . $pid . '?pdf=error');
                }
            } else {
                if ($this->input->post('delete-pdf') == "true") {
                    if (file_exists('./' . PDF_PATH . '/' . $product['pdf'])) {
                        unlink('./' . PDF_PATH . '/' . $product['pdf']);
                    }
                    $params['pdf'] = "";
                } else {
                    $params['pdf'] = $product['pdf'];
                }
            }

            if ($this->products->save_product($pid, $params)) {
                //add product alt

                foreach ($_POST['code'] as $k => $code) {
                    if ($this->input->post('type') == 'color') {
                        $color = strtoupper($_POST['color'][$k]);
                    } elseif ($this->input->post('type') == 'model') {
                        $color = '';
                    } else {
                        $color = '';
                    }
                    $param_alt = array(
                        'pid' => $pid,
                        'code' => $code,
                        'normal_price' => $_POST['price'][$k],
                        'special_price' => $_POST['sp_price'][$k],
                        'p_type' => $this->input->post('type'),
                        'p_value' => $_POST['value'][$k],
                        'color' => $color,
                        'in_stock' => $_POST['stock'][$k]
                    );

                    $p_cover = '';
                    if (!empty($_FILES['photo']['tmp_name'][$k])) {

                        $config = array();
                        $config['upload_path'] = './' . PRODUCT_PATH . '/';
                        $config['allowed_types'] = 'gif|jpg|png';
                        $config['encrypt_name'] = true;
                        $this->upload->initialize($config);

                        $_FILES['images[]']['name']= $_FILES['photo']['name'][$k];
                        $_FILES['images[]']['type']= $_FILES['photo']['type'][$k];
                        $_FILES['images[]']['tmp_name']= $_FILES['photo']['tmp_name'][$k];
                        $_FILES['images[]']['error']= $_FILES['photo']['error'][$k];
                        $_FILES['images[]']['size']= $_FILES['photo']['size'][$k];


                        if ($this->upload->do_upload('images[]')) {
                            $upload_data = $this->upload->data();
                            $param_alt['p_cover'] = $upload_data['file_name'];
                        }
                    }


                    if (isset($_POST['at-id'][$k]) && $_POST['at-id'][$k] != "") {
                        $this->products->save_product_alt($_POST['at-id'][$k], $param_alt);
                    } else {
                        $this->products->add_product_alt($param_alt);
                    }
                }
                $delete_arr = array_unique(explode(",", $_POST['deleted-alt']));

                foreach ($delete_arr as $del) {
                    $this->products->delete_alt($del);
                }
                redirect('admin/products/edit/' . $pid . '?save=success');
            } else {
                redirect('admin/products/edit/' . $pid . '?save=error');
            }

        } else {
            $js = '$(\'select[name="taxonomy_term_id"]\').change(function(){
                $("input[name=\'group\']").val("");
            });
            $("input[name=\'group\']").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "' . base_url() . 'admin/products/ajax_get_group",
                        dataType: "json",
                        type: "POST",
                        data: {
                            keyword: request.term,
                            term_id: $(\'select[name="taxonomy_term_id"]\').val()
                        },
                        complete: function (data) {
                            $("input[name=\'group\']").removeClass(\'ui-autocomplete-loading\');
                        },
                        success: function (data) {

                            response(data);
                        }
                    });
                },
                minLength: 3
            });
            var attr_type = "'.$product['att_type'].'";
            $("#add-color").click(function(){
                $("#type").val("color");
                attr_type = "color";
                $("#box-type").hide();
                $("#first-box").show();
                $("#add-at,#reset-all").show();
                $("#color-boxed").show();
                $("#other-boxed label").html("Color (Text)*:");
            });
            
            $("#add-model").click(function(){
                $("#type").val("model");
                attr_type = "model";
                $("#box-type").hide();
                $("#first-box").show();
                $("#add-at,#reset-all").show();
                $("#color-boxed").hide();
                $("#other-boxed label").html("Model (Text)*:");
            });
            $("#add-size").click(function(){
                $("#type").val("size");
                attr_type = "size";
                $("#box-type").hide();
                $("#first-box").show();
                $("#add-at,#reset-all").show();
                $("#color-boxed").hide();
                $("#other-boxed label").html("Size (Text)*:");
            });
            $("#reset-all").click(function(){
                attr_type = "";
                $(".delete-at").each(function(i,v){
                if($(this).data("aid")){
                $("#deleted-alt").val($("#deleted-alt").val()+","+$(this).data("aid"));
                }
                });
                $(".thumb-attr").remove();
                $("#box-type").show();
                $("#more-att").html("");
                $("#add-at,#reset-all").hide();
                $("#color-boxed").show();
                $("#other-boxed label").html("Color (Text)*:");
            });
            $(\'#add-at\').click(function(){
var num = $(\'.sub-alt\').length + 1;
var html = \'<div class="clearfix row sub-alt" id="at-\'+num+\'" style="padding-right: 20px;"><div class="thumbnail clearfix ">\';
html += \'<button type="button" class="btn btn-sm btn-danger pull-right delete-at" data-id="\'+num+\'"><i class="fa fa-times-circle"></i> </button>\';
html += \'<div class="clearfix"></div> <div class="col-md-6"> <div class="form-group"> <label>Code</label>\';
html += \'<input type="text" name="code[]" value="\' + $("#product_model_code").val() + \'" class="form-control" required/>\';
html += \'</div><div class="form-group"><label>Photo</label><input type="file" name="photo[]" class="form-control"/></div><div class="col-md-6 no-padding"><div class="form-group"><label>Price*:</label>\';
html += \'<input type="text" name="price[]" value="\' + $("#product_price").val() + \'" class="form-control digi" required/></div></div>\';
html += \'<div class="col-md-6 no-padding"><div class="form-group"><label>Special Price*:</label>\';
html += \'<input type="text" name="sp_price[]"  value="\' + $("#product_spacial_price").val() + \'" class="form-control digi" required/>\';
html += \'</div></div></div><div class="col-md-6">\';

if(attr_type==="color"){
html += \'<div class="form-group"><label>Color*:</label>\';
html += \'<input type="hidden" name="color[]" id="color-selector-\'+num+\'" value="#ffffff" class="form-control color-input" required/>\';
html += \'<div class="color-box"><div class="color-active"></div>\';
html += \'<div class="color-select color-1" data-hex="#ffffff" data-text="สีขาว"></div>\';
html += \'<div class="color-select color-2" data-hex="#1B88CB" data-text="สีฟ้า"></div>\';
html += \'<div class="color-select color-3" data-hex="#12A144" data-text="สีเขียว"></div>\';
html += \'<div class="color-select color-4" data-hex="#FDDA1A" data-text="สีเหลือง"></div>\';
html += \'<div class="color-select color-5" data-hex="#0E1522" data-text="สีดำ"></div>\';
html += \'<div class="color-select color-6" data-hex="#CD2026" data-text="สีแดง"></div>\';
html += \'<div class="color-select color-7" data-hex="#7E2683" data-text="สีม่วง"></div>\';
html += \'<div class="color-select color-8" data-hex="#F05C21" data-text="สีส้ม"></div>\';
html += \'<div class="color-select-picker" id="color-picker-\'+num+\'"></div><div class="clearfix"></div></div></div>\';
html += \'<div class="form-group"><label>Color (Text)*:</label>\';
}
if(attr_type==="model"){
html += \'<div class="form-group"><label>Model (Text)*:</label>\';
}
if(attr_type==="size"){
html += \'<div class="form-group"><label>Size (Text)*:</label>\';
}
html += \'<input type="text" name="value[]" class="form-control" required/></div><div class="form-group">\';
html += \'<label>Product In Stock</label><select name="stock[]" class="form-control" required>\';
html += \'<option value="1">YES</option><option value="0">NO</option>\';
html += \'</select></div></div></div></div>\';
$(\'#more-att\').append(html);
$(\'#color-picker-\'+num).ColorPicker({
  color: \'#0000ff\',
  onShow: function (colpkr) {
    $(colpkr).fadeIn(200);
    return false;
  },
  onHide: function (colpkr) {
    $(colpkr).fadeOut(200);
    return false;
  },
  onChange: function (hsb, hex, rgb) {

  $(\'#color-picker-\'+num).parent().find(\'.color-active\').css(\'backgroundColor\', "#"+hex);
  $(\'#color-picker-\'+num).parent().parent().find(\'input\').val("#"+hex);
  }
});
});

$(document).on("click",".delete-at",function(){
$(\'#at-\'+$(this).data("id")).remove();
$("#deleted-alt").val($("#deleted-alt").val()+","+$(this).data("aid"));
});
var _this;
$(\'.color-select-picker\').click(function(){
_this = $(this);
});


$(\'.color-select-picker\').ColorPicker({
  color: \'#0000ff\',
  onShow: function (colpkr) {
    $(colpkr).fadeIn(200);
    return false;
  },
  onHide: function (colpkr) {
    $(colpkr).fadeOut(200);
    return false;
  },
  onChange: function (hsb, hex, rgb) {
  $(_this).parent().find(\'.color-active\').css(\'backgroundColor\', "#"+hex);
  $(_this).parent().parent().find(\'input\').val("#"+hex);
  }
});
$(document).on(\'click\', \'.color-select\', function () {
  var hex = $(this).data(\'hex\');
  var text = $(this).data("text");
  $(this).parent().find(\'.color-active\').css(\'backgroundColor\', hex);
  $(this).parent().parent().find(\'input\').val(hex);
  $(this).parent().parent().parent().find("input[name^=value]").val(text);
});
$(document).on("click","#remove-pdf",function(){
$("#delete-pdf").val("true");
$(this).remove();
$("#download-pdf").remove();
return false;
});

$(function(){
				CKEDITOR.replace( "info" ,{
					filebrowserBrowseUrl : "' . base_url('js/ckfinder/ckfinder.html') . '",
					filebrowserImageBrowseUrl : "' . base_url('js/ckfinder/ckfinder.html?type=Images') . '",
					filebrowserFlashBrowseUrl : "' . base_url('js/ckfinder/ckfinder.html?type=Flash') . '",
					filebrowserUploadUrl : "' . base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') . '",
					filebrowserImageUploadUrl : "' . base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') . '",
					filebrowserFlashUploadUrl : "' . base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') . '"
				});
				
			});
';
        if ($this->input->get('upload') == "error") {
            $js .= '$.notify("Can\'n upload cover photo.", "warning");';
        }
        if ($this->input->get('pdf') == "error") {
            $js .= '$.notify("Can\'n upload PDF file!.", "warning");';
        }
        if ($this->input->get('save') == "success") {
            $js .= '$.notify("Save product success.", "success");';
        }
        if ($this->input->get('save') == "error") {
            $js .= '$.notify("Can\'n save product!.", "warning");';
        }
        $this->template->write('js', $js);
        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Edit product');
        $this->template->write('user_id', $render_data['user']['aid']);
        $this->template->write('user_name', $render_data['user']['name']);
        $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
        $render_data['product'] = $product;
        $render_data['product_alts'] = $this->products->get_product_alt($pid);
        $this->load->model('taxonomy_model', 'taxonomy');
        $render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
        $this->template->write_view('content', 'admin/products/edit', $render_data);
        $this->template->render();
    }
}


function delete($pid)
{
    if (!is_group(array('admin'))) {
        redirect('admin');
        exit();
    }
    $product = $this->products->get_product($pid);
    if (isset($product['id'])) {
        $this->products->product_delete($pid);
        redirect('admin/products?delete=true');
    } else {
        show_error('The product you are trying to delete does not exist.');
    }
}


public
function ajax_get_group()
{
    $term_id = $this->input->post('term_id');
    $keyword = $this->input->post('keyword');
    $result = $this->db->distinct()->select('group')
        ->where('taxonomy_term_id', $term_id)
        ->like('group', $keyword)->order_by('group')->get('products');

    if ($result->num_rows() > 0) {
        $return_list = $result->result_array();
        $return_array = array();
        foreach ($return_list as $row) {
            $return_array[] = $row['group'];
        }
        echo json_encode($return_array);
    }
}

}
