$(document).ready(function () {

    var cart_total = $('#header_cart_subtotal_amount').val();
    
    $('#header_cart_total').html(cart_total);
    
    $('.cart_qty').bind('change', function(){
        
        var itemQty = $(this).val();
          
        var itemKey = $(this).data('item_key');
        var itemPrice = $(this).data('item_price');
        
        var subtotal = parseFloat(itemPrice) * parseInt(itemQty);
        $('.item_subtotal_'+itemKey).html(subtotal);
        $('#item_subtotal_'+itemKey).val(subtotal);
        
        var cartTotal = 0;
        $(".item_subtotal").each(function() {
            cartTotal = (parseFloat(cartTotal) + parseFloat($(this).val()));
        });
        
        cartTotal = formatNumber(cartTotal,2);
        
        $('.cart_subtotal').html(cartTotal);
        $('.cart_total').html(cartTotal);
        $('#header_cart_total').html(cartTotal);
       
        update_cart(itemKey , itemQty);
        
    });
    
    
    $('.addtowishlist').click(function(){
        
        var key = $(this).attr('product_hash');         
        var pid = key ? '_'+ key : '';
        var callurl = $('#base_url').val();
        var variant_id = $('#product_variants'+pid).val();
        var product_id = $('#product_id'+pid).val();
        
        var postData = 'action=add_to_wishlist';
            postData = postData + '&variant_id=' + variant_id;
            postData = postData + '&product_id=' + product_id;

        $.ajax({
            type: "POST",
            url: callurl + "webshop/webshop_request",
            data: postData,
            beforeSend: function () {
                //$("#top-cart-wishlist-count").html("<div class='overlay'><i class='fa fa-refresh fa-spin'></i> Adding In Wishlist</div>");
            },
            success: function (data) {

               var objData = JSON.parse(data);
                if(objData.status == "SUCCESS") {
                  var wishlist_count = objData.count;
                    $("#top-cart-wishlist-count").html((parseInt(wishlist_count)));                     
                    $("#success_alert_message").html('<i class="fa fa-check"></i> Item added to wishlist.');
                    $("#success_alert").addClass('show');
                    setTimeout(function(){ $("#success_alert").removeClass('show'); }, 3000);
                } else {
                    $("#error_alert_message").html('<i class="fa fa-time"></i> '+objData.error);
                    $("#error_alert").addClass('show');
                    setTimeout(function(){ $("#error_alert").removeClass('show'); }, 3000);
                }
            }
        });
        
    });
    
    $('.remove_from_wishlist').click(function(){
        
        var key = $(this).attr('product_hash');         
        var pid = key ? '_'+ key : '';
        var callurl = $('#base_url').val();
        var variant_id = $('#variant_id'+pid).val();
        var product_id = $('#product_id'+pid).val();
        alert(key);
        var postData = 'action=remove_from_wishlist';
            postData = postData + '&variant_id=' + variant_id;
            postData = postData + '&product_id=' + product_id;
            
            $.ajax({
            type: "POST",
            url: callurl + "webshop/webshop_request",
            data: postData,
            beforeSend: function () {
                //$("#top-cart-wishlist-count").html("<div class='overlay'><i class='fa fa-refresh fa-spin'></i> Adding In Wishlist</div>");
            },
            success: function (data) {

               var objData = JSON.parse(data);
                if(objData.status == "SUCCESS") {
                  var wishlist_count = objData.count;
                    $("#top-cart-wishlist-count").html((parseInt(wishlist_count)));                     
                    $("#success_alert_message").html('<i class="fa fa-check"></i> Item remove from wishlist.');
                    $("#success_alert").addClass('show');
                    $("#row"+pid).hide();
                    setTimeout(function(){ $("#success_alert").removeClass('show'); }, 3000);
                } else {
                    $("#error_alert_message").html('<i class="fa fa-time"></i> '+objData.error);
                    $("#error_alert").addClass('show');
                    setTimeout(function(){ $("#error_alert").removeClass('show'); }, 3000);
                }
            }
        });
        
    });
    
    
});

function remove_cart_item(key, source){
     
    var callurl = $('#base_url').val();
    var postData = 'action=remove_cart_item';
        postData = postData + '&cart_item_key=' + key;
        postData = postData + '&action_source=' + source;   //[header_cart or cart_page]
        
    $.ajax({
        type: "POST",
        url: callurl + "webshop/webshop_request",
        data: postData,
        beforeSend: function () {
            $("#header_cart_content").html("<div class='overlay'><i class='fa fa-refresh fa-spin'></i> Loading Cart Items</div>");
        },
        success: function (data) {
            
            if(source == 'header_cart'){                
                $("#header_cart_content").html(data);
            }
            
            if(source == 'cart_page'){                
                $(".cart_page_content").html(data);
                load_header_cart();
            }
            
            setTimeout(function(){ 
                var cartCount = parseInt($('#header_cart_item_count').val());
                var cartTotal = $('#header_cart_subtotal_amount').val();
                cartCount = cartCount ? cartCount : '0'; 
                cartTotal = cartTotal ? cartTotal : '0.00'; 
                $("#header_cart_count").html(cartCount);
                $("#header_cart_total").html(cartTotal);
            }, 500);
            
            $("#success_alert_message").html('<i class="fa fa-check"></i> Item Removed successfully.');
            $("#success_alert").addClass('show');
            setTimeout(function(){ $("#success_alert").removeClass('show'); }, 3000);
        }
    });
}

function update_cart(itemKey , itemQty){
   
    var callurl = $('#base_url').val();
     
    var postData = 'action=update_cart';
        postData = postData + '&itemKey=' + itemKey;
        postData = postData + '&itemQty=' + itemQty;
     
    $.ajax({
        type: "POST",
        url: callurl + "webshop/webshop_request",
        data: postData,
        beforeSend: function () {
            $("#header_cart_content").html("<div class='overlay'><i class='fa fa-refresh fa-spin'></i> Loading Cart Items</div>");
        },
        success: function (data) {
         
            if(data=='SUCCESS') {
                $("#success_alert_message").html('<i class="fa fa-check"></i> Cart updated successfully.');
                $("#success_alert").addClass('show');
                setTimeout(function(){ $("#success_alert").removeClass('show'); }, 3000);
            }
        }
    });
}

function update_price_by_variants(key){
    
    var pid = key ? '_' + key : '';
    
    var promotion_price = parseFloat($('#promotion_price'+pid).val());
    var overselling = webshop_settings_overselling; //JS Globle Variable Have Defined In Footer File.
     
    if(promotion_price) {
        return false;
    } else {
        var variant_id              = $('#product_variants'+pid).val();
        var variant_name            = $('#product_variants'+pid+' option:selected').attr("title");
        var variant_price           = parseFloat($('#product_variants'+pid+' option:selected').attr("price"));
        var variant_unit_quantity   = parseFloat($('#product_variants'+pid+' option:selected').attr("unit_quantity"));
        var variant_quantity        = parseFloat($('#product_variants'+pid+' option:selected').attr("quantity"));
        var unit_price              = '';
        
        variant_price = (parseFloat(variant_price)) ? variant_price : 0;

        var product_price = $('#price'+pid).val();
        var tax_rate    = $('#tax_rate'+pid).val();
        var tax_method  = $('#tax_method'+pid).val();

        unit_price = (parseFloat(product_price) + parseFloat(va