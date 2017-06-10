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

        if(parseInt(stock) > 0){
            $('#add-to-cart').html('ADD TO CART').removeAttr('disabled');
        }else{
            $('#add-to-cart').html('OUT OF STOCK').attr('disabled','disabled');
        }
        $('#code').html(code);
        select_attr = true;
    });

    $('#add-to-cart').click(function(){
        if(!select_attr){
            alert('กรุณาเลือกสีของสินค้า');
        }else{

        }
    });

});
