//cart
var products = localStorage.getItem('products');
if (products === null) {
    products = {};
} else {
    products = JSON.parse(products);
}
$(document).ready(function () {
    var select_attr = false;

    $('.select-color').click(function () {
        if($(this).hasClass('disabled')){
            return false;
        }
        $('.select-color').removeClass('active');
        $(this).addClass('active');
        var price = $(this).data('price');
        var spprice = $(this).data('spprice');
        var stock = $(this).data('stock');
        var code = $(this).data('code');
        var aid = $(this).data('aid');
        var value = $(this).data('value');

        $('#product_paid').val(aid);
        $('#product_code').val(code);
        $('#product_value').val(value);
        $('#product_price').val(price);
        $('#product_spprice').val(spprice);

        if (parseInt(spprice) > 0) {
            $('.default-price').html('<s>' + parseInt(price).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</s>');
            $('.special-price').html(parseInt(spprice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
        } else {
            $('.default-price').html(parseInt(price).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            $('.special-price').html('');
        }

        if (parseInt(stock) > 0) {
            $('#add-to-cart').html('ADD TO CART').removeAttr('disabled');
        } else {
            $('#add-to-cart').html('OUT OF STOCK').attr('disabled', 'disabled');
        }
        $('#code').html(code);
        select_attr = true;
    });

    $('#select-size,#select-model').change(function(){
        if($(this).val()==""){
            select_attr = false;
            $('#product_paid').val('');
            $('#product_code').val('');
            $('#product_value').val('');
            $('#product_price').val('');
            $('#product_spprice').val('');
            $('.default-price').html('-');
            $('.special-price').html('');
            $('#code').html('-');
        }else{
            var dt = $(this).val().split('|');
            var price = parseInt(dt[1]);
            var spprice = parseInt(dt[3]);
            var stock = dt[2];
            var code = dt[0];
            var aid = dt[4];
            var value = dt[5];
            $('#product_paid').val(aid);
            $('#product_code').val(code);
            $('#product_value').val(value);
            $('#product_price').val(price);
            $('#product_spprice').val(spprice);

            if (parseInt(spprice) > 0) {
                $('.default-price').html('<s>' + price.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</s>');
                $('.special-price').html(spprice.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
            } else {
                $('.default-price').html(price.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
                $('.special-price').html('');
            }

            if (parseInt(stock) > 0) {
                $('#add-to-cart').html('ADD TO CART').removeAttr('disabled');
            } else {
                $('#add-to-cart').html('OUT OF STOCK').attr('disabled', 'disabled');
            }
            $('#code').html(code);
            select_attr = true;
        }
    });


    //member ============
    var options_register = {
        beforeSubmit: showRequest_register,
        success: showResponse_register
    };
    $('#register-form').ajaxForm(options_register);

    $('#account-type').change(function () {
        if ($(this).val() === 'business') {
            $('.biz').show().find('.input').attr('required', 'required');
        } else {
            $('.biz').hide().find('.input').removeAttr('required');
            ;
        }
    });

    var options_login = {
        beforeSubmit: showRequest_login,
        success: showResponse_login
    };
    $('#login-form').ajaxForm(options_login);


    var options_forgot = {
        beforeSubmit: showRequest_forgot,
        success: showResponse_forgot
    };
    $('#forgot-form').ajaxForm(options_forgot);

    var options_profile = {
        beforeSubmit: showRequest_profile,
        success: showResponse_profile
    };
    $('#profile-form').ajaxForm(options_profile);

    $('#link-forgot-password').click(function () {
        $('#div-forgot').show();
        $('#div-login').hide();
        return false;
    });
    $('#link-login').click(function () {
        $('#div-forgot').hide();
        $('#div-login').show();
        return false;
    });

    cal_simpleorder();
    $('#add-to-cart').click(function () {
        if (!select_attr) {
            swal({
                title: "ผิดพลาด!",
                text: "กรุณาเลือกตัวเลือกสินค้า!",
                type: "warning",
                confirmButtonText: "ตกลง"
            });
        } else {
            var product_image = $('.big-thumb img').attr('src');
            var product_pid = $('#product_pid').val();
            var product_title = $('#product_title').val();
            var product_paid = $('#product_paid').val();
            var product_code = $('#product_code').val();
            var product_value = $('#product_value').val();
            var product_price = parseInt($('#product_price').val());
            var product_spprice = parseInt($('#product_spprice').val());
            var product_qty = parseInt($('#qty').val());
            if (product_qty <= 0) {
                product_qty = 1;
            }
            update_product('add', product_paid, null, [product_paid, product_pid, product_title, product_code, product_value, product_price, product_spprice, product_qty, product_image]);

            $('#order-info .p-thumb').html($('.big-thumb').html());
            $('#order-info .p-title').html(product_title);
            $('#order-info .p-code').html(product_code + " : " + product_value);
            if (parseInt(product_spprice) > 0) {
                $('#order-info .p-price').html('ราคา : <s>' + product_price.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '</s> บาท/ชิ้น');
                $('#order-info .p-spprice').html('ราคาพิเศษ : ' + product_spprice.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + ' บาท/ชิ้น');
            } else {
                $('#order-info .p-price').html('ราคา : ' + product_price.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + ' บาท/ชิ้น');
                $('#order-info .p-spprice').html('');
            }

            cal_simpleorder();
            $('#lightbox-overlay').fadeIn(200);
            $('#lightbox').fadeIn(200);
        }
    });

    $('#close-lightbox').click(function () {
        $('#lightbox-overlay').fadeOut(200);
        $('#lightbox').fadeOut(200);
    });

    $(document).on("keydown", ".digi", function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    $(document).on("change", ".digi", function (e) {
        var num = parseFloat($(this).val());
        $(this).val(num.toFixed(2));
    });

    $(document).on("keydown", ".number", function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode === 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    $(document).on("change", ".number", function (e) {
        var num;
        if ($(this).val().length <= 0) {
            num = 1;
        } else {
            num = parseInt($(this).val());
        }
        if (num <= 0) {
            num = 1;
        }
        $(this).val(num.toFixed(0));
    });
});
function showRequest_register() {
    $('#submit-register').html('Loading...').attr('disabled', 'disabled');
    return true;
}

function showResponse_register(responseText) {
    var obj = jQuery.parseJSON(responseText);
    if (obj.status === 'success') {

        swal({
                title: "Register success",
                text: "สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบเพื่อใช้งาน",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "ตกลง",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    window.location = '/login';
                }
            });
    } else {
        swal({
            title: "Warning!",
            text: obj.message,
            html: true
        });
        grecaptcha.reset();
        $('#submit-register').html('I Accept Term of use and register now.').removeAttr('disabled');
    }

}

function showRequest_login() {
    $('#submit-login').html('Loading...').attr('disabled', 'disabled');
    return true;
}

function showResponse_login(responseText) {
    var obj = jQuery.parseJSON(responseText);
    if (obj.status === 'success') {
        window.location = '/';
    } else {
        swal({
            title: "Error",
            type: "error",
            text: obj.message,
            html: true
        });
        $('#submit-login').html('Login').removeAttr('disabled');
    }

}

function showRequest_forgot() {
    $('#submit-forgot').html('Loading...').attr('disabled', 'disabled');
    return true;
}

function showResponse_forgot(responseText) {
    var obj = jQuery.parseJSON(responseText);
    if (obj.status === 'success') {

        swal({
            title: "Send email success",
            text: "ระบบได้ส่งลิงค์สำหรับเปลี่ยนรหัสผ่านไปยังอีเมล์แล้ว",
            type: "success",
            showCancelButton: false,
            confirmButtonText: "ตกลง",
            closeOnConfirm: true,
            closeOnCancel: false
        });
    } else {
        swal({
            title: "Error",
            type: "error",
            text: obj.message,
            html: true
        });
        $('#submit-forgot').html('Reset password').removeAttr('disabled');
    }

}

function showRequest_profile() {
    $('#submit-profile').html('Loading...').attr('disabled', 'disabled');
    return true;
}

function showResponse_profile(responseText) {
    var obj = jQuery.parseJSON(responseText);
    if (obj.status === 'success') {

        swal({
            title: "Save success",
            text: "บันทึกข้อมูลส่วนตัวสำเร็จ",
            type: "success",
            showCancelButton: false,
            confirmButtonText: "ตกลง",
            closeOnConfirm: true,
            closeOnCancel: false
        });
    } else {
        swal({
            title: "Error",
            type: "error",
            text: obj.message,
            html: true
        });


    }
    $('#submit-profile').html('Save Change').removeAttr('disabled');

}


function update_product(type, index, key, value) {
    products = localStorage.getItem('products');
    if (products === null) {
        products = {};
    } else {
        products = JSON.parse(products);
    }
    if (type === "add") {
        if (products.hasOwnProperty(index)) {
            products[index]["qty"] = products[index]["qty"] + value[7];
        } else {
            products[value[0]] = {
                pid: value[1],
                title: value[2],
                code: value[3],
                value: value[4],
                price: value[5],
                sp_price: value[6],
                qty: value[7],
                image: value[8]
            }
        }
    } else if (type === "edit") {
        products[index][key] = value;

    } else if (type === "delete") {
        delete products[index];
    }
    localStorage.setItem('products', JSON.stringify(products));
}
Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function cal_simpleorder() {

    var total_normal = 0;
    var total_sp_discount = 0;
    var total_amount = 0;
    var total_vat = 0;
    var total = 0;
    var total_qty = 0;

    for (var i in products) {
        var t = 0;
        total_normal += products[i]['qty'] * products[i]['price'];
        if (products[i]['sp_price'] > 0) {
            total_sp_discount += (products[i]['price'] - products[i]['sp_price']) * products[i]['qty'];
            var t = products[i]['sp_price'] * products[i]['qty'];
        } else {
            var t = products[i]['price'] * products[i]['qty'];
        }
        total_qty = total_qty + products[i]['qty'];
        $('#price-' + i).html(t.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    }

    total_amount = total_normal - total_sp_discount;
    total_vat = (total_amount / 100) * 7;
    total = total_amount + total_vat;
    $('#order-info .total-amount span').html(total_amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    $('#order-info .total-vat span').html(total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    $('#total-simple').html(total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + ' บาท');
    if (total_qty > 0) {
        $('.cart-number').html('(' + total_qty + ')');
    } else {
        $('.cart-number').html('');
    }

}
function convertToSlug(Text) {
    return Text.toLowerCase().replace(/ /g, '-').replace(/\s+/g, '').replace(/_/g, '').replace(/[^\w\d-]+/g, '')
}