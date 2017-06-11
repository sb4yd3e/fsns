$(document).ready(function () {
    var select_attr = false;

    $('.select-color').click(function () {
        $('.select-color').removeClass('active');
        $(this).addClass('active');

        var price = $(this).data('price');
        var spprice = $(this).data('spprice');
        var stock = $(this).data('stock');
        var code = $(this).data('code');

        if (parseInt(spprice) > 0) {
            $('.default-price').html('<s>' + price + '</s>');
            $('.special-price').html(spprice);
        } else {
            $('.default-price').html(price);
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

    $('#add-to-cart').click(function () {
        if (!select_attr) {
            swal({
                title: "ผิดพลาด!",
                text: "กรุณาเลือกสีของสินค้า!",
                type: "warning",
                confirmButtonText: "ตกลง"
            });
        } else {

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
        grecaptcha.reset($('#re-form-login').attr('data-widget-id'));
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
        grecaptcha.reset($('#re-form-reset').attr('data-widget-id'));
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