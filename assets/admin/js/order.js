var total_normal = 0;
var total_sp_discount = 0;
var total_amount = 0;
var discount_10k = 0;


var discount_code_amount = 0;
var total_discount = 0;
var total_before_vat = 0;
var total_vat = 0;
var total = 0;


function init_order() {
    cal_order();


    $(document).on("change", ".product_qty", function () {
        var _num = parseInt($(this).val());
        if (_num <= 0) {
            _num = 1;
            $(this).val(_num);
        }
        update_product("edit", "p" + $(this).data('id'), 'qty', _num);
    });
    $(document).on("change", ".product_amount", function () {
        var _num = parseInt($(this).val());
        if (_num < 0) {
            _num = 0;
            $(this).val(_num);
        }
        update_product("edit", "p" + $(this).data('id'), 'price', _num);
    });

    $(document).on("change", ".product_spacial_amount", function () {
        var _num = parseInt($(this).val());
        if (_num < 0) {
            _num = 0;
            $(this).val(_num);
        }
        update_product("edit", "p" + $(this).data('id'), 'sp_price', _num);
    });

    $(document).on("change", "#custom-discount", function () {
        var _num = parseInt($(this).val());
        if (_num < 0) {
            _num = 0;
            $(this).val(_num);
        }
        custom_discount = _num;
        cal_order();
    });

    $(document).on("change", "#shipping-amount", function () {
        var _num = parseInt($(this).val());
        if (_num < 0) {
            _num = 0;
            $(this).val(_num);
        }
        shipping = _num;
        cal_order();
    });

    $(document).on("click", ".delete-product", function () {

        if (Object.size(products) === 1) {
            alert('ไม่สามารถลบรายการนี้ได้ จำเป็นต้องมีรายการสินค้าอย่างน้อย 1 รายการ');
            return;
        }
        $('#p-' + $(this).data('id')).remove();
        update_product("delete", "p" + $(this).data('id'), null, null);
    });


    //add product

    $(document).on("change", "#product-select", function () {
        var pid = $(this).val();
        if (pid !== "") {
            pid = pid.split('|');
            $("#code-select").html('<option value="">== Loading ==</option>');
            $.ajax({
                method: "POST",
                data: {pid: pid[0]},
                url: "/admin/orders/ajax_get_attribute"
            }).done(function (alt) {
                $("#code-select").html('<option value="">== Select ==</option>');
                var obj = jQuery.parseJSON(alt);
                obj.forEach(function (v) {
                    $("#code-select").append('<option value="' + v['pa_id'] + '|' + v['color'] + '|' + v['p_value'] + '|' + v['normal_price'] + '|' + v['special_price'] + '|' + v['code']+ '|' + v['minimum'] + '">' + v['code'] + ' - ' + v['p_value'] + '</option>');
                });
            });
        }
        $("#code-select").html('<option value="">== Select ==</option>');
        $("#product-color").hide();
        $("#add-product-price").html('');
        $("#add-product-sp-price").html('');
        $("#add-minimum").html('');

    });

    $("#product-color").hide();
    $(document).on("change", "#code-select", function () {
        var d = $(this).val();
        if (d === "") {
            $("#product-color").hide();
            $("#add-product-price").html('');
            $("#add-product-sp-price").html('');
            $("#add-minimum").html('');
            return;
        }
        var data = d.split("|");
        $("#product-color").css('background-color', data[1]);
        $("#product-color").show();
        $("#add-product-price").html(data[3]);
        $("#add-product-sp-price").html(data[4]);
        $("#add-minimum").html(data[6]);

    });
    $(document).on("click", "#add-product-btn", function () {
        if ($("#code-select").val() !== "" && $("#product-select").val() !== "") {
            var p = $("#product-select").val();
            var a = $("#code-select").val();
            var a2 = a.split("|");
            update_product("add", "p" + a2[0], null, [p, a]);
        }
    });

    $(document).on("click", "#submit-coupon", function () {
        if ($('#coupon').val().length <= 0) {
            return;
        }

        $.ajax({
            method: "POST",
            data: {code: $('#coupon').val()},
            url: "/admin/orders/ajax_get_coupon"
        }).done(function (status) {
            var obj = jQuery.parseJSON(status);
            if (obj.status === "error") {
                $('#coupon').val('');
                $("#coupon-amount").html('ไม่พบรหัสส่วนลดนี้หรือหมดอายุไปแล้ว');
            } else {
                discount_code_value = obj['discount'];
                discount_code = obj['code'];
                cal_order();
            }
        });
    });


    $(document).on("click", "#save-order", function () {
        $(this).attr('disabled', 'disabled').html('Saving...');

        var json = JSON.stringify(products);
        $.ajax({
            method: "POST",
            data: {coupon: $('#coupon').val(), products: json, shipping: shipping,custom_discount:custom_discount},
            url: window.location.href,
        }).done(function (status) {
            var obj = jQuery.parseJSON(status);
            if (obj.status === "error") {
                if(obj.message){
                    $.notify(obj.message, "error");
                }else{
                    $.notify("Can't save order.", "error");
                }

            } else {
                $.notify("Save order success.", "success");
            }
            $('#save-order').removeAttr('disabled').html('<i class="fa fa-check"></i> Save');
        });

    });

    $(document).on("click", "#save-shipping", function () {
        $(this).attr('disabled', 'disabled').html('Saving...');

        $.ajax({
            method: "POST",
            data: {
                name: $('#shipping_name').val(),
                address: $('#shipping_address').val(),
                province: $('#shipping_province').val(),
                zip: $('#shipping_zip').val(),
                bil_name: $('#billing_name').val(),
                bil_address: $('#billing_address').val(),
                bil_province: $('#billing_province').val(),
                bil_zip: $('#billing_zip').val(),
                oid: $('#oid').val()
            },
            url: '/admin/orders/save_shipping',
        }).done(function (status) {
            var obj = jQuery.parseJSON(status);
            if (obj.status === "error") {
                $.notify("Can't save shipping address.", "error");
            } else {
                $.notify("Save shipping address success.", "success");
            }
            $('#save-shipping').removeAttr('disabled').html('<i class="fa fa-check"></i> Save');
        });

    });


    var options = {
        beforeSubmit: showRequest,
        success: showResponse
    };

    $('#ajax-upload-document').ajaxForm(options);


    $(document).on('click', '.delete-file', function () {
        if (confirm("Confirm delete file?")) {
            var fid = $(this).data('fid');
            $.ajax({
                method: "POST",
                data: {
                    fid: fid
                },
                url: '/admin/orders/ajax_delete_file',
            }).done(function (status) {
                $.notify("Delete file success.", "success");
                ajax_list_files();
            });
        }
    });

    $('.select-product').click(function () {
        var ids = $('#id-products').val();
        var a_ids = ids.split(',');
        if ($(this).is(':checked')) {
            a_ids.push($(this).val());
        } else {
            var index = a_ids.indexOf($(this).val());
            console.log(index);
            a_ids.splice(index, 1);
        }
        $('#id-products').val(a_ids.join(','));

    })
}

function showRequest(formData, jqForm, options) {
    $('#add-file-btn').html('Uploading...').attr('disabled', 'disabled');
    return true;

}
function showResponse(responseText, statusText, xhr, $form) {
    $('#add-file-btn').html('Upload').removeAttr('disabled');
    var obj = jQuery.parseJSON(responseText);
    if (obj.status == 'success') {
        $('#addFileModal').modal('hide');
        ajax_list_files();
    } else {
        alert(obj.message);
    }

}


function update_product(type, index, key, value) {
    if (type === "add") {
        var prod = value[0].split("|");
        var att = value[1].split("|");
        var minimum = parseInt($("#add-minimum").html());
        if(minimum<=0){
            minimum = 1;
        }

        if (("p" + att[0] in products)) {
            return;
        }
        products["p" + att[0]] = {
            pid: prod[0],
            title: prod[1],
            price: att[3],
            sp_price: att[4],
            qty: minimum
        }
        var remark = '';
        if(minimum > 1){
            remark = '*ต้องสั่งขั้นต่ำอย่างน้อย '+minimum+' หน่วย';
        }

        $('#body-product').append('<tr id="p-' + att[0] + '"> <td><a href="/product/' + prod[0] + '/' + convertToSlug(prod[1]) + '" target="_blank">' + prod[1] + '</a> [' + att[5] + '] -  ' + att[2] + '</td><td><input type="text" value="' + att[3] + '" class="form-control product_amount digi" data-id="' + att[0] + '" id="product_amount-' + att[0] + '"></td> <td><input type="text" value="' + att[4] + '" class="form-control product_spacial_amount digi" data-id="' + att[0] + '" id="product_spacial_amount-' + att[0] + '"></td><td><input type="number" value="'+minimum+'" min="'+minimum+'" class="form-control product_qty number" data-id="' + att[0] + '" id="product_qty-">'+remark+'</td><td id="total_amount_p' + att[0] + '"></td><td><button type="button" class="btn btn-sm btn-danger delete-product" data-id="' + att[0] + '"><i class="fa fa-times-circle"></i></button></td></tr>');
        $('#addproductModal').modal('hide');
    } else if (type === "edit") {
        products[index][key] = value;

    } else if (type === "delete") {
        delete products[index];
    }

    cal_order();
}

function reset_order() {
    total_normal = 0;
    total_sp_discount = 0;
    discount_10k = 0;
    discount_code_amount = 0;
    total_vat = 0;
    total_before_vat = 0;
    total_amount = 0;
    total_discount = 0;
    total = 0;
}

function cal_order() {
    reset_order();
    var tmp_10k = 0, tmp_discount = 0;
    // var tmp_af_total = 0;
    for (var i in products) {
        total_normal += products[i]['qty'] * products[i]['price'];
        $('#total_amount_' + i).html((products[i]['qty'] * products[i]['price']).toFixed(2));
        if (products[i]['sp_price'] > 0) {
            total_sp_discount += (products[i]['price'] - products[i]['sp_price']) * products[i]['qty'];
        }

    }


    //========= discount
    total_amount = total_normal - total_sp_discount;
    if (total_amount >= 100000) {
        tmp_10k = (total_amount / 100) * 5;
    }
    if (discount_code_value > 0) {
        tmp_discount = (total_amount / 100) * discount_code_value
    }
    if (tmp_10k > tmp_discount) {
        discount_10k = tmp_10k;
        discount_code_amount = 0;
        total_discount = tmp_10k;
    } else if (tmp_10k < tmp_discount) {
        discount_10k = 0;
        discount_code_amount = tmp_discount;
        total_discount = tmp_discount;

    } else if (tmp_10k === tmp_discount) {
        discount_10k = 0;
        discount_code_amount = tmp_discount;
        total_discount = tmp_discount;
    } else {
        discount_10k = 0;
        discount_code_amount = 0;
        total_discount = 0;
    }
    //========= discount

    total_before_vat = total_amount - total_discount - custom_discount;
    total_vat = (total_before_vat / 100) * 7;
    total = total_before_vat + total_vat + shipping;
    update_order();
}

function update_order() {

    if (discount_10k > 0) {
        $('#tr10k').show();
    } else {
        $('#tr10k').hide();
    }

    $("#total-normal").html(total_normal.toFixed(2));
    $("#spacial-discount").html(total_sp_discount.toFixed(2));
    $("#coupon").val(discount_code);
    if (discount_code_value > 0) {
        $("#coupon-amount").html('ได้รับส่วนลด : ' + discount_code_value + '%');
    } else {
        $("#coupon-amount").html('');
    }

    $("#coupon-total").html(discount_code_amount.toFixed(2));
    $("#discount-100k").html(discount_10k);
    $("#before-vat").html(total_before_vat.toFixed(2));
    $("#vat").html(total_vat.toFixed(2));
    $("#shipping-amount").val(shipping.toFixed(2));
    $("#custom-discount").val(custom_discount.toFixed(2));
    $("#total-price").html(total.toFixed(2));
}

Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
