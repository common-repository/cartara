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
    echo '<div class = "row product_container_grid">';
    $i = 1;
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

        echo'<div class="cn360-col-md-4 product-div">
                             <div class="thumbnail product-information">
                               <div class="thumbnail_images"><img src="' . esc_url($fimg) . '" alt="" class="thumb_img"></div>
                                 <a href="' . $value->guid . '" class="more-info"><h3>' . (strlen($value->post_title) > 50 ? substr($value->post_title, 0, 50).'...' : $value->post_title) . '</h3></a>
                                <h4>' . 'Price ' . get_option('cartara_store_currencysymbol') . '' . (!empty(get_post_meta($post_id, '_price', true)) ? get_post_meta($post_id, '_price', true) : '0.00') . '</h4>
                                <div class="thumbnail-de">
                                       <div class="thumb-description"> 
                                        <div class="thumbnail_hover_images"><img src="' . esc_url($fimg) . '" alt=""  width=25% height="25%" >
                                           
                                        </div>
                                        <div class="product-text"><a href="' . esc_url($value->guid) . '"><h3>' . esc_html($value->post_title) . '</h3></a>
                                          
                                        <p>' . get_post_meta($post_id, '_shortdesc', true) . '</p></div>
                                        <div class="products-price"><p>' . 'Price ' . get_option('cartara_store_currencysymbol') . '' . (!empty(get_post_meta($post_id, '_price', true)) ? get_post_meta($post_id, '_price', true) : '0.00') . '</p>
                                            <span class="a-button-inner"><a href="' . esc_url($value->guid) . '" rel="nofollow"   id="a-autoid-10-announce" class="a-button-text">Details</a></span>                             
                                        </div>
                                     </div>
                                  </div>
                            </div>
                          
                    </div>';
        if ($i % 3 == 0) {
            echo'</div><div class = "row product_container_grid">';
        }
        $i++;
    }
    ?>
</div>