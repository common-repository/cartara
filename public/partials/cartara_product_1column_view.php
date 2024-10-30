<?php /**
 * Provide a public  area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://techexeitsolutions.com/
 * @since      1.0.0
 * @package    cartara
 * @subpackage cartara/public/partials
 */ ?>
<style>
    .thumbnail_hover_images {
        flex-direction: column;
    }
</style>

<div class="cn360-section">
    <?php
   
    foreach ($posts as $value) {
        $fimg = '';
        $post_id = $value->ID;
        $image_path = get_post_meta($post_id, '_imagepath', true);

        $gallery_img = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);
        $_shotred_series = get_post_meta($post_id, '_shotred_series', true);

        $rst = $_shotred_series;
        if (empty($gallery_img)) {
            $fimg = CARTARA_SYNC__PLUGIN_URL . 'admin/images/img_found.jpg';
        } else {
            $fimg = wp_get_attachment_url($gallery_img[0]);
        }


        $img_id = '';
        if (!empty($rst) && $rst[0] != '') {
            $img_id = $rst;
        } else {
            if (!empty($gallery_img)) {
                $img_id = $gallery_img;
            }
        }
		$prod_short_desc = get_post_meta($post_id, '_shortdesc', true);
        echo'
                             
                               
                                <div class="thumbnail-single-product">
                                       <div class="thumb-description"> 
                                        <div class="thumbnail_hover_images"><img src="' . esc_url($fimg) . '" alt=""  width=25% height="25%" >
                                           
                                        </div>
                                        <div class="product-text"><a href="' . esc_url($value->guid) . '"><h3>' . esc_html($value->post_title) . '</h3></a>
                                          
                                        <p>' . (strlen($prod_short_desc) > 150 ? substr($prod_short_desc,150).'....' : $prod_short_desc) . '</p>';																				?>														
                                        <?php                    
                                        $attr_meta = get_post_meta($post_id, '_product_ass_attrs', true);                    
                                        if (!empty($attr_meta)) {                        
                                        ?>                        
                                        <div><h3>Product Options - <a href="<?=$value->guid?>">Click Here</a></h3></div>                        
                                                           
                                    <?php } ?>					
                                    <?php                     
                                    echo          		'</div>
                                        <div class="products-price"><p>' . 'Price ' . get_option('cartara_store_currencysymbol') . '' . (!empty(get_post_meta($post_id, '_price', true)) ? get_post_meta($post_id, '_price', true) : '0.00') . '</p>																<span class="a-button-inner"><a href="' . esc_url($value->guid) . '" rel="nofollow"   id="a-autoid-10-announce" class="a-button-text">Details</a></span>                             
                                        </div>
                                     </div>
                                  </div>
                            	<div style="width:100%;height:10px"></div>
                          
                    ';
       }
    ?>
</div>