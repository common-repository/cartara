<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 *
 * @package    cartara
 * @subpackage cartara/admin/partials
 */
?>
<style>
    .welcome-panel { 
        overflow: visible;
        margin-top: 3px;
    }

    .upload_section .welcome-panel {
        padding: 15px; 
    }
    .upload_section h4 {
        display: inline-block;
        font-size: 20px;  
        font-weight: 600; 
        margin: 0;
        padding-top: 4px;
        margin-right: 10px;
    }
    .upload_section .browse_file{
        padding: 0 !important;
        margin: 0;
        display: inline-block;
        padding-left: 5px !important;
        border: 1px solid #ccc;
        background-color: white;
    }
    .upload_img{
        width: 100px;
        height: 100px;
        border: 1px solid #ccc;
        padding: 3px;
        margin-right: 10px;
    }
    .delete_image{
        padding: 5px;
        border-radius: 0;
        margin-left: 5px;
    }
    .s_border{
        border-bottom: 1px solid #e8e8e8;
        padding-bottom: 10px;
        margin-bottom: 10px;

    }
    .image_list{
        width: 100%;
        margin-top: 20px;
        position: relative;
        display: flex;
        flex-wrap: wrap;
    }
    .image_list li{
        width: 85px;
        display: inline-block;
        height: 85px;
        border: 1px solid #ccc;
        margin-right: 20px;
        margin-bottom: 40px;
        position: relative;
    }
    .image_list li img{
        width: 100%;
        height: 100%;
        padding: 2px;
    }
    .image_list .close_btn{
        text-decoration: none;
        position: absolute;
        top: -15px;
        color: #ca4a1f;
        right: -13px;
        border-radius: 50%;
        line-height: 0;
        background: #fff;
        border: 2px solid #ca4a1f;
    }
    .image_list .close_btn span {
        padding: 0;
        font-size: 18px;
        line-height: 1.2;
    }
    .primary_img{
        position: absolute;
        top: 88px;
        font-size: 11px;
        color: #000000;
    }
    .primary_img i.fa.fa-question {
        border: 1px solid;
        padding: 2px 3px;
        border-radius: 0;
        margin-top: 2px;
        position: relative;
    }  
    .tooltip_btn:hover ~ .tooltip_text {
        display: block !important;
        width: 450px;
        position: absolute;
        z-index: 9;
        color: #fff;
        background: #464545;
        padding: 5px;
        border-radius: 5px;
        margin-top: 10px;
    }
    .tooltip_text:after {
        content: "";
        position: absolute;
        left: 10px;
        top: -10px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 10px 10px 10px;
        border-color: transparent transparent #464545 transparent;
        z-index: 9;
    }
    .wrap.col-sm-12.upload_section {
        text-align: center;
    }
</style>
<section class="">
    <div class="container-fluid">
        <div class="wrap col-sm-12 upload_section">
            <strong class="text">* Required product image dimensions for this template are 400 pixels x 400 pixels (Width x Height).<br/>If your images are not 400 x 400, then please resize them with this free online tool- <a href="#">Click Here</a></strong>
            <div class="welcome-panel">
                <div class="welcome-panel-content">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="text-box-row">
                                <div class="field_wrapper media_field ">

                                </div>  
                                <div class="text-center">
                                    <input type="button" name="add_image" class="add_image btn btn-primary" value="Add Images" onclick="add_more_fields();" />
                                </div>
                            </div>
                            <input type="text" name="shotred_series" value="" id="shotred_series" hidden>
                            <input type="text" name="shotred_series_check" value="" id="shotred_series_check" hidden>
                        </div>
                    </div>
                </div>
                <div>
                    <?php
                    $gallery_img = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);
                    if (!empty($gallery_img)) {
                        ?>
                        <ul class="image_list" id="sortable" data-imgsrt="<?php echo $post_id; ?>">
                            <?php
                            $i = 0;
                            foreach ($gallery_img as $id) {
                                if ($id != '') {
                                    if ($i == 0) {
                                        echo '<p class="primary_img">Primary Image <a href="javascript:(0);" class="tooltip_btn"><i class="fa fa-question"></i></a><span class="tooltip_text" style="display:none;">The Primary Image is the image that will appear as the large image on your website and will also be the image that appears on the order page after this product is added to cart by the customer.</span> </p>';
                                    }
                                    echo '<li class="view_image_list" data-id="' . $id . '">'
                                    . '<img src="' . wp_get_attachment_url($id) . '">'
                                    . '<a href="JavaScript:Void(0);" class="close_btn" onclick="deleteImage(this,' . $post_id . ',' . $id . ');">'
                                    . '<span class="dashicons dashicons-no"></span></a>';
                                    echo'</li>';
                                }
                                $i++;
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</section>
<script>
    jQuery(function () {
        jQuery("#sortable").sortable({
            deactivate: function (event, ui) {
                var serial = [];
                jQuery('#sortable > li').each(function () {
                    var a = jQuery(this).data('id');
                    serial.push(a);
                    jQuery('#shotred_series').val(serial);
                    console.log(serial);
                });

                /*-----------*/
                var post_imgtId = jQuery('#sortable').data('imgsrt');
                /*-----------*/
                jQuery.ajax({
                    type: 'POST',
                    url: ajax.ajax_url,
                    data: {'action': 'cartara_update_postmeta_image_short', 'meta_value': serial, 'post_id': post_imgtId},
                    success: function (response) {
                        console.log(response);
                    }
                });

                jQuery('#shotred_series_check').val('true');

            },
            placeholder: false
        });
        jQuery("#sortable").disableSelection();
    });

</script>