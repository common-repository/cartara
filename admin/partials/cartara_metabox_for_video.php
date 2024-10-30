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
    .ss_border{
        border-bottom: 1px solid #e8e8e8;
        padding-bottom: 10px;
        margin-bottom: 10px;

    }
    h3.video_url {
        margin: 0;
        margin-left: 25px;
        padding-top: 5px;
        font-size: 16px;
        font-weight: 600;
        color: #337ab7;
    }
    input#upload_button {
        margin-top: 2px;
        margin-left: 10px;

    }
    input#video_url {
        width: 300px;
    }
</style>
<section class="">
    <div class="container-fluid">
        <div class="wrap col-sm-12 upload_section">
            <div class="welcome-panel">
                <div class="welcome-panel-content">
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="text-box-row">

                                <div class="field_wrapper video_field">

                                </div>  
                                <div class="text-center">
                                    <input type="button" name="add_videos" class="add_video btn btn-primary" value="Add Videos" onclick="add_more_videos();" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <textarea name="video_short_series" id="video_short_series" hidden="true"></textarea>
                    <?php
                    $gallery_vedios = get_post_meta($post_id, 'cartara_videos_key', true);
                    ?>
                    <ul class="image_list" id="video_short" data-ppst="<?php echo $post_id; ?>">
                        <?php
                        if (!empty($gallery_vedios)) {
                            foreach ($gallery_vedios as $url) {
                                if ($url != '') {
                                    ?>
                                    <li class="view_video_list" data-src="<?php echo $url; ?>" >
                                        <img src="<?php echo plugin_dir_url(__DIR__) . 'images/video-img.png'; ?>" width="100" height="90" >
                                        <a href="javascript:void[0];" class="close_btn" data-src="<?php echo $url; ?>" onclick="deleteVideo(this, <?php echo $post_id; ?>);">
                                            <span class="dashicons dashicons-no"></span></a>
                                    </li>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    jQuery(function () {
        jQuery("#video_short").sortable({
            deactivate: function (event, ui) {
                var serial_vd = [];
                jQuery('#video_short > li').each(function () {
                    var a = jQuery(this).data('src');
                    serial_vd.push(a);

                    jQuery('#video_short_series').val(serial_vd);
                    console.log(serial_vd);
                });
                /*-----------*/
                var post_ppstId = jQuery('#video_short').data('ppst');
                /*-----------*/
                jQuery.ajax({
                    type: 'POST',
                    url: ajax.ajax_url,
                    data: {'action': 'cartara_update_postmeta_video_short', 'meta_value': serial_vd, 'post_id': post_ppstId},
                    success: function (response) {
                        console.log(response);
                    }
                });
            }
        });
        jQuery("#video_short").disableSelection();
    });
</script>