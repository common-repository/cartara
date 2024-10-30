<?php
/**
 * Template Name: Cartara Product
 */
get_header();
?>
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
 <p>
<style>
    .thumbnail_hover_images {
        flex-direction: column;
    }
    #sidebar {
    	padding-top: 25px;
    	position: relative;
	}
	.col-4 {
    	width: 25%;
	}
	[class*="col-"] {
    	float: left;
    	padding: 25px 25px 25px 50px;
        
	}
	#sidebar ul, #sub_footer ul {
    	list-style: none;
	}
	#sidebar li {
    	list-style: none;
	}
</style>
</p>
<div class="page-content" style="width:100%;height:auto;min-height:900px;">
		

<?php 
	$get_template_dir = get_template_directory(); 
	$sidebar_path = $get_template_dir.'/sidebar.php';
 	if(file_exists($sidebar_path)) {
 		include($sidebar_path);
 	} else {
 ?>

<div id="sidebar" class="col-4">
	<?php
	$active_widgets = get_option( 'sidebars_widgets' );
	$sidebar_keys = array_keys($active_widgets);
	$main_key = $sidebar_keys[1];
	?>
	<?php dynamic_sidebar($main_key);  ?>
</div>

<?php } ?>

<div class="cn360-section" style="float:left;width:60%;">
    <?php
    $queried_object = get_queried_object();
			$term_id = $queried_object->term_id;			
			$posts = get_posts(
    			array(
        			'posts_per_page' => -1,
        			'post_type' => 'cartara_product',
        			'tax_query' => array(
            			array(
                			'taxonomy' => 'cartara_category',
                			'field' => 'term_id',
                			'terms' => $term_id,
            			)
        			),
        			'order' => 'ASC',
  					'orderby' => 'meta_value_num',
  					'meta_key' => 'cartara_product_order_'.$term_id
    			)
			);
			
		if (empty($posts)) {
			$posts = get_posts(
    			array(
        			'posts_per_page' => -1,
        			'post_type' => 'cartara_product',
        			'tax_query' => array(
            			array(
                			'taxonomy' => 'cartara_category',
                			'field' => 'term_id',
                			'terms' => $term_id,
            			)
        			)
    			)
			);
		}
			
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
		$product_price = get_post_meta($post_id, '_price', true);
        echo'<div class="cn360-col-md-4 product-div">
                             <div class="thumbnail product-information">
                               <div class="thumbnail_images"><img src="' . esc_url($fimg) . '" alt="" class="thumb_img"/></div>
                                 <a href="' . esc_url($value->guid) . '" class="more-info"><h3>' . (strlen($value->post_title) > 50 ? substr($value->post_title, 0, 50).'...' : esc_html($value->post_title) ). '</h3></a>
                                <h4>' . 'Price ' . get_option('cartara_store_currencysymbol') . '' . (!empty($product_price) ? $product_price : '0.00') . '</h4>
                                <div class="thumbnail-de">
                                       <div class="thumb-description"> 
                                        <div class="thumbnail_hover_images"><img src="' . esc_url($fimg) . '" alt=""  width=25% height="25%" >
                                           
                                        </div>
                                        <div class="product-text"><a href="' . esc_url($value->guid) . '"><h3>' . esc_html($value->post_title) . '</h3></a>
                                          
                                        <p>' . get_post_meta($post_id, '_shortdesc', true) . '</p></div>
                                        <div class="products-price"><p>' . 'Price ' . get_option('cartara_store_currencysymbol') . '' . (( trim($product_price)!='' ) ? $product_price : '0.00') . '</p>
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

<div style="clear:both"></div>
	</div><!-- #primary -->
	
<?php get_footer();?>