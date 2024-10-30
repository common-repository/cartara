<?php

/*
 * The public-facing functionality of the plugin.
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 * @package    cartara
 * @subpackage cartara/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    cartara
 * @subpackage cartara/public
 * @author     http://techexeitsolutions.com
 */
class cartara_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function cartara_enqueue_styles() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Cartara_sync_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Cartara_sync_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cartara-public.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . 'cartara_main', plugin_dir_url(__FILE__) . 'css/cartara_main.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function cartara_enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Cartara_sync_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Cartara_sync_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cartara-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function cartara_products_showcase() {

        $this->cartara_page_display();
        $this->cartara_page_display();
    }

    public function cartara_page_display() {
        $arr = array(
            'post_type' => 'cartara_product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'offset' => 0,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $posts = get_posts($arr);
        if (!empty($posts)) {
            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/cartara_product_grid_view.php';
        }
    }

    public function cartara_page_list_view() {

        $arr = array(
            'post_type' => 'cartara_product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'offset' => 0,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $posts = get_posts($arr);

        if (!empty($posts)) {
            /* --cartara_product_list_view -- */
            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/cartara_product_list_view.php';
        }
    }
	
	public function cartara_product_single_view($arr) {
		global $wpdb;
		$post_id = $arr['wp_product_id'];

        $value = sanitize(get_post($post_id));

        if (!empty($value)) {
            /* --cartara_product_single_view -- */
            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/cartara_product_single_view.php';
        }
	}
	
	public function cartara_product_1column_view($arr) {
		global $wpdb;
		$term_id = esc_attr($arr['term_id']);
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

        if (!empty($posts)) {
            /* --cartara_product_1column_view -- */
            ob_start();
            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/cartara_product_1column_view.php';
            return ob_get_clean();
        }
	}
	
	public function cartara_product_3column_view($arr) {
		global $wpdb;
		$term_id = esc_attr($arr['term_id']);
		
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
		//print_r($posts);
        if (!empty($posts)) {
            /* --cartara_product_single_view -- */
            ob_start();
            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/cartara_product_3column_view.php';
            return ob_get_clean();
        }
	}

    public function cartara_single_product($single_template) {
        global $wp_query, $post;
        /* -- Checks for single template by post type -- */
        if ($post->post_type == 'cartara_product') {

            $single_template = plugin_dir_path(__FILE__) . 'templates/single-cartara_product.php';
        }

        return $single_template;
    }
    
    public function cartara_product_categories($template) {
        global $wp_query, $post;
        /* -- Checks for single template by post type -- */
        if (is_tax('cartara_category')) {

            $template = plugin_dir_path(__FILE__) . 'templates/taxonomy-cartara_category.php';
        }

        return $template;
    }

    public function cartara_add_to_cartproduct() {
        $attr_groupname = array();
        $attr_optioname = array();

        if (sanitize(isset($_POST)) ) {
            if (intval($_POST['pwc_order_temp_id']) == null && sanitize($_POST['pwc_order_token']) == null) {
                setcookie('pwc_order_temp_id', 'null', time() + (86400 * 30), "/");
                setcookie('pwc_order_token', 'null', time() + (86400 * 30), "/");
            } else {
                unset($_COOKIE['pwc_order_temp_id']);
                unset($_COOKIE['pwc_order_token']);

                $ptemid = intval($_POST['pwc_order_temp_id']);
                $ptokenid = trim(sanitize($_POST['pwc_order_token']));

                setcookie('pwc_order_temp_id', $ptemid, time() + (86400 * 30), "/");
                setcookie('pwc_order_token', $ptokenid, time() + (86400 * 30), "/");
            }


            /* ------------------------------------------------- */
            $post_id = intval($_POST['pId']);
            $pwc_product_link_id = intval($_POST['pwc_product_link_id']);
            $addtocart_quantiy = trim(sanitize($_POST['addtocart_quantiy']));

            if (!empty(sanitize($_POST['attr_groupname'])) && !empty(sanitize($_POST['attr_optioname']))) {
                $attr_groupname = sanitize($_POST['attr_groupname']);
                $attr_optioname = sanitize($_POST['attr_optioname']);
            }


            $target_url = 'https://secureinfossl.com/api/addToCart';
            $target_url = 'https://secureinfossl.com/api/addToCart?apikey=PS4s1S3DF5rw5Fod5s4w8e4xds5d7w5e%3D&productlinkid=bccbdcace6abe8d150eddb7cb9cc3e36&quantity=1&thankyouurl=&recurringprofilerowid=&attributerowid=&ordertempid=11038183&token=0fa2abb05812b80a322bc95d0d7376864b74b14d';
            $merchant_id = get_option('cartara_mar_api_key');
            $api_key = get_option('cartara_mar_api_signature');

            $API_KEY = 'PS4s1S3DF5rw5Fod5s4w8e4xds5d7w5e=';
            $thankyouurl = '';
            $recurring_profile = '';
            $csv_options = '';
            if (!empty(sanitize($_POST['attributes']))) {
                $csv_options = implode(",", sanitize($_POST['attributes']));
            }

       
            if ((intval($_POST['pwc_order_temp_id']) != NULL) && (sanitize($_POST['pwc_order_token']) != NULL)) {

             
                $body = array('apikey'=>$API_KEY, 'productlinkid'=>$pwc_product_link_id, 'quantity'=>$addtocart_quantiy, 'thankyouurl'=>$thankyouurl, 'recurringprofilerowid'=>$recurring_profile, 'attributerowid'=>$csv_options, 'ordertempid'=>intval($_POST['pwc_order_temp_id']), 'token'=>sanitize($_POST['pwc_order_token']) );
            } else {

                $body = array('apikey'=>$API_KEY, 'productlinkid'=>$pwc_product_link_id, 'quantity'=>$addtocart_quantiy, 'thankyouurl'=>$thankyouurl, 'recurringprofilerowid'=>$recurring_profile, 'attributerowid'=>$csv_options);
            }
            
        	$request = new WP_Http();
    		$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    		if (isset($response_full->errors)) {
        		return array(500, 'Unknown Error');
    		}

                switch ($http_code = $response_full['response']['code']) {
                    case 200:
                    	$response = $response_full['body'];
                        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);

                        $url = '';
                        $img_id = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);
                        $img_id_s = get_post_meta($post_id, '_shotred_series', true);

                        /* ------------------------------- */
                        if (!empty($img_id[0]) && empty($img_id_s[0])) {
                            $url = wp_get_attachment_url($img_id[0]);
                        } else {
                            $url = wp_get_attachment_url($img_id_s[0]);
                        }
                        if ($url == '') {
                            $url = 'NULL';
                        }


                        /* ------------------------------- */
                       $token = (array) $xml->order->token;

                        $ordertempid = (array) $xml->order->ordertempid;
                       
                        $orderitemid = (array) $xml->order->orderitemid;
                        $redirecturl = (array) $xml->order->redirecturl;



                        /* -------------Assing new values in session---- */
                        unset($_COOKIE['pwc_order_temp_id']);
                        unset($_COOKIE['pwc_order_token']);

                        /* -------------Assing new values in session---- */

                        setcookie('pwc_order_temp_id', trim($ordertempid[0]), time() + (86400 * 30), "/");
                        setcookie('pwc_order_token', trim($token[0]), time() + (86400 * 30), "/");

                        /* -------------Assing new values in session---- */

                        $price = get_post_meta($post_id, '_price', true);
                        $item_pr_total = round(($price * $addtocart_quantiy), 2);

                        /* ---------set the item price per product---- */

                        $ret_cart_item = array(
                            'product_id' => $post_id,
                            'product_name' => get_the_title($post_id),
                            'product_site_url' => get_the_permalink($post_id),
                            'product_price' => get_option('cartara_store_currencysymbol') . ' ' . $item_pr_total,
                            'product_sku' => get_post_meta($post_id, '_sku', true),
                            'product_image_url' => esc_url($url),
                            'product_link_id' => trim($pwc_product_link_id),
                            'product_qty' => trim($addtocart_quantiy),
                            'response' => 'added',
                            'ordertempid' => $ordertempid[0],
                            'token' =>$token[0],
                            'orderitemid' => $orderitemid[0],
                            'redirecturl' => $redirecturl[0],
                            'user_ip' => $this->cartara_sl_get_ip(),
                            'attr_groupname' => json_encode($attr_groupname),
                            'attr_optioname' => json_encode($attr_optioname),
                        );

                        $retun_push_st = $this->cartara_incart_products($post_id, $ret_cart_item);
                        if ($retun_push_st) {
                            echo json_encode($ret_cart_item);
                        }
                        wp_die();
                        break;
                    default:
                        echo 'Unexpected HTTP code: ', $http_code, "\n";
                }
            //}
        }
    }

    public function cartara_incart_products($post_id, $product_data = array()) {

        global $wpdb;
        $cart_table = $wpdb->prefix . 'cartara_incart_products';
        $push_cart = true;
        $user_ip = $this->cartara_sl_get_ip();

        if (!empty($post_id) && !empty($product_data)) {
            $push_cart = $wpdb->insert(
                    $cart_table, array(
                'post_id' => $post_id,
                'product_data' => json_encode($product_data),
                'user_ip' => $user_ip
                    ), array(
                '%d',
                '%s',
                '%s'
                    )
            );

            if ($push_cart == 'false') {
                echo $wpdb->last_error;
            }
        }
        return $push_cart;
    }

    public function cartara_procedtochekout() {
        global $wpdb;
        $cart_table = $wpdb->prefix . 'cartara_incart_products';
        $push_cart = true;
        $user_ip = $this->cartara_sl_get_ip();

        $rem_check = $wpdb->delete($cart_table, array('user_ip' => $user_ip), array('%s'));

        if ($rem_check == 'false') {
            echo $wpdb->last_error;
        }
    }

    public function cartara_incart_data_byip() {

        global $wpdb;
        $cart_table = $wpdb->prefix . 'cartara_incart_products';
        $user_ip = $this->cartara_sl_get_ip();
        if (isset($user_ip) && $user_ip != "") {
            $get_cart = $wpdb->get_results('SELECT * FROM  ' . $cart_table . ' WHERE user_ip ="' . $user_ip . '"', ARRAY_A);

            if (!empty($get_cart)) {
                echo json_encode($get_cart);
            } else {
                echo '500';
            }
        } else {
            echo "error";
        }
        wp_die();
    }

    public function cartara_incart_item_remove() {
        global $wpdb;
        $cart_table = $wpdb->prefix . 'cartara_incart_products';
        $rem_check = false;

        /* --------------------------------------------------- */
        if (intval(isset($_POST['item_id']))) {
            $item_id = intval($_POST['item_id']);
            $rem_check = $wpdb->delete($cart_table, array('id' => $item_id), array('%d'));
        }

        /* --------------Add delete item  api script---------- */
        $product_detail = $wpdb->get_results('SELECT * FROM  ' . $cart_table . ' WHERE id ="' . $item_id . '" LIMIT 1', ARRAY_A);
        $all_pdata = $product_detail[0]['product_data'];

        /* --------------------------------------------------- */
        $p_data = json_decode($all_pdata, true);
        $target_url = 'https://secureinfossl.com/api/removeFromCart.html';
        $API_KEY = 'PS4s1S3DF5rw5Fod5s4w8e4xds5d7w5e=';

        /* --------------------------------------------------- */
        $orderitemid = intval($_POST['orderitemid']);

        if ( isset( $_COOKIE["pwc_order_temp_id"]) ){
             $pwc_order_temp_id = trim($_COOKIE["pwc_order_temp_id"]); 
        }else{
             return false;
        }

      
        if ( isset($_COOKIE["pwc_order_token"])){
             $pwc_order_token = trim($_COOKIE["pwc_order_token"]); 
        }else{
             return false;
        }
       


        $pwc_product_link_id = trim(sanitize($p_data['product_link_id']));
     
		$body = array('apikey'=>$API_KEY, 'productlinkid'=>$pwc_product_link_id, 'orderitemid'=>$orderitemid);
        $request = new WP_Http();
    	$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    	if (isset($response_full->errors)) {
        	return array(500, 'Unknown Error');
    	}
    	$response = $response_full['body'];
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);


        /* ---------------------------------------------- */
        $token = (array) $xml->order->token;
        $ordertempid = (array) $xml->order->ordertempid;
        $orderitemid = (array) $xml->order->orderitemid;
        $redirecturl = (array) $xml->order->redirecturl;

        /* -------------Assing new values in session---- */

        unset($_COOKIE['pwc_order_temp_id']);
        unset($_COOKIE['pwc_order_token']);


        setcookie('pwc_order_temp_id', trim($ordertempid[0]), time() + (86400 * 30), "/");
        setcookie('pwc_order_token', trim($token[0]), time() + (86400 * 30), "/");

        /* ----------------------------------------------------- */

        /* --------------Add delete item  api script---------- */
        echo $rem_check;
        wp_die();
    }

    public function cartara_sl_get_ip() {

        $pwc_cartuser_ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ( $ip === false ) ? '0.0.0.0' : $ip;

        if(isset($_COOKIE["pwc_cartuser_ip"])){

            if ($_COOKIE["pwc_cartuser_ip"] == NULL) {
                $uk = rand(0, 999999);
                $pwc_cartuser_ip = $uk . $ip;
                setcookie('pwc_cartuser_ip', $pwc_cartuser_ip, time() + (86400 * 30), "/");
            } else {
                $pwc_cartuser_ip = $_COOKIE["pwc_cartuser_ip"];
            }
            return $pwc_cartuser_ip;
        }else{
            return false;
        }       

    }

    public function cartara_sl_getfront_ip() {
        $pwc_cartuser_ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ( $ip === false ) ? '0.0.0.0' : $ip;

        if(isset($_COOKIE["pwc_cartuser_ip"])){

             if ($_COOKIE["pwc_cartuser_ip"] == NULL) {
                 $uk = rand(0, 999999);
                 $pwc_cartuser_ip = $uk . $ip;
                 setcookie('pwc_cartuser_ip', $pwc_cartuser_ip, time() + (86400 * 30), "/");
             }else{
                 $pwc_cartuser_ip = $_COOKIE["pwc_cartuser_ip"];
             }
            echo $pwc_cartuser_ip;
            wp_die();
           
        }else{
            return false;
        }
       
    }

   

    public function cartara_add_popcart() {
        $pwc_order_temp_id = isset($_COOKIE["pwc_order_temp_id"]) ? $_COOKIE["pwc_order_temp_id"] : '';
        $pwc_order_token = isset($_COOKIE["pwc_order_token"]) ? $_COOKIE["pwc_order_token"] : ''; ;
        $send_c = "send_ckout('$pwc_order_temp_id','$pwc_order_token')";

        echo '<div class="cn360-section"><div class="cln_cart_popup" id="cloud_Modal" tabindex="-1" role="dialog" aria-labelledby="cloud_Modal-2">
                <div class="cn360_modal-dialog cn360_modal-lg" role="document">
                    <div class="cn360_modal-content">
                    <button type="button" class="cn360_close">&times;</button>
                        <div class="cn360_modal-body">                          
                           <ul class="pop_top_list">
                           <li class="cn360_containet-fluid" >
                            <div class="cn360_row clearfix pop_head">
                            <div class="cn360_col-md-2"><h4>CART ITEMS</h4></div>
                            <div class="cn360_col-md-5"><h4 style="opacity:0 !important;">Product name</h4></div>
                           <div class="cn360_col-md-5 pop_right_head"><h4>QTY</h4><h4>ITEM PRICE</h4><h4>TOTAL</h4><h4 style="width: 30px;">&nbsp;</h4></div>
                           </div>
                           </li>
                           </ul>
                            <ul id="cart_body_set">

                            </ul>
                            <div class="pop_bottom"><div class="text_left">Total Items : <span class="itemCount"></span></div><div class="text_right"><b>Grand Total :</b> $<span class="gdTotal"></span></div></div>
                        </div>

                        <div class="cn360_modal-footer">
                            <button type="button" class="cn360_btn btn-dialog continue">Continue Shopping</button>
                            <button type="button" class="chk_btn" onclick="' . $send_c . '"><span><span class="chk-2-text">Proceed To Checkout</span></span> <svg aria-hidden="true" data-prefix="fas" data-icon="caret-right" role="img" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 192 512" class="svg-inline--fa fa-caret-right fa-w-6 fa-3x"><path fill="currentColor" d="M0 384.662V127.338c0-17.818 21.543-26.741 34.142-14.142l128.662 128.662c7.81 7.81 7.81 20.474 0 28.284L34.142 398.804C21.543 411.404 0 402.48 0 384.662z" class=""></path></svg></button>
                        </div>
                    </div> 
                </div> 
            </div></div>';
    }


    public function cartara_get_cart_tip_count() {

        global $wpdb;
        $cart_table = $wpdb->prefix . 'cartara_incart_products';
        $iip = $this->cartara_sl_get_ip();
        $get_cart = $wpdb->get_results('SELECT COUNT(*) FROM  ' . $cart_table . ' WHERE user_ip ="' . $iip . '"', ARRAY_A);
        $get_cart_qty = $wpdb->get_results('SELECT * FROM  ' . $cart_table . ' WHERE user_ip ="' . $iip . '"', ARRAY_A);

        $qty = array();
        $qty_data = array();
        foreach ($get_cart_qty as $get_cart_qty_val) {
            $qty_data[] = json_decode($get_cart_qty_val['product_data'], true);
        }
        $total_qty = '0';
        foreach ($qty_data as $qty_data_val) {
            $total_qty += trim($qty_data_val['product_qty']);
        }

        $cct = $get_cart[0]['COUNT(*)'];

        if(isset($_COOKIE["pwc_order_temp_id"])){

            if ($_COOKIE["pwc_order_temp_id"] == 'null' || $_COOKIE["pwc_order_temp_id"] == 'undefined') {
            $pwc_order_temp_id = 'null';
            } else {
              $pwc_order_temp_id = $_COOKIE["pwc_order_temp_id"];
             }           
        }else{
            return false;
        }

         
         if(isset($_COOKIE["pwc_order_token"])){

            if ($_COOKIE["pwc_order_token"] == 'null' || $_COOKIE["pwc_order_token"] == 'undefined') {

            $pwc_order_token = 'null';
            }else{
               $pwc_order_token = $_COOKIE["pwc_order_token"];
              }           
        }else{
            return false;
        }    

        

        echo json_encode(array('cct' => $cct, 'ordertempid' => $pwc_order_temp_id, 'token' => $pwc_order_token, 'cart_total' => $total_qty));

        wp_die();
    }

}

?>