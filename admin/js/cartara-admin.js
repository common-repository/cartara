
jQuery(document).ready(function () {
    /*------------Tooltip Hover------*/
    var sr = [];
    jQuery('#sortable > li').each(function () {
        var a = jQuery(this).data('id');
        sr.push(a);
        jQuery('#shotred_series').val(sr);
        console.log(sr);
    });


    var sr_v = [];
    jQuery('#video_short > li').each(function () {
        var a = jQuery(this).data('src');
        sr_v.push(a);
        jQuery('#video_short_series').val(sr_v);
        console.log(sr_v);
    });

//***************save api in api_setting page ************/
    jQuery('#save_cartara_api').on('click', function () {
        var merchant_id = jQuery('#merchant_id').val();
        var api_signature = jQuery('#api_signature').val();
        jQuery.ajax({
            type: 'POST',
            url: ajax.ajax_url,
            data: {'merchant_id': merchant_id, 'api_signature': api_signature, 'action': 'cartara_api_add_option'},
            success: function (response) {
//                alert(response);
                console.log(response);
            }
        });
    });
//***************save api in api_setting page ************/
//***************show dashboard page ************/

    /*------------------------*/

    jQuery(document).on('click', '.search_btn', function (e) {
        console.log('search_btn');
        var $search_box = jQuery(this).find('.search_box');
        if (e.target === $search_box[0]) {
            return false;
        }
        jQuery(this).toggleClass('active');
        $search_box.focus();

    });

    jQuery(document).on('keyup', '.search_box', function () {
        var search_term =jQuery(this).val();

        var re = new RegExp(search_term, 'gi');

        jQuery('.cn_list > li').each(function () {

            var name = jQuery(this).attr('data-name');

            if (name.match(re) === null) {
                jQuery(this).css('display', 'none');
            } else {
                jQuery(this).css('display', 'block');
                jQuery('.search_empty_msg').remove();
            }
        });

        if (search_term) {
            jQuery(this).parent().addClass('filtered');
        } else {
            jQuery(this).parent().removeClass('filtered');
        }

        var visible = jQuery('.cn_list > li:visible').length;

        var $no_scs_msg = jQuery('.cn_list').find('p');

        if (visible == 0) {
            jQuery('.cn_list_m').html('<p align="center" class="search_empty_msg"><i>No form name match search found !</i></p>');
        } else {
            jQuery('.search_empty_msg').remove();
        }

    });


    jQuery("#api_synchronization").on("click", function () {
        confirm_old_data_delete();
    });

    jQuery('.link-sort-list').click(function (e) {
        var jQuerysort = this;
        var jQuerylist = jQuery('.cn_list');
        var jQuerylistLi = jQuery('li', jQuerylist);
        jQuerylistLi.sort(function (a, b) {
            var keyA = jQuery(a).text();
            var keyB = jQuery(b).text();
            if (jQuery(jQuerysort).hasClass('asc')) {
                return (keyA > keyB) ? 1 : 0;
            } else {
                return (keyA < keyB) ? 1 : 0;
            }
        });
        jQuery.each(jQuerylistLi, function (index, row) {
            jQuerylist.append(row);
        });
        e.preventDefault();
    });

    /*-----------------------copy form-------*/

    jQuery(document).on('click', '.cartara_copy', function (e) {
        e.preventDefault();
        var btn = jQuery(this);
        var box = btn.closest('li').find('.cartara_copy_box');
        jQuery('.cartara_copy_box').not(box).hide();
        box.fadeToggle();
        box.select();
        copyToClipboard(box);
    });
    
    

});


function cartara_delete_form_code(id) {
    var id = id;

    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'id': id, 'action': 'cartara_delete_shortcode'},
        success: function (response) {
            if (response == 'Deleted successfully') {


                var visible = jQuery('.cn_list > li:visible').length;
                if (visible > 0) {
                    jQuery("#cn_list").load(location.href + " #cn_list>*", "");
                }
                jQuery("#content").load(location.href + " #content>*", "");

                console.log(response);
            }

        }
    });
}

function cartara_survey_delete_form_code(id) {
    var id = id;
    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: { 'action': 'cartara_survey_delete_shortcode','id': id},
        success: function (response) {
            if (response == 'Deleted successfully') {
                alert(response);
                var visible = jQuery('.cn_slist > li:visible').length;
                if (visible > 0) {
                    jQuery("#cn_list").load(location.href + " #cn_list>*", "");
                }
                jQuery("#content").load(location.href + " #content>*", "");

                console.log(response);
            }

        }
    });
}
//***************sync api products ************/

function progressbar() {
    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'action': 'cartara_get_data_from_api'},
        xhr: function () {
            var xhr = jQuery.ajaxSettings.xhr();
            xhr.upload.onprogress = function (e) {
                var per = Math.floor(e.loaded / e.total * 100) + '%';
                jQuery("#myBar").css("width", per);
                jQuery("#myBar").html(per);
                console.log(Math.floor(e.loaded / e.total * 100) + '%');
            };
            return xhr;
        },
        success: function (response) {

            jQuery('#load_session').hide();
            jQuery('.response_section').append('<p class="endpoint">Synching Completed.</p>');

            console.log(response);
        }
    });
}

//***************sync api categroy************/  

function confirm_old_data_delete() {
    swal({
        title: 'Are you sure you want to “Synchronize Products”?',
        text: "This will remove some information of existing products.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
            .then(function (isConfirm) {
                if (isConfirm) {
                    jQuery('.endpoint').hide();
                    jQuery('#load_session').show();

                    jQuery.ajax({
                        type: 'POST',
                        url: ajax.ajax_url,
                        data: {'action': 'cartara_delete_data'},
                        timeout: function (response) {
                            swal({
                                title: 'Session Timeout.',
                                text: "Synching failed!",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            });
                        },
                        success: function (response) {
                            var res = jQuery.parseJSON(response);
                            if (res.del_res == 1) {
                                swal({
                                    title: 'Step 1 completed.',
                                    text: "Some information your previously synced products are deleted , Press Ok to processed for step 2.",
                                    icon: "success",
                                    buttons: true,
                                    dangerMode: true,
                                }).then(function (isConfirm) {
                                    if (isConfirm) {
                                        progressbar();
                                    }
                                });
                            }
                        }
                    });
                } else {
                    swal("Your imaginary file is safe!");
                }
            });
}




/******************add image start***************************/
function arrangeSno()
{
    var i = 1;
    jQuery('.s_border').each(function () {
        $(this).find('h4').html(i);
        i++;
    });
}
function delete_field(e) {

    jQuery(e).parent('div').remove(); //Remove field html
    arrangeSno();

}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {


            var image = new Image();

            //Set the Base64 string return from FileReader as source.
            image.src = e.target.result;

            //Validate the File Height and Width.
            image.onload = function () {
                var height = this.height;
                var width = this.width;
                if ((height === 400 && width === 400) || (height === 250 && width === 400))
                {
                    jQuery(input).prev('.upload').attr('src', e.target.result);
                    return true;
                } else {
                    console.log("Height and Width must be 400px.");
                    delete_field(input);

                    swal({
                        title: 'Required Dimensions',
                        text: '400px (Width) X 400px (Height)',
                        icon: "warning",
                    })
                }
            };
        };
        reader.readAsDataURL(input.files[0]);
    }
}


function add_more_fields(e) {

    var maxField = 20; //Input fields increment limitation
    var addimage = $('.add_image'); //Add button selector
    var wrapper = $('.media_field'); //Input field wrapper
 
      var fieldHTML = '<div class="s_border"><h4></h4><img name="post_image[]" class="upload upload_img" src="../wp-content/plugins/cartara/admin/images/images400.png"  alt="your image" />\n\
      <input type="button" class="add_image btn btn-primary" value="Upload Image" id="uploadimage" onclick="openMediaPopup(this)"/><input type="hidden" name="productImage[]" id="image" value="" class="product-images"/><button type="button"  class="btn btn-danger delete_image"  onclick="delete_field(this);"><span class="dashicons dashicons-no"></span></button> </div>'; //New input field html 
    var x = 1; //Initial field counter is 1

    if (x < maxField) {

        x++; //Increment field counter
        jQuery(wrapper).append(fieldHTML); //Add field html
    }
    arrangeSno();

}
function deleteImage(e, post_id, id) {

    jQuery(e).parent('li').remove();
    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {'post_id': post_id, 'id': id, 'action': 'cartara_delete_attachment'},
        success: function (response) {
            if (response == '1') {
                swal({
                    title: 'Image Deleted.',
                    icon: "success",
                });
                var srr = [];
                jQuery('#sortable > li').each(function () {
                    var a = jQuery(this).data('id');
                    srr.push(a);
                    jQuery('#shotred_series').val(srr);
                    console.log('srr:' + srr);
                });
            }

            if (response == '0') {
                swal({
                    title: 'Image Deleted.',
                    icon: "success",
                });
                jQuery('#shotred_series').val('');
            }
            console.log(response);
        }
    });



}

function openMediaPopup(input) {
	
	tb_show('cartara', 'media-upload.php?type=image&TB_iframe=true');

				window.send_to_editor = function(html)
				{
					//alert( html );
					//imgurl = jQuery( 'img', html ).attr( 'src' );
					var myRegex = /<img[^>]+src="((http|https):\/\/[^">]+)"/g;
					var imgurl = myRegex.exec(html); 
					//alert(imgurl);
					//$( '#sidebarimage' ).val(imgurl);
					jQuery(input).prev('.upload').attr('src', imgurl[1]);
					
					var myClassRegex = /<img[^>]+class="([^">]+)"/g;
					var imgclass = myClassRegex.exec(html);
					//alert(imgclass[1]); 
            		var imgid    = parseInt(imgclass[1].replace(/\D/g, ''), 10);
            		//alert(imgid);
					jQuery(input).next('.product-images').val(imgid);
					tb_remove();
				}

				return false;
}
//******************add image end***************************/

//******************add video start***************************/
function arrange_S_no()
{
    var i = 1;
    jQuery('.ss_border').each(function () {
        jQuery(this).find('h4').html(i);
        i++;
    });
}
function delete_video(e) {
    jQuery(e).parent('div').remove(); //Remove field html
    arrange_S_no();
}

var S_NO = 1;
function add_more_videos(e) {

    var maxField = 20; //Input fields increment limitation
    var addvideo = $('.add_videos'); //Add button selector
    var wrapper = $('.video_field'); //Input field wrapper
    var fieldHTML = '<div class="ss_border"><h4></h4><input type="text" name="cartara_videos[]" class="browse_file" id="video-url_' + S_NO + '" /><input id="upload_button" name="upload_button" type="button" class="add_image btn btn-primary" value="Upload Video" onclick="openMediaPopupVideo(this)"><button type="button" class="btn btn-danger delete_image" onclick="delete_video(this);"><span class="dashicons dashicons-no"></span></button>'; //New input field html 
    var x = 1; //Initial field counter is 1

    //Check maximum number of input fields
    if (x < maxField) {

        x++; //Increment field counter
        jQuery(wrapper).append(fieldHTML); //Add field html
    }
    arrange_S_no();
    S_NO++;

}

function openMediaPopupVideo(input) {
	
	tb_show('cartara', 'media-upload.php?type=video&TB_iframe=true');

				window.send_to_editor = function(html)
				{
                	
					jQuery(input).prev('.browse_file').val(html);
					tb_remove();
				}

				return false;
}




function open_media_uploader(a, b) {
    var mediaUploader;

    if (mediaUploader) {
        mediaUploader.open();
        return;
    }
    // Extend the wp.media object
    mediaUploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Video',
        button: {
            text: 'Choose Video'
        }, multiple: false});

    // When a file is selected, grab the URL and set it as the text field's value
    mediaUploader.on('select', function () {
        var attachment = mediaUploader.state().get('selection').first().toJSON();
        if ('video' === attachment.type) {
            jQuery('#video-url_' + b).val(attachment.id);
        } else
        {
            swal({
                title: 'Please Choose Video with supported format',
                text: 'Only .mp4,.ogg type videos allowed. Size should not be excced more then 2 MB.',
                icon: "warning",
            });

        }
    });
    // Open the uploader dialog
    mediaUploader.open();

}

function deleteVideo(e, post_id) {
    jQuery(e).parent('li').remove();

    var url = jQuery(e).data('src');

    jQuery.ajax({
        type: 'POST',
        url: ajax.ajax_url,
        data: {post_id: post_id, url: url, 'action': 'cartara_delete_video_attachment'},
        success: function (response) {
            if (response == '1')
            {
                swal({
                    title: 'Video Deleted.',
                    icon: "success",
                });

                var sr_check = [];
                jQuery('#video_short > li').each(function () {
                    var a = jQuery(this).data('src');
                    sr_check.push(a);
                    jQuery('#video_short_series').val(sr_check);
                    console.log(sr_check);
                });
            }

            if (response == '0') {
                swal({
                    title: 'Video Deleted.',
                    icon: "success",
                })
                jQuery('#video_short_series').val('');
            }

        }
    });
}

function copyToClipboard(element) {
    var $temp = jQuery("<input>");
    jQuery("body").append($temp);
    $temp.val(jQuery(element).val());
    document.execCommand("copy");
    $temp.remove();
}

function copyFunction(element) {
  var el = document.getElementById(element);
		var range = document.createRange();
		range.selectNodeContents(el);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
		document.execCommand('copy');
		alert("Contents copied to clipboard.");
}

function doSaveOrder() {
	var x = document.getElementById("bulk-action-selector-top");
  	var option = document.createElement("option");
  	option.text = "cn_save_order";
  	x.add(option);
	document.getElementById("posts-filter").method = "post"; 
	document.getElementById("bulk-action-selector-top").value = 'cn_save_order';
	document.getElementById("posts-filter").action = "admin-post.php";
	document.getElementById("posts-filter").submit();
}





