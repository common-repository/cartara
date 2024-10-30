<?php
/**
 * Template Name: Cartara Product
 */
get_header();
?>
<style>
    .cn360-section .inline_label {
        display: inline-block;
        width: 20%;
    }
    .cn360-section .option_input {
        width: 80%;
        display: inline-block; 
    }
    .cn360-section .option_txt {
        font-size: 18px !important;
    }

    .cn360-section .flex-viewport, .span_2_of_a1.simpleCart_shelfItem {
        padding: 0 15px;
        overflow: hidden;
    }
    .cn360-section audio, canvas, progress, video {
        height: 385px; 
    }
    .cn360-section .flex-control-thumbs { 
        display: inline-block;
        justify-content: center;
        align-items: center;
        white-space: nowrap;
        overflow: hidden; 
    }

    .cn360-section .flex-control-thumbs li {
        width: auto;
        padding: 0px; 
        display: inline-block; 
        cursor: move; 
    }
    .cn360-section .flex-control-thumbs li > * {
        max-width: 75px;
        height: 75px !important;
        float: left;
        margin-right: 10px;
    } 
    .cn360-section .flex-viewport ul {
        padding-left: 0;
        overflow: hidden; 
    }
    .cn360-section .flex-viewport ul {
        padding-left: 0;
    }
    .cn360-section .dragscroll{
        padding-left: 0;  overflow-y:hidden;  cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab;
    }
    .cn360-section .add_t_cart {
        cursor: default !important;
    }
  
</style>
<div class="cn360-section">
    <div class="product-dis">
        <div class="cn360-col-md-5 grid-im">
            <?php
            $post_id = intval(get_the_ID());
            $gallery_img = array();
            $_product_link_id = get_post_meta($post_id, '_product_link_id', true);
            $gallery_img = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);

            $new_gal_vid = array();
            /* ----------------------video shorted------------------ */
            $gallery_vedios = array();
            $gallery_vedios = get_post_meta($post_id, 'cartara_videos_key', true);
            //print_r($gallery_vedios);
            $s_vedios = get_post_meta($post_id, 'cartara_videos_short_series', true);
            $new_gal_vid = $gallery_img;
            if (!empty($gallery_vedios)) {
                $new_gal_vid = array_merge($new_gal_vid, $gallery_vedios);
            }
            $dragable = 'draggable="false"';

            if (!empty($new_gal_vid)) {
                ?>
                <div class="flexslider">
                    <div class="flex-viewport">
                        <ul>
                            <?php
                            $s = 0;
                            foreach ($new_gal_vid as $gl_data_id) {
                                if(is_numeric($gl_data_id)) {
                                	$type = get_post_mime_type($gl_data_id);
                                } else {
                                	$mypost = get_page_by_title($gl_data_id, OBJECT, 'attachment'); 
                                	$gl_data_id = $mypost->ID;
                                	$type = get_post_mime_type($gl_data_id);
                                }
                                if ($s == 0) {
                                    switch ($type) {
                                        case 'image/jpeg':
                                        case 'image/png':
                                        case 'image/gif':
                                            echo '<li class="thumb-image on' . $s . ' active">
                                            <div><img src="' . wp_get_attachment_url($gl_data_id) . '" class="cn360-img-responsive" > </div>
                                  </li>';
                                            break;
                                        case 'video/mpeg':
                                        case 'video/mp4':
                                        case 'video/quicktime':
                                        case 'video/x-ms-wmv':
                                        case 'video/3gpp':
                                            echo'<li class="thumb-image on' . $s . '">
                                         <video width="100%" controls>
                                            <source src=' . wp_get_attachment_url($gl_data_id) . ' type="video/mp4">
                                         </video>
                                     </li>';
                                            break;
                                    }
                                } else {
                                    switch ($type) {
                                        case 'image/jpeg':
                                        case 'image/png':
                                        case 'image/gif':
                                            echo '<li class="thumb-image on' . $s . '">
                                            <div><img src=' . wp_get_attachment_url($gl_data_id) . ' class="cn360-img-responsive" > </div>
                                   </li>';
                                            break;
                                        case 'video/mpeg':
                                        case 'video/mp4':
                                        case 'video/quicktime':
                                        case 'video/x-ms-wmv':
                                        case 'video/3gpp':
                                            echo'<li class="thumb-image on' . $s . '">
                                         <video width="100%" controls>
                                            <source src=' . wp_get_attachment_url($gl_data_id) . ' type="video/mp4">
                                         </video>
                                    </li>';
                                            break;
                                    }
                                }
                                $s++;
                            }
                            ?>
                        </ul>
                        <div class="list_scroll">
                            
                        </div>
                        <ol class="flex-control-thumbs scrollbar dragscroll">
                            <?php
                            $l = 0;
                            foreach ($new_gal_vid as $gl_data_id_ol) {
                            	if(is_numeric($gl_data_id_ol)) {
                                	$type = get_post_mime_type($gl_data_id_ol);
                                } else {
                                	$mypost = get_page_by_title($gl_data_id_ol, OBJECT, 'attachment'); 
                                	$gl_data_id_ol = $mypost->ID;
                                	$type = get_post_mime_type($gl_data_id_ol);
                                }
                                
                                if ($l == 0) {
                                    switch ($type) {
                                        case 'image/jpeg':
                                        case 'image/png':
                                        case 'image/gif':
                                            echo ' <li class="flex-active' . $l . '"><img src="' . wp_get_attachment_url($gl_data_id_ol) . '" ' . $dragable . '  onclick="showMe(this,' . $l . ');"></li>';
                                            break;
                                        case 'video/mpeg':
                                        case 'video/mp4':
                                        case 'video/quicktime':
                                        case 'video/x-ms-wmv':
                                        case 'video/3gpp':
                                            echo ' <li class="flex-active' . $l . '"><img src="'. CARTARA_SYNC__PLUGIN_URL.'public/images/Icon_10-512.png" onclick="showMe(this,' . $l . ');">';
                                            echo'</li>';
                                            break;
                                    }
                                } else {
                                    switch ($type) {
                                        case 'image/jpeg':
                                        case 'image/png':
                                        case 'image/gif':
                                            echo ' <li class="flex-active' . $l . '"><img src="' . wp_get_attachment_url($gl_data_id_ol) . '" ' . $dragable . '  onclick="showMe(this,' . $l . ');"></li>';
                                            break;
                                        case 'video/mpeg':
                                        case 'video/mp4':
                                        case 'video/quicktime':
                                        case 'video/x-ms-wmv':
                                        case 'video/3gpp':
                                            echo ' <li class="flex-active' . $l . '"><img src="'. CARTARA_SYNC__PLUGIN_URL.'public/images/Icon_10-512.png" onclick="showMe(this,' . $l . ');">';
                                            echo '</li>';
                                            break;
                                    }
                                }
                                $l++;
                            }
                            ?>
                        </ol>
                    </div>
                </div>
            <?php } else {
                ?>
                <img src="<?php echo CARTARA_SYNC__PLUGIN_URL; ?>admin/images/img_found.jpg" class="cn360-img-responsive">
            <?php } ?>

        </div>

        <div class="cn360-col-md-7 single-top-in">
            <div class="span_2_of_a1 simpleCart_shelfItem">
                <h3><?php echo get_the_title(); ?></h3>
                <div class="price_single"><h3><span class="reducedfrom item_price"><?php echo get_option('cartara_store_currencysymbol') . '' . (!empty(get_post_meta($post_id, '_price', true)) ? get_post_meta($post_id, '_price', true) : '0.00'); ?></span></h3>

                    <?php
                    $attr_meta = get_post_meta($post_id, '_product_ass_attrs', true);
					
                    if (!empty($attr_meta)) {
                        ?>

                        <div><h5><span class="reducedfrom option_txt">Options</span></h5></div>
                        <div class="option_display">
                            <?php
                            $fdat = implode(",", $attr_meta);
                            $get_groups = $wpdb->get_results('SELECT grp.groupname, GROUP_CONCAT(CONCAT(attributename,"::", attributerowid)) as attrdata FROM ' . $wpdb->prefix . 'cartara_product_attribute attr INNER JOIN ' . $wpdb->prefix . 'cartara_product_attribute_group grp ON grp.grouprowid=attr.grouprowid WHERE attr.attributerowid IN (' . $fdat . ') GROUP BY grp.grouprowid', ARRAY_A);

                            if (!empty($get_groups)) {
                                foreach ($get_groups as $attribute_data_val) {
                                    ?>
                                    <label class = "inline_label"><?php echo strtoupper($attribute_data_val['groupname']); ?></label>
                                    <select class="cn360-form-control option_input " data-grouprowid="<?php echo strtoupper($attribute_data_val['groupname']); ?>"  data-groupname="<?php echo strtoupper($attribute_data_val['groupname']); ?>" onchange="check_cloud_option();">
                                        <option data-option="<?php echo strtoupper($attribute_data_val['groupname']); ?>" value="0"><?php echo 'Select ' . $attribute_data_val['groupname']; ?></option> 
                                        <?php
                                        $option = $attribute_data_val['attrdata'];
                                        $data_set = explode(',', $option);
                                        foreach ($data_set as $data_set_opp) {
                                            $t = explode('::', $data_set_opp);
                                            ?>
                                            <option value="<?php echo $t[1]; ?>">
                                                <?php echo ucfirst($t[0]); ?>
                                            </option>
                                        <?php }
                                        ?>  
                                    </select>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
                

                <h3>Quantity :</h3>  
                <div class="value-button minus_btn" id="decrease" onclick="decreaseValue(this)" value="Decrease Value">-</div>
                <input type="text" class="number" id="addtocart_quantiy" value="1" />
                <div class="value-button" id="increase" onclick="increaseValue(this)" value="Increase Value">+</div>
                <!--product_link_id-->
                <input type="text" id="product_link_id" value="<?php echo $_product_link_id; ?>" hidden>
                <!--product_link_id-->
                &nbsp;
                <button type="button" class="cn360-btn cn360-btn-danger add_t_cart" onclick="add_to_cart(<?php echo $post_id; ?>);" >
                    Add To Cart <span class="dashicons dashicons-image-rotate loader_icon" style="display:none;"></span>
                </button>
                &nbsp;
                <a  class="cn360-btn cn360-btn-danger back_btn" href="<?php echo site_url('/shop'); ?>">  Back </a>
                <?php if (!empty(trim(get_post_field('_shortdesc', $post_id)))) { ?>
                    <h3>Description: </h3>
                    <p class="description"><?php echo get_post_meta($post_id, '_shortdesc', true); ?></p>
                <?php } ?>
                <?php if (!empty(get_post_meta($post_id, 'post_content', true))) { ?>
                    <h3>Long Description: </h3> 
                    <p class="long_description"><?php echo get_post_field('post_content', $post_id); ?></p>
                <?php } ?>
                <h5 class="cartstatus"></h5>
            </div>
            <div class="cn360-clearfix"> </div>
        </div>
        <!----- tabs-box ----> 
    </div>
    <!-----> 
</div>
<?php get_footer();?>