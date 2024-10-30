'use strict';
var pwc_cart_ordertempid;// YOU HAVE TO KEEP THIS VALUE IN SESSION
var pwc_cart_token;       // YOU HAVE TO KEEP THIS VALUE IN SESSION
var orderitemid;
var cart_st = 0;


(function ($) {
    jQuery(document).ready(function () {

        /*---------------------MY CART LINK ----------------------*/
        var cart_link = '';
        cart_link += '<div class="cn360-section"><a href="javascript:void(0);" class="popup_cart" onclick="cloude_make_mycart();">';
        cart_link += '<i><span class="cart_count">0</span><svg aria-hidden="true" data-prefix="fal" data-icon="shopping-cart" role="img" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-shopping-cart fa-w-18 fa-3x"><path fill="currentColor" d="M551.991 64H129.28l-8.329-44.423C118.822 8.226 108.911 0 97.362 0H12C5.373 0 0 5.373 0 12v8c0 6.627 5.373 12 12 12h78.72l69.927 372.946C150.305 416.314 144 431.42 144 448c0 35.346 28.654 64 64 64s64-28.654 64-64a63.681 63.681 0 0 0-8.583-32h145.167a63.681 63.681 0 0 0-8.583 32c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64 0-17.993-7.435-34.24-19.388-45.868C506.022 391.891 496.76 384 485.328 384H189.28l-12-64h331.381c11.368 0 21.177-7.976 23.496-19.105l43.331-208C578.592 77.991 567.215 64 551.991 64zM240 448c0 17.645-14.355 32-32 32s-32-14.355-32-32 14.355-32 32-32 32 14.355 32 32zm224 32c-17.645 0-32-14.355-32-32s14.355-32 32-32 32 14.355 32 32-14.355 32-32 32zm38.156-192H171.28l-36-192h406.876l-40 192z" class=""></path></svg></i><span class="hidden-xs">Cart</span>';
        cart_link += '</a></div>';
        jQuery('body').append(cart_link);
        /*---------------------MY CART LINK ----------------------*/

        get_cart_tip_count();


        /*-------------------CHECK THE OPTIONS SET FOR THE PRODUCT THAT HAS ATTRBUTES ONLY--------------------------*/
        if (jQuery('.option_display > select').length != 0) {
            var tk = 0;
            jQuery('.option_display > select').each(function (i, e) {
                if (jQuery(e).val() == '0') {
                    tk = 1;
                } else {
                    tk = 0;
                }
            });
            if (tk == 1) {
                jQuery('.add_t_cart').attr('disabled', 'disabled');
                jQuery('.add_t_cart').html('Please Select Options <span class="dashicons dashicons-image-rotate loader_icon" style="display:none;"></span>');
            } else {
                jQuery('.add_t_cart').html('Add to Cart <span class="dashicons dashicons-image-rotate loader_icon" style="display:none;"></span>');
            }
        }

        /*---------------------MY CART ITEM TOTAL----------------------*/
        var localRels = [];
        var price = '';
        var stcr_total = [];
        var qnty = '';

        jQuery('#cart_body_set').each(function () {

            jQuery(this).find('.product-price').each(function () {
                price = jQuery(this).html().substring(1);
                localRels.push(price[1]);
            });

            jQuery(this).find('#addtocart_quantiy').each(function () {
                qnty = jQuery(this).val();
                stcr_total.push(qnty);
            });

            console.log(localRels);
            console.log(stcr_total);

            var qty_total = 0;
            for (var j = 0; j < stcr_total.length; j++)
            {
                if (isNaN(stcr_total[j])) {
                    continue;
                }
                qty_total += Number(stcr_total[j]);
            }
            jQuery('.itemCount').html(qty_total);
            jQuery('.cart_count').html(qty_total);

            var total = 0;
            for (var i = 0; i < localRels.length; i++)
            {
                if (isNaN(localRels[i])) {
                    continue;
                }
                total += Number(localRels[i]);
            }
            console.log('$' + total);
            jQuery('.gdTotal').html(total);
        });
        /*---------------------MY CART ITEM TOTAL----------------------*/

        jQuery(function () {
            jQuery('#viewproduct').on("change", function () {
                var filter = jQuery(this).val();
                if (filter == 'grid') {
                    jQuery('.list').hide();
                    jQuery('.grid').show();
                } else {
                    jQuery('.grid').hide();
                    jQuery('.list').show();
                }
            });
        });

        /*----------------------CONTINUE SHOPING-----------------------*/
        jQuery(".continue").on('click', function () {
            jQuery(".cln_cart_popup").removeClass("in");
        });

        /*-----------CHECK ATTRE OPTIONS SINGLE PRODUCT PAGE------------*/
        jQuery('select').on('change', function () {
            console.log(jQuery(this).val());

            var attr_option = jQuery(this).val();
            if (attr_option == '0') {
                jQuery('.add_t_cart').attr('disabled', 'disabled');
                jQuery('.add_t_cart').html('Add To Cart <span class="dashicons dashicons-image-rotate loader_icon" style="display:none;"></span>');
            } else {
                jQuery('.add_t_cart').attr('disabled', false);
                jQuery('.add_t_cart').html('Add To Cart <span class="dashicons dashicons-image-rotate loader_icon" style="display:none;"></span>');
            }
        });
        /*-----------CONTINUE SHOPPING CART BUTTON CLOSE EVENT------------*/
        jQuery(".cn360_close").click(function () {
            jQuery(".cln_cart_popup").removeClass("in");

        });

    });
})(jQuery);

/*--------------------------------------------------*/
/*---------FUNCTION FOR FRONT END EVENTS -----------*/
/*--------------------------------------------------*/



function showMe(a, b) {
    jQuery('.flex-viewport > ul > li').each(function () {
        jQuery(this).removeClass('active');
    });
    jQuery(".on" + b).addClass('active');
}


/*-----------ADD TO CART BUTTON ONCLICK EVENT------------*/
function add_to_cart(pId) {
    var attributerowid = [];
    var attr_groupname = [];
    var attr_optioname = [];

    jQuery('.option_display > select').each(function (i, e) {
        if (jQuery(e).val() != '0') {
            attributerowid.push(jQuery(e).val());
            attr_groupname.push(jQuery(e).data('groupname'));
            var optionn = jQuery(e).find(':selected').html();
            attr_optioname.push(jQuery.trim(optionn));
        }
    });

    console.log(attributerowid);

    /*-----------ADD TO CART BUTTON ONCLICK LOADER EVENT------------*/
    jQuery('.loader_icon').show();

    /*-----------ADD TO CART BUTTON ONCLICK ASSIGN TOKEN AND TEMP ID EVENT------------*/
    if ((pwc_cart_ordertempid == null || pwc_cart_ordertempid == 'undefined'))
        pwc_cart_ordertempid = null;

    /*-----------ADD TO CART BUTTON ONCLICK ASSIGN TOKEN AND TEMP ID EVENT------------*/
    if ((pwc_cart_token == null || pwc_cart_token == undefined))
        pwc_cart_token = null;


    /*-----------ADD TO CART BUTTON ONCLICK AJAX WITH TOKEN AND TEMP ID EVENT------------*/
    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {"pId": pId,
            "pwc_product_link_id": jQuery('#product_link_id').val(),
            "pwc_order_temp_id": pwc_cart_ordertempid,
            "pwc_order_token": pwc_cart_token,
            "addtocart_quantiy": jQuery('#addtocart_quantiy').val(),
            "attributes": attributerowid,
            "attr_groupname": attr_groupname,
            "attr_optioname": attr_optioname,
            "action": 'cartara_add_to_cartproduct'
        },
        timeout: function (result) {
            console.log('timeout');
        },
        success: function (result) {
            var res = jQuery.parseJSON(result);
            if (res.response == "added") {

                pwc_cart_ordertempid = res.ordertempid;
                pwc_cart_token = res.token;

                var cloud_user_ip = res.user_ip;

                /*-----------SEND USER IP TO CREATE CART------------*/
                cloude_make_mycart();
                console.log('pwc_cart_ordertempid:' + res.ordertempid);
                console.log('pwc_cart_token:' + res.token);
            }
        }
    });
}

/*-----------GET USER IP FOR CART DISPLAY AND ONTHER DESIGNES------------*/
function get_usre_ip() {

    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'action': 'cartara_sl_getfront_ip'},
        timeout: function () {
            console.log('timeout');
        },
        success: function (result) {
            console.log(result);
            var cloud_user_ip = result;
            cloude_make_mycart();
        }

    });

}

/*-----------GET USER IP AND CREATE HIS CART DISPLAY ------------*/
function cloude_make_mycart() {
    //var u_ip = cloud_user_ip;
    var html = '';
    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'action': 'cartara_incart_data_byip'},
        timeout: function () {
            console.log('timeout');
        },
        success: function (result) {
            if (result != '500')
            {
                var cart = jQuery.parseJSON(result);

                var Grand_total = 0;
                jQuery.each(cart, function (index, value) {
                    var ht = jQuery.parseJSON(value.product_data);

                    var a = [];
                    a = jQuery.parseJSON(ht.attr_groupname);
                    var b = [];
                    b = jQuery.parseJSON(ht.attr_optioname);

                    var pricecart = ht.product_price.split(' ');

                    Grand_total += Number(pricecart[1]);

                    var s_qty = (parseInt(pricecart[1]) / (parseInt(ht.product_qty)));

                    html += '<li class="main-div-product containet-fluid item_' + value.id + '" >'
                    html += ' <div class="cn360_clearfix">'
                    html += '            <div class="cn360_col-md-2 text-center">'

                    if (ht.product_image_url != 'NULL') {
                        html += '<img src="' + ht.product_image_url + '" class="cn360_img-responsive">'
                    } else {
                        html += '<div class="cn360-noimage"><p>No Image</p></div>'
                    }
                    html += '      </div>'
                    html += '    <div class="cn360_col-md-5">'
                    html += ' <div class="products-info">'
                    html += '<h2>' + ht.product_name + '</h2>'

                    jQuery.each(a, function (index, value) {
                        html += '<h6>' + a[index] + ' : ' + b[index] + '</h6>'
                    });

                    html += '</div>'
                    html += '<div class="product-btn">'
                    html += '<a href="javascript:void(0);" class="s_btn btn_r" onclick="deleteMe(' + value.id + ',' + ht.orderitemid + ',' + ht.ordertempid + ');">Remove</a>|'
                    html += '<a href="javascript:void(0);" class="s_btn btn_up" onclick="updateMe(' + value.id + ',' + ht.orderitemid + ');" >Update</a>'
                    html += '<input type="text" value="' + ht.product_site_url + '" id="single_update_url_' + value.id + '" hidden>'
                    html += '</div>'
                    html += '</div>'
                    html += '<div class="cn360_col-md-5 pop_right_head bottom_desc">'
                    html += '<div class="value-button minus_btn" id="decrease" onclick="decreaseValue()" value="Decrease Value" style="display:none;">-</div>'
                    html += '<input type="text" disabled="disabled" class="number addtocart_quantiy"  value="' + ht.product_qty + '" />'
                    html += '<div class="value-button" id="increase" onclick="increaseValue(this)" value="Increase Value" style="display:none;">+</div>'
                    html += '<h3>' + pricecart[0] + '' + s_qty + '</h3>'

                    html += '<h3 class="product-price">' + pricecart[0] + pricecart[1] + '</h3>'
                    html += '<h3><span class="close_list_product" onclick="deleteMe(' + value.id + ',' + ht.orderitemid + ',' + ht.ordertempid + ');">&times</span></h3>'
                    html += '</div>'
                    html += '</div>'
                    html += '</li>';

                });


                jQuery('.gdTotal').html(Grand_total);

                /*-----------APPEND CREATE CART ITEMS ------------*/
                jQuery('#cart_body_set').html(html);

                /*-----------DISPLAY CART ------------*/
                jQuery(".cln_cart_popup").toggleClass("in");
                /*-----------HIDE LOADER ON ADD TO CART BUTTON ------------*/
                jQuery('.loader_icon').css('display', 'none');
                /*-----------APPEND CREATE CART ITEMS TOTAL AND PRICE TOTAL ------------*/
                set_cart_pprt();
            } else {
                /*-----------APPEND EMPTY CART DATA ------------*/
                jQuery('#cart_body_set').html('<h4>Cart Is Empty.</h4>');
                /*-----------DISPLAY CART ------------*/
                jQuery(".cln_cart_popup").toggleClass("in");

                jQuery('.cart_count').html('0');
                jQuery('.ccTotal').html('0');
                jQuery('.itemCount').html('0');
                jQuery('.gdTotal').html('0');

            }
        }
    });

}


/*-----------DELETE CART ITEM ------------*/
function deleteMe(id, orderitemid, status, token) {

    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'action': 'cartara_incart_item_remove', 'item_id': id, 'orderitemid': orderitemid},
        timeout: function () {
            console.log('timeout');
        },
        success: function (rem_se) {
            if (rem_se == 1) {
                jQuery('.item_' + id).remove();
                var ls = jQuery('#cart_body_set > li').length;
                console.log(ls);

                set_cart_pprt();

                if (ls == 0) {
                    jQuery('#cart_body_set').html('<h4>Cart Is Empty.</h4>');

                    pwc_cart_ordertempid = null;  // YOU HAVE TO KEEP THIS VALUE IN SESSION
                    pwc_cart_token = null; //
                    cart_st = 1;
                    jQuery('.cart_count').html('0');
                    jQuery('.ccTotal').html('0');
                    jQuery('.itemCount').html('0');

                }
                console.log(rem_se);
            }
        }
    });
}

/*-----------UPDATE CART ITEM ------------*/
function updateMe(id, orderitemid) {

    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'action': 'cartara_incart_item_remove', 'item_id': id, 'orderitemid': orderitemid},
        timeout: function () {
            console.log('timeout');
        },
        success: function (rem_se) {
            console.log(rem_se);
            var rediredturl = jQuery('#single_update_url_' + id).val();
            console.log(rediredturl);
            if (rediredturl != '' && rediredturl !== 'undefined') {
                window.location.href = rediredturl;
            }

        }
    });
}

/*-----------GET CART TIP COUNT AND GET DATA FROM BACKEND BY COOKIES ------------*/
function get_cart_tip_count() {
    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'action': 'cartara_get_cart_tip_count'},
        timeout: function () {
            console.log('timeout');
        },
        success: function (rem_se) {
            var data = jQuery.parseJSON(rem_se);

            jQuery('.cart_count').html(data.cart_total);
            jQuery('.itemCount').html(data.cart_total);

            pwc_cart_ordertempid = data.ordertempid;
            pwc_cart_token = data.token;

            console.log('====SETUP THE COOKIE FOR FRONT AFTER REFRESH ====');
            console.log('====pwc_cart_ordertempid ====>' + pwc_cart_ordertempid);
            console.log('====pwc_cart_token ====>' + pwc_cart_token);
            console.log('====SETUP THE COOKIE FOR FRONT AFTER REFRESH ====');

        }
    });
}

/*-----------CART INCREASE AND DECREASE------------*/
function increaseValue($this) {
    console.log($this);

    var value = jQuery('.number').val();
    value = isNaN(value) ? 0 : value;
    value++;

    jQuery($this).prev().val(value);
}

function decreaseValue($this) {
    console.log($this);
    var value = jQuery('.number').val();
    value = isNaN(value) ? 0 : value;
    value < 1 ? value = 1 : '';
    value--;

    jQuery($this).next().val(value);
}

/*-----------CART INCREASE AND DECREASE END------------*/

/*-----------CART PRICE TOTAL DECREASE------------*/
function set_cart_pprt() {

    var localRels = [];
    var price = '';
    var stcr_total = [];
    var qnty = '';

    jQuery('#cart_body_set').each(function () {
        jQuery(this).find('.product-price').each(function () {
            price = jQuery(this).html().substring(1);
            localRels.push(price);
        });


        jQuery(this).find('.addtocart_quantiy').each(function () {
            qnty = jQuery(this).val();
            stcr_total.push(qnty);
        });

        console.log(localRels);
        console.log(stcr_total);


        var qty_total = 0;
        for (var j = 0; j < stcr_total.length; j++)
        {
            if (isNaN(stcr_total[j])) {
                continue;
            }
            qty_total += Number(stcr_total[j]);
        }
        jQuery('.itemCount').html(qty_total);
        jQuery('.cart_count').html(qty_total);


        var total = 0;
        for (var i = 0; i < localRels.length; i++)
        {
            if (isNaN(localRels[i])) {
                continue;
            }
            total += Number(localRels[i]);
        }
        console.log('$' + total);
        jQuery('.gdTotal').html(total);


    });
}

/*-----------CART PRICE TOTAL DECREASE------------*/
function check_cloud_option() {
    jQuery('.option_input').on('change', function () {
        var tok = 0;
        if (jQuery('.option_display > select').length != 0) {
            jQuery('.option_display > select').each(function (i, e) {
                if (jQuery(e).val() == '0') {

                    jQuery('.add_t_cart').attr('disabled', 'disabled');
                    jQuery('.add_t_cart').attr('value', 'Please Select Options');
                    tok = 1;
                }
            });
            if (tok == 0) {
                jQuery('.add_t_cart').html('Add To Cart <span class="dashicons dashicons-image-rotate loader_icon" style="display:none;"></span>');
            }
        }

    });
}

function send_ckout(p_cart_ordtempid, pcarttoken) {

    if (cart_st == 0) {
        var pco_id = p_cart_ordtempid;
        var pc_token = pcarttoken;

        if ((pco_id == null || pco_id == '' || typeof pco_id == 'undefined' || pc_token == null || pc_token == '' || typeof pc_token == 'undefined'))
        {
            pco_id = pwc_cart_ordertempid;
            pc_token = pwc_cart_token;

        }

        if ((pco_id !== null && typeof pco_id !== 'undefined' && pc_token != '' && typeof pc_token !== 'undefined'))
        {
            jQuery.ajax({
                type: 'POST',
                url: ajax.ajax_url,
                data: {'action': 'cartara_procedtochekout'},
                timeout: function () {
                    console.log('timeout');
                },
                success: function (rem_se) {
                    var lc = jQuery('#cart_body_set > li').length;
                    if (lc > 0)
                        window.location = 'https://www.secureinfossl.com/myCheckout/' + pco_id + '/' + pc_token + '.html';
                }
            });
        } else {
            return false;
        }
    }
}