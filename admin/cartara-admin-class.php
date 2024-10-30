<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 * @package    cartara
 * @subpackage cartara/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    cartara
 * @subpackage cartara/admin
 * @author     http://techexeitsolutions.com
 */
class cartara_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function cartara_personalised_cartara_menu() {

        add_menu_page('Cartara', 'Cartara', 'manage_options', 'cartara_menu', array($this, 'cartara_page_function'), 'dashicons-cloud'); //dashicons-update
        add_submenu_page('cartara_menu', 'Cartara Dashboard', 'Dashboard', 'edit_posts', 'cartara_menu');
        add_submenu_page('cartara_menu', 'Cartara Categories', 'Categories', 'manage_options', 'edit-tags.php?taxonomy=cartara_category&post_type=cartara_product', NULL); 
        add_submenu_page('cartara_menu', 'Cartara Products', 'Products', 'manage_options', 'edit.php?post_type=cartara_product', NULL); //My Product
        add_submenu_page('cartara_menu', 'Cartara Opt-In Forms', 'Opt-In Forms', 'edit_posts', 'optform', array($this, 'cartara_optin_display'));
         add_submenu_page('cartara_menu', 'Cartara Surveys Action Forms', 'Action Surveys', 'edit_posts', 'cartasurvey', array($this, 'cartara_survey_display'));
        add_submenu_page(null, 'Cartara Form', 'Cartara Shortcoder', 'edit_posts', 'cartara_shortcoder', array($this, 'cartara_cr_form'));
        add_submenu_page(null, 'Cartara Survey Form', 'Cartara Survey Shortcoder', 'edit_posts', 'cartara_survey_shortcoder', array($this, 'cartara_cr_survey_form'));
        add_submenu_page('cartara_menu', 'Cartara API Settings', 'API Settings', 'edit_posts', 'api_setting', array($this, 'cartara_subpage_function_api_settings'));
    }

    public function cartara_enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cartara-admin.css', array(), $this->version, 'all');

        wp_enqueue_style($this->plugin_name . 'font-file',  plugin_dir_url(__FILE__) . 'css/font-awesome.css', array(), '4.7.0');
     
        wp_enqueue_style($this->plugin_name . 'jquery-ui-css-file', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name. 'shortcode-file', plugin_dir_url(__FILE__) . 'css/shortcode.css', array(), $this->version, 'all');
    }

    public function cartara_enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cartara-admin.js', array('jquery'), $this->version, false);
        $data = array('ajax_url' => admin_url('admin-ajax.php'));
        wp_localize_script($this->plugin_name, 'ajax', $data);
        wp_enqueue_script($this->plugin_name . 'sweetalert-jq', plugin_dir_url(__FILE__) . 'js/sweetalert.min.js',array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . 'bootstrap-toggle-jq',  plugin_dir_url(__FILE__) . 'js/bootstrap-toggle.min.js', array('jquery'),'2.2.0');
        
       
    }

    public function cartara_page_function() {
        include_once 'partials/cartara_sync-admin-display.php';
    }

    public function cartara_subpage_function_orders() {
      
    }

    public function cartara_subpage_function_api_settings() {
        $url_api_setting = $this->cartara_get_current_url();
        include_once 'partials/cartara_api_settings_page.php';
    }

    /* ------------CREATE THE TREE VIEW FOR THE ATTER SET--------------- */

    public function cartara_get_all_attribute_groups_and_value() {
        global $wpdb;
        $attribute_data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "cartara_product_attribute_group LEFT JOIN " . $wpdb->prefix . "cartara_product_attribute ON (" . $wpdb->prefix . "cartara_product_attribute.grouprowid = " . $wpdb->prefix . "cartara_product_attribute_group.grouprowid)", ARRAY_A);
        $attribute_groupname = $wpdb->get_results("SELECT groupname FROM " . $wpdb->prefix . "cartara_product_attribute_group WHERE 1", ARRAY_A);


        if (is_wp_error($attribute_data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }


        if (is_wp_error($attribute_groupname)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }

        include_once 'partials/cartara_display_attribute_groups.php';
    }

    public function cartara_optin_display() {
        global $wpdb;
        $data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cartara_optin_form_data WHERE 1', ARRAY_A);


        if (is_wp_error($data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }


        include_once 'partials/cartara_optin_display_shortcode.php';
    }
    public function cartara_survey_display() {
        global $wpdb;
        $data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cartara_optin_survey_data WHERE 1', ARRAY_A);


        if (is_wp_error($data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }


        include_once 'partials/cartara_optin_survey_display_shortcode.php';
    }

    public function cartara_cr_form() {

        global $wpdb;
        $data = '';
        if (intval(isset($_GET['id'])) != '') {
            $data = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'cartara_optin_form_data WHERE id=' . intval($_GET['id']), ARRAY_A);
        } else {
            $data = array(
                'formname' => '',
                'formdata' => '',
                'visible' => '',
                'status' => ''
            );
        }


        if (is_wp_error($data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
        include_once 'partials/cartara_opt_form.php';
    }

    public function cartara_cr_form_save() {
		$transient_name = md5( 'CARTARA_SYNC' . get_current_user_id() );
		$notices = new CartaraTransientAdminNotices( $transient_name );
        global $wpdb;
        $tablename = $wpdb->prefix . 'cartara_optin_form_data';
        if (sanitize(isset($_POST['cn_save']) )) {

            if (sanitize(!isset($_POST['cn_create_form']) ) || !wp_verify_nonce(sanitize($_POST['cn_create_form']), 'cn_save_action')) {
         
				$notices->add( 'save_cartara', 'Sorry, your nonce did not verify.', 'error' );
            } else {

                // process form data
                if ((sanitize($_POST['cn_name']) ) != '') {
                    $formname = sanitize($_POST['cn_name']);
                }
                if ((sanitize($_POST['form_content'])) != '') {
                    $formdata = sanitize($_POST['form_content']);
                }
                if (sanitize(isset($_POST['cn_disable'])) && sanitize($_POST['cn_disable']) != '') {
                    $cn_disable = sanitize($_POST['cn_disable']);
                } else {
                    $cn_disable = '0';
                }

                if ((sanitize($_POST['cn_devices'])) != '') {
                    $cn_visible = sanitize($_POST['cn_devices']);
                }

                if (intval(isset($_GET['id'])) != '') {
                    $id = intval($_GET['id']);
                    $data = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'cartara_optin_form_data WHERE id=' . $id, ARRAY_A);
					if (is_wp_error($data)) {
                        $error_string = $wpdb->get_error_messages();
						$notices->add( 'save_cartara', 'No such form exits.'.$error_string , 'warning' );
						wp_safe_redirect(admin_url() . "admin.php?page=optform");
						exit;
                    }else{
						$update = $wpdb->query(
								$wpdb->prepare(
										"UPDATE $tablename SET formname='%s', formdata='%s', status='%s',visible='%s' WHERE id=%d", $formname, $formdata, $cn_disable, $cn_visible, $id)
						);
						if (is_wp_error($update)) {
							$error_string = $wpdb->get_error_messages();
							$notices->add( 'save_cartara', 'Sorry, failed to save form '.$formname.'.Error: '.$error_string, 'error' );
							wp_safe_redirect(admin_url() . "admin.php?page=optform");
							exit;
						}
						if ($update !== false) {
							wp_safe_redirect(admin_url() . "admin.php?page=optform");
							exit;
						} else {
						   $notices->add( 'save_cartara', 'Sorry, failed to save form '.$formname.'.', 'error' );
							wp_safe_redirect(admin_url() . "admin.php?page=optform");
							exit;
						}
						
						
					}
                } else {
                    
                    $lastid = $wpdb->insert($tablename, array(
                        'formname' => $formname,
                        'formdata' => $formdata,
                        'status' => $cn_disable,
                        'visible' => $cn_visible
                            ), array('%s', '%s', '%s', '%s')
                    );
                    if ($lastid != '') {
                 
						wp_safe_redirect(admin_url() . "admin.php?page=optform");
						exit;
                    } else {
                        $notices->add( 'save_cartara', 'Sorry, failed to save form '.$formname.'.', 'error' );
						wp_safe_redirect(admin_url() . "admin.php?page=optform");
						exit;
                    }
                    if (is_wp_error($lastid)) {
                        $error_string = $wpdb->get_error_messages();
						$notices->add( 'save_cartara', 'Sorry, failed to save form '.$formname.'.Error: '.$error_string, 'error' );
						wp_safe_redirect(admin_url() . "admin.php?page=optform");
						exit;
                    }
                }
            }
        }
    }
    

    public function cartara_optin_display_shortcode($atts) {
        ob_start();
        global $wpdb;
        if (!empty(esc_attr($atts['id']) )) {
            $form_id = esc_attr($atts['id']);
            $optin_data = $wpdb->prefix . 'cartara_optin_form_data';
            $form_data = $wpdb->get_results("SELECT * FROM $optin_data WHERE id= '$form_id '", ARRAY_A);
            if (!empty($form_data)) {
                $form_name = esc_html($form_data[0]['formname']);
                $form_data_html = stripslashes($form_data[0]['formdata']);
                $status = esc_html($form_data[0]['status']);

                if ($status == '0') {
                    $dy_class = $form_data[0]['visible'];

                    switch ($dy_class) {
                        case 'all':
                            echo '<div class="cn_form_output_all admin_output">' . $form_data_html . '</div>';
                            break;
                        case 'mobile_only':
                            echo '<div class="cn_form_output_mobile_only">' . $form_data_html . '</div>';
                            break;
                        case 'desktop_only':
                            echo '<div class="cn_form_output_desktop_only">' . $form_data_html . '</div>';
                            break;
                    }
                } else {
                    echo "<p>This shortcode is temporary disabled</p>";
                }
            }
            if (is_wp_error($form_data)) {
                $error_string = $wpdb->get_error_messages();
                echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
            }
        }
         return ob_get_clean();
    }

    public function cartara_delete_shortcode() {
        global $wpdb;
        $optin_data = $wpdb->prefix . 'cartara_optin_form_data';
        $id = intval($_POST['id']);
        if ($id != '') {
            $delete_id = $wpdb->query('DELETE FROM  ' . $optin_data . ' WHERE id = "' . $id . '"');

            if ($delete_id > 0) {
                echo "Deleted successfully";
            } else {

                echo "Something went wrong" . $error;
            }
        }
        wp_die();
    }
    /* ------------SURVEY FORMS CUSTOM CODE------------------------------*/
     public function cartara_cr_survey_form() {

        global $wpdb;
        $data = '';
        if (intval(isset($_GET['id'])) != '') {
            $data = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'cartara_optin_survey_data WHERE id=' . intval($_GET['id']), ARRAY_A);
        } else {
            $data = array(
                'formname' => '',
                'formdata' => '',
                'visible' => '',
                'status' => ''
            );
        }


        if (is_wp_error($data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
        include_once 'partials/cartara_opt_survey_form.php';
    }

    public function cartara_cr_survey_save() {
        $transient_name = md5( 'CARTARA_SYNC' . get_current_user_id() );
        $notices = new CartaraTransientAdminNotices( $transient_name );
        global $wpdb;
        $tablename = $wpdb->prefix . 'cartara_optin_survey_data';
        if (sanitize(isset($_POST['cn_survey_save'])) ) {

            if (sanitize(!isset($_POST['cn_create_survey_form'])) || !wp_verify_nonce(sanitize($_POST['cn_create_survey_form']), 'cn_survey_save_action')) {
                $notices->add( 'save_cnsyn', 'Sorry, your nonce did not verify.', 'error' );
            } else {

                // process form data
                if (sanitize($_POST['cn_name']) != '') {
                    $formname = sanitize($_POST['cn_name']);
                }
                if (sanitize($_POST['form_content']) != '') {
                    $formdata = sanitize($_POST['form_content']);
                }
                if (sanitize(isset($_POST['cn_disable'])) && sanitize($_POST['cn_disable']) != '') {
                    $cn_disable = sanitize($_POST['cn_disable']);
                } else {
                    $cn_disable = '0';
                }

                if (sanitize($_POST['cn_devices']) != '') {
                    $cn_visible = sanitize($_POST['cn_devices']);
                }

                if (intval(isset($_GET['id'])) != '') {
                    $id = intval($_GET['id']);
                    $data = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'cartara_optin_survey_data WHERE id=' . $id, ARRAY_A);
                    if (is_wp_error($data)) {
                        $error_string = $wpdb->get_error_messages();
                        $notices->add( 'save_cnsyn', 'No such form exits.'.$error_string , 'warning' );
                        wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                        exit;
                    }else{
                        $update = $wpdb->query(
                                $wpdb->prepare(
                                        "UPDATE $tablename SET formname='%s', formdata='%s', status='%s',visible='%s' WHERE id=%d", $formname, $formdata, $cn_disable, $cn_visible, $id)
                        );
                        if (is_wp_error($update)) {
                            $error_string = $wpdb->get_error_messages();
                            $notices->add( 'save_cnsyn', 'Sorry, failed to save form '.$formname.'.Error: '.$error_string, 'error' );
                            wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                            exit;
                        }
                        if ($update !== false) {
                            wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                            exit;
                        } else {
                           $notices->add( 'save_cnsyn', 'Sorry, failed to save form '.$formname.'.', 'error' );
                            wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                            exit;
                        }
                        
                        
                    }
                } else {
                    
                    $lastid = $wpdb->insert($tablename, array(
                        'formname' => $formname,
                        'formdata' => $formdata,
                        'status' => $cn_disable,
                        'visible' => $cn_visible
                            ), array('%s', '%s', '%s', '%s')
                    );
                    if ($lastid != '') {
                        wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                        exit;
                    } else {
                        $notices->add( 'save_cnsyn', 'Sorry, failed to save form '.$formname.'.', 'error' );
                        wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                        exit;
                    }
                    if (is_wp_error($lastid)) {
                        $error_string = $wpdb->get_error_messages();
                        $notices->add( 'save_cnsyn', 'Sorry, failed to save form '.$formname.'.Error: '.$error_string, 'error' );
                        wp_safe_redirect(admin_url() . "admin.php?page=cartasurvey");
                        exit;
                    }
                }
            }
        }
    }

    public function cartara_optin_survey_display_shortcode($atts) {
        ob_start();
        global $wpdb;
        if (!empty(esc_attr($atts['id']) )) {
            $form_id = esc_attr($atts['id']);
            $optin_data = $wpdb->prefix . 'cartara_optin_survey_data';
            $form_data = $wpdb->get_results("SELECT * FROM $optin_data WHERE id= '$form_id '", ARRAY_A);
            if (!empty($form_data)) {
                $form_name = $form_data[0]['formname'];
                $form_data_html = stripslashes($form_data[0]['formdata']);
                $status = $form_data[0]['status'];

                if ($status == '0') {
                    $dy_class = $form_data[0]['visible'];

                    switch ($dy_class) {
                        case 'all':
                            echo '<div class="cn_form_output_all admin_output">' . $form_data_html . '</div>';
                            break;
                        case 'mobile_only':
                            echo '<div class="cn_form_output_mobile_only">' . $form_data_html . '</div>';
                            break;
                        case 'desktop_only':
                            echo '<div class="cn_form_output_desktop_only">' . $form_data_html . '</div>';
                            break;
                    }
                } else {
                    echo "<p>This shortcode is temporary disabled</p>";
                }
            }
            if (is_wp_error($form_data)) {
                $error_string = $wpdb->get_error_messages();
                echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
            }
        }
        return ob_get_clean();
    }

    public function cartara_survey_delete_shortcode() {
        global $wpdb;
        $optin_data = $wpdb->prefix . 'cartara_optin_survey_data';
        $id = intval($_POST['id']);
        if ($id != '') {
            $delete_id = $wpdb->query('DELETE FROM  ' . $optin_data . ' WHERE id = "' . $id . '"');

            if ($delete_id > 0) {
                echo "Deleted successfully";
            } else {

                echo "Something went wrong" . $error;
            }
        }
        wp_die();
    }


    /* ------------CREATE THE TREE VIEW FOR THE ATTER SET--------------- */

    public function cartara_get_current_url() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $actual_link;
    }

    public function cartara_get_data_from_api() {

        $merchant_id = get_option('cartara_mar_api_key');
        $api_key = get_option('cartara_mar_api_signature');

        $target_url = 'https://www.secureinfossl.com/testapi/productList.html';

        if (!empty($merchant_id) && !empty($api_key)) {
            $body = array('merchantid'=>$merchant_id, 'api_signature'=>$api_key);
        	$request = new WP_Http();
    		$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    		if (isset($response_full->errors)) {
        		return array(500, 'Unknown Error');
    		}
    		$response_code = $response_full['response']['code'];
    		if ($response_code === 200) {
    			//convert xml string into an object
    			$response = $response_full['body'];
                    $xml = simplexml_load_string($response);
                    //convert into json
                    $json = json_encode($xml);
                    //convert into associative array
                    $pro_data = json_decode($json, true);
                    $res_main = $this->cartara_get_product_data($pro_data);
                    echo $res_main;
                    wp_die();
    		} else {
    			echo 'Unexpected HTTP code: ', $response_code, "\n";
    		}
        }
    }

    public function cartara_get_product_data($pro_data = array()) {
        $products_loop = '';
        $prod_live_set = $pro_data['productcount']['totalcount'];

        /* ----------CHECK THE LIVE ARRAY STATUS--- */
        if ($prod_live_set == 1) {
            $products_loop = $pro_data['products'];
        } else {
            $products_loop = $pro_data['products']['product'];
        }
        /* ----------CHECK THE LIVE ARRAY STATUS--- */

        /* ----------CHECK THE SETTINGS----------- */
        $this->cartara_update_store_settings();
        /* ----------CHECK THE CATEGORY-------- */
        $this->cartara_get_data_from_category_api();

        /* ----------INSRET PRODUCTS THE LOOP ----------- */
        foreach ($products_loop as $product_value) {

            $this->cartara_insert_product_data_cloud($product_value);
        }

        /* ----------INSRET THE LOOP END----------- */

        /* -----------GET ATTRIBUTE FORM API DATABASE AND SAVE IN ATTER TABLE-------- */

        $this->cartara_get_attribute_form_cartara_api();

        /* -------------------------------------------- */
    }

    public function cartara_insert_product_data_cloud($product) {

        global $wpdb;
        $sku = $product['sku'];
        $tablename_log = $wpdb->prefix . 'cartara_products';
        $pro_name = base64_decode($product['name']);
        $longdesc = base64_decode($product['longdesc']);
        $shortdesc = base64_decode($product['shortdesc']);

        if (empty($shortdesc)) {
            $shortdesc = ' ';
        } else {
            $shortdesc = $this->cartara_rip_tags($shortdesc);
        }

        $product_link_id = base64_decode($product['product_link_id']);

        $cat_id = $product['categoryid'];
        $term_data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'termmeta WHERE meta_value = ' . $cat_id . ' LIMIT 1', ARRAY_A);

        $tID = $term_data[0]['term_id'];
        $term = get_term($tID, 'cartara_category');
        $cat_name = $term->slug;

        $lon = $this->cartara_rip_tags(base64_decode($product['longdesc']));
		$post_id = $this->cartara_get_post_id_by_meta_key_and_value('_product_row_id_set', $product['product_row_id']);
		
		if(!$post_id) {
		
        $post_id = wp_insert_post(array(
            'post_type' => 'cartara_product',
            'post_title' => trim($pro_name),
            'post_content' => $lon,
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        ));
        } else {
        	
        	$my_post = array();
        	$my_post['ID'] = $post_id;
        	$my_post['post_title'] = trim($pro_name);
        	$my_post['post_content'] = $lon;
        	wp_update_post( $my_post );
        }
        
        

        if ($post_id != '') {
            //insert post 
            wp_set_object_terms($post_id, $cat_name, 'cartara_category', true);
            
            $product_order_meta_value = get_post_meta($post_id, 'cartara_product_order_'.$tID, true);
            if( empty($product_order_meta_value) ) {
            	update_post_meta($post_id, 'cartara_product_order_'.$tID, 1);
            }
            if ($product['product_no'] != '')
                update_post_meta($post_id, '_product_no', $product['product_no']);
            if (!empty($product['product_row_id'])) {
                $input = $product['product_row_id'];
                //Product ID:
                $name = str_pad($input, 5, "0", STR_PAD_LEFT);
                $pr_id = "PRO-" . $name;
                update_post_meta($post_id, '_product_row_id', $pr_id);
                update_post_meta($post_id, '_product_row_id_set', $input);
            }

            if ($product['product_link_id'] != '')
                update_post_meta($post_id, '_product_link_id', $product['product_link_id']);
            if (!empty($product['sku']))
                update_post_meta($post_id, '_sku', $product['sku']);
            if (!empty($product['price']))
                update_post_meta($post_id, '_price', $product['price']);
            if (!empty($product['shortdesc']))
                update_post_meta($post_id, '_shortdesc', $shortdesc);
            if (!empty($product['categoryid']))
                update_post_meta($post_id, '_categoryid', $product['categoryid']);
            if (!empty($product['regularbuylink']))
                update_post_meta($post_id, '_regularbuylink', $product['regularbuylink']);
            if (!empty($product['regularbuylink']))
                update_post_meta($post_id, '_oneclickbuylink', $product['regularbuylink']);
            if (!empty($product['imagepath']))
                update_post_meta($post_id, '_imagepath', $product['imagepath']);

            $table_log_drop = "TRUNCATE TABLE $tablename_log;";
            if ($wpdb->query($table_log_drop)) {
                $data = $wpdb->insert($tablename_log, array(
                    'sku' => trim($product['sku']),
                    'product_no' => trim($product['product_no']),
                    'product_link_id' => trim($product['product_link_id'])
                        ), array('%s', '%s', '%s')
                );


                if (is_wp_error($data)) {
                    $error_string = $wpdb->get_error_messages();
                    echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
                }
            }
        }

        if (is_wp_error($post_id)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
        if (is_wp_error($term_data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
    }

    public function cartara_get_attribute_form_cartara_api() {
        $merchant_id = get_option('cartara_mar_api_key');
        $api_key = get_option('cartara_mar_api_signature');
        $target_url = 'https://secureinfossl.com/testapi/productOption.html';
        
        if (!empty($merchant_id) && !empty($api_key)) {
            $body = array('merchantid'=>$merchant_id, 'api_signature'=>$api_key);
        	$request = new WP_Http();
    		$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    		if (isset($response_full->errors)) {
        		return array(500, 'Unknown Error');
    		}
    		$response_code = $response_full['response']['code'];
    		if ($response_code === 200) {
    			//convert xml string into an object
    			$response = $response_full['body'];
                    $xml = simplexml_load_string($response);

                    $json = json_encode($xml);

                    $attribute_data = json_decode($json, true);
                    if (!empty($attribute_data)) {
                        $this->cartara_insert_attribute_data($attribute_data);
                    }
                    wp_die();
    		} else {
    			echo 'Unexpected HTTP code: ', $response_code, "\n";
    		}
        }
     
    }

    public function cartara_insert_attribute_data($attribute_data = array()) {
        $attribute_group = $attribute_data['groups'];
        $attributes = $attribute_data['attributes']; // attributes array 
        $assignedattributes = $attribute_data['assignedattributes']; // assignedattributes array 
        $myspace_to_search = $assignedattributes['attributegroup']; // assignedattributes array sub array
        $product_attr = array();

        foreach ($attribute_group as $group_val) {
            foreach ($group_val as $group) {
                $this->cartara_insert_attribute_groups($group);
            }
        }

        foreach ($attributes as $attributes_option) {
            foreach ($attributes_option as $options) {
                $this->cartara_insert_attribute_groups_options($options);
            }
        }

        foreach ($myspace_to_search as $key => $node) {
            if ($node['productrowid'] != "") {
                $product_attr[$node['productrowid']][] = $node['attributerowid'];
            }
        }

        foreach ($product_attr as $pro_id => $pro_data) {
            $this->cartara_get_attribute_mata_vlaue($pro_id, $pro_data);
        }
    }

    /* ------SELECT attribute post ID FROM attribute group table using  product row id and meta value---------------------- */

    public function cartara_get_attribute_mata_vlaue($pro_id, $attr_data = array()) {

        global $wpdb;
        $product_meta_table = $wpdb->prefix . 'postmeta';
        $product_attribute = $wpdb->prefix . 'cartara_product_attribute';

        $product_row_id = "_product_row_id_set";

        $get_attr_post = $wpdb->get_results('SELECT * FROM  ' . $product_meta_table . ' WHERE meta_key ="' . $product_row_id . '" AND meta_value=' . $pro_id . '', ARRAY_A);
        if (!empty($get_attr_post)) {
            update_post_meta($get_attr_post[0]['post_id'], '_product_ass_attrs', $attr_data);
        }


        if (is_wp_error($get_attr_post)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
    }

    public function post_meta_attributes($assigned_val) {
        global $wpdb;
        $pro_row_id = $assigned_val['productrowid'];
        $attributerowid = $assigned_val['attributerowid'];
        $product_row_id = "_product_row_id_set";

        $product_group_table = $wpdb->prefix . 'postmeta';
        $product_attribute = $wpdb->prefix . 'cartara_product_attribute';

        $get_attr_post = $wpdb->get_results('SELECT * FROM  ' . $product_group_table . ' WHERE meta_key ="' . $product_row_id . '" AND meta_value=' . $pro_row_id . '', ARRAY_A);
        $get_attribute_name = $wpdb->get_results('SELECT * FROM  ' . $product_attribute . ' WHERE attributerowid=' . $attributerowid . '', ARRAY_A);

        foreach ($get_attr_post as $get_attr_post_id) {
            $attr_post_id = $get_attr_post_id['post_id'];
            if ($attr_post_id) {

                /* ---------NEED TO UPDATE CODE HERE FOR ASSINGMENT-------------- */
                foreach ($get_attribute_name as $get_att_name) {
                    $attribute_name = $get_att_name['attributename'];
                    /* -------FIX NEEDED---------- */
                    update_post_meta($attr_post_id, 'cartara_product_attribute', $attribute_name);
                }

                /* ---------NEED TO UPDATE CODE HERE FOR ASSINGMENT-------------- */
            }
        }


        if (is_wp_error($get_attr_post)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }



        if (is_wp_error($get_attribute_name)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
    }

    public function cartara_insert_attribute_groups($group = array()) {
        global $wpdb;
        $product_group_table = $wpdb->prefix . 'cartara_product_attribute_group';

        $result = $wpdb->replace($product_group_table, array(
            'grouprowid' => $group['grouprowid'],
            'groupname' => $group['groupname'],
            'isrequired' => $group['isrequired']
        ));


        if (is_wp_error($result)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
    }

    public function cartara_insert_attribute_groups_options($options = array()) {
        global $wpdb;
        $attribute_table = $wpdb->prefix . 'cartara_product_attribute';
        $result = $wpdb->replace($attribute_table, array(
            'attributeid' => '',
            'grouprowid' => $options['grouprowid'],
            'attributerowid' => $options ['attributerowid'],
            'attributename' => $options['attributename'],
            'weight' => $options['weight']
        ));
        if (is_wp_error($result)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
    }

    public function cartara_api_add_option() {
        $res = 0;
        if (sanitize(isset($_POST)) ) {
            update_option('cartara_mar_api_key', sanitize($_POST['merchant_id']));
            update_option('cartara_mar_api_signature', sanitize($_POST['api_signature']));
            echo $res = 1;
        }
        wp_die();
    }

    public function cartara_create_products_post_type() {
        $args = array(
            'labels' => array(
                'name' => 'Products',
                'singular_name' => 'Product',
                'all_items' => 'Products',
                'title' => 'Products',
                'menu_name' => 'Cartara Products', 'admin menu', 'plugin-textdomain',
                'name_admin_bar' => 'Products', 'add new on admin bar', 'plugin-textdomain',
                'singular_name' => 'Products',
                'edit_item' => 'Edit My Products',
                'new_item' => 'New Products',
                'view_item' => 'View Products',
                'items_archive' => 'Cartara Products Archive',
                'search_items' => 'Search Product',
                'not_found' => 'No Products found',
                'not_found_in_trash' => 'No Products found in trash',
            ),
            //FOR disallow edit post "Add"
            'capabilities' => array(
                'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            ),
            'map_meta_cap' => true,
            'supports' => array('title', 'editor', 'author', 'comments', 'excerpt'),
            'show_in_menu' => false,
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-cloud',
            'taxonomies' => array('cartara_category'),
            'show_tagcloud' => false,
            'menu_position' => 8,
            'hierarchical' => true
        );

        register_post_type('cartara_product', $args);


        register_taxonomy('cartara_category', 'cartara_product', array(
            'hierarchical' => true,
            'label' => 'Cartara Categories',
            'show_admin_column' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'singular_name' => 'Cartara_categorie',
            "rewrite" => true,
            "query_var" => true
                )
        );

        register_taxonomy('cartara_tags', 'cartara_product', array(
            'hierarchical' => true,
            'label' => 'Cartara Tags',
            'singular_name' => 'Cartara_tag',
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_menu' => false,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'cartara_tags'),
        ));
    }
    
    function cartara_menu_highlight( $parent_file ) {
         global $submenu_file, $current_screen, $pagenow;

        $taxonomy = $current_screen->taxonomy;
        if ( $taxonomy == 'cartara_category' ) {
            $parent_file = 'cartara_menu';
            if ( $pagenow == 'edit-tags.php' ) {
                $submenu_file = 'edit-tags.php?taxonomy='.$taxonomy.'&post_type=' . $current_screen->post_type;
            }
        }

        return $parent_file;
    }
    
    function cartara_category_column_header( $columns ){
    	unset($columns);
    	$columns['cb'] = 'input type="checkbox" />';
    	$columns['name'] = 'Name';
    	$columns['posts'] = 'count';
    	$columns['product_layout'] = 'Layout';
    	$columns['category_short_code'] = 'Short Code';
    	return $columns;
	}

	function cartara_category_column_content( $value, $column_name, $term_id ){
    	if ($column_name == 'category_short_code') {
        	$value .= '<div style="margin-bottom:3px;"><span id="col1'.$term_id.'">[cartara_category_product_1column_view term_id='.$term_id.']</span>  &nbsp;&nbsp;<span class="cn_controlss">
                            <a href="javascript:void(0);" class="cn_copy"  title="Copy shortcode"  onclick="copyFunction(\'col1'.$term_id.'\')">
                                <span class="dashicons dashicons-editor-code"></span>
                            </a>
                        </div>';
        	
        	$value .= '<div style="margin-bottom:3px;"><span id="col3'.$term_id.'">[cartara_category_product_3column_view term_id='.$term_id.']</span> &nbsp;&nbsp;<span class="cn_controlss">
                            <a href="javascript:void(0);" class="cn_copy"  title="Copy shortcode"  onclick="copyFunction(\'col3'.$term_id.'\')">
                                <span class="dashicons dashicons-editor-code"></span>
                            </a>
                        </div>';
    	} else if ($column_name == 'product_layout') {
           
    		$value .= '<div style="margin-top:1px;margin-bottom:3px;" class="tool_wrap">1 Column <a class="Column1ToolTip" href="#" data-toggle="tooltip" title="thgfhg" ><i class="fa fa-info-circle" aria-hidden="true" style="color:red;font-size: 14px;"></i></a><div class="tooltip_img"><img src="'.CARTARA_SYNC__PLUGIN_URL.'admin/images/one-column.jpg" /></div></div>';
    		$value .= '<div style="margin-top:1px;margin-bottom:3px;" class="tool_wrap">3 Column <a class="Column3ToolTip" href="javascript:void(0)" title="" ><i class="fa fa-info-circle" aria-hidden="true" style="color:red;font-size: 14px;"></i></a><div class="tooltip_img"><img src="'.CARTARA_SYNC__PLUGIN_URL.'admin/images/three-column-layout.jpg" /></div></div>';
    	}
    	return $value;
	}
	
	function cartara_category_description_link($actions, $post)
	{
    	if ($post->taxonomy=='cartara_category')
    	{
        	$actions['category_description'] = '<a href="#" title="'.strip_tags($post->description).'" rel="permalink">Description</a>';
    	}
    	return $actions;
	}



    public function cartara_meta_box_add() {
        add_meta_box('cartara-meta-box-id', 'Cartara Product Details', array($this, 'cartara_meta_box_price_fields'), 'cartara_product', 'normal', 'high');
        add_meta_box('cartara-meta-box-image', 'Upload Product Media', array($this, 'cartara_meta_box_image_fields'), 'cartara_product', 'normal', 'high');
        add_meta_box('cartara-meta-box-video', 'Upload Product Video', array($this, 'cartara_meta_box_video_fields'), 'cartara_product', 'normal', 'high');

        /* ----------ATTRIBUTE META SETUP PANEL----------- */
        add_meta_box('cartara_product_meta_box', '<strong>CN360 Attribute Groups</strong>', array($this, 'cartara_get_all_attribute_groups_and_value'), 'cartara_product', 'side', 'low');
        /* -ATTRIBUTE META SETUP PANEL- */
    }

    public function cartara_meta_box_price_fields() {
        global $post;
        get_post_type();
        $post_id = $post->ID;
        include_once 'partials/cartara_metabox.php';
    }

    public function cartara_meta_box_image_fields() {
        global $post;
        get_post_type();
        $post_id = $post->ID;
        include_once 'partials/cartara_metabox_for_images.php';
    }

    public function cartara_meta_box_video_fields() {
        global $post;
        get_post_type();
        $post_id = $post->ID;
        include_once 'partials/cartara_metabox_for_video.php';
    }

    public function cartara_filter_post_type_by_taxonomy() {
        global $typenow;
        $post_type = 'cartara_product'; // change to your post type
        $taxonomy = 'cartara_category'; // change to your taxonomy
        if ($typenow == $post_type) {
            $selected = isset($taxonomy) ? $taxonomy : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => __("{$info_taxonomy->label}"),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => true,
            ));
        };
    }

    public function cartara_convert_id_to_term_in_query($query) {
        global $pagenow;
        $post_type = 'cartara_product'; // change to your post type
        $taxonomy = 'cartara_category'; // change to your taxonomy
        $q_vars = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }

    /* --------------------------Cart---------- */

    public function cartara_update_store_settings() {

        //*GETTING CATEGORY LIST OF CARTARA*//
        $merchant_id = get_option('cartara_mar_api_key');
        $api_key = get_option('cartara_mar_api_signature');

        $target_url = 'https://www.secureinfossl.com/testapi/basicSettings.html';
        
        if (!empty($merchant_id) && !empty($api_key)) {
            $body = array('merchantid'=>$merchant_id, 'api_signature'=>$api_key);
        	$request = new WP_Http();
    		$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    		if (isset($response_full->errors)) {
        		return array(500, 'Unknown Error');
    		}
    		$response_code = $response_full['response']['code'];
    		if ($response_code === 200) {
    			//convert xml string into an object
    			$response = $response_full['body'];
                    $xml = simplexml_load_string($response);
                    //convert into json
                    $json = json_encode($xml);
                    //convert into associative array
                    $cat_data = json_decode($json, true);
                    update_option('cartara_store_currency', $cat_data['currency']);
                    update_option('cartara_store_currencysymbol', $cat_data['currencysymbol']);
                    update_option('cartara_store_productidprefix', $cat_data['productidprefix']);
    		} else {
    			echo 'Unexpected HTTP code: ', $response_code, "\n";
    		}
        }
		
    }

   
    public function cartara_get_data_from_category_api() {
    	$merchant_id = get_option('cartara_mar_api_key');
        $api_key = get_option('cartara_mar_api_signature');
        $target_url = 'https://www.secureinfossl.com/testapi/categoryList.html';
        $body = array('merchantid'=>$merchant_id, 'api_signature'=>$api_key);
        $request = new WP_Http();
    	$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    	if (isset($response_full->errors)) {
        	return array(500, 'Unknown Error');
    	}
    	$response_code = $response_full['response']['code'];
    	if ($response_code === 200) {
    		//convert xml string into an object
    		$response = $response_full['body'];
            $xml = simplexml_load_string($response);
            //convert into json
            $json = json_encode($xml);
            //convert into associative array
            $cat_data = json_decode($json, true);
            $this->cartara_put_category_data($cat_data);
    	} else {
    		echo 'Unexpected HTTP code: ', $response_code, "\n";
    	}
    }

    public function cartara_put_category_data($cat_data = array()) {

        foreach ($cat_data['categories'] as $category_value) {
            $count = count($category_value);
            if ($count > 0) {
                foreach ($category_value as $category) {
                    $this->cartara_insert_category_data_cartara($category);
                }
            }
        }
    }

    public function cartara_insert_category_data_cartara($category = array()) {
        global $wpdb;
        $category_id = esc_attr($category['categoryid']);
        $categroy_title = esc_html($category['categorytitle']);
        $term_data = $wpdb->get_results("SELECT * FROM `wp_terms` WHERE  name = '$categroy_title'", ARRAY_A);
        $term_name = esc_html($term_data[0]['name']);
        $count_name = count($term_data);

        if ($count_name == 0) {
            $terms = wp_insert_term(
                    $categroy_title, // the term 
                    'cartara_category', // the taxonomy
                    array(
                'slug' => $categroy_title,
                    )
            );
            /* --------Updated By saurabh sir--------- */
            if (!is_wp_error($terms)) {
                update_term_meta($terms['term_id'], $categroy_title, $category_id, FALSE);
            } elseif (is_wp_error($terms)) {
                return true;
            }
        }
        if (is_wp_error($term_data)) {
            $error_string = $wpdb->get_error_messages();
            echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }
    }

    //add option for category.
    public function cartara_cat_api_add_option() {
        $response = 0;
        if (!get_option('cartara_cat_api_key') && !get_option('cartara_cat_api_signature')) {
            if (add_option('cartara_cat_api_key', 'MER-00002') && add_option('cartara_cat_api_signature', 'VjJOU053RTNWRE1GWlFkdFV6VUhNQVl4QWpCVE1BPT0=')) {
                $response = 1;
            }
        } else {
            if (update_option('cartara_cat_api_key', 'MER-00002') || update_option('cartara_cat_api_signature', 'VjJOU053RTNWRE1GWlFkdFV6VUhNQVl4QWpCVE1BPT0=')) {
                $response = 1;
            }
        }
        echo json_encode(array('response' => $response));
        wp_die();
    }

    public function cartara_delete_data() {
        global $wpdb;
    
        $this->cartara_delete_all_attribute_data();

        echo json_encode(array('del_res' => '1'));
        wp_die();
    }

    public function cartara_delete_all_attribute_data() {
        global $wpdb;
        $attribute_table = $wpdb->prefix . 'cartara_product_attribute';
        $product_group_table = $wpdb->prefix . 'cartara_product_attribute_group';
        $delete1 = $wpdb->query("TRUNCATE TABLE $attribute_table");
        $delete2 = $wpdb->query("TRUNCATE TABLE $product_group_table");
    }

    public function cartara_gall_save_post($post_id, $post) {
        global $post;
        $gallery_array = array();
        $uploadfiles = sanitize(isset($_FILES['cartara_gallery'])) ? sanitize($_FILES['cartara_gallery']) : '';
        $cncustompost = sanitize(isset($_POST['cncustompost'])) ? sanitize($_POST['cncustompost']) : '';

        if (sanitize(isset($_POST['cncustompost'])) ) {
            update_post_meta($post_id, '_shortdesc', $cncustompost);
        }
        if(sanitize(isset($_POST['productImage'])) ) {
        	$product_images = sanitize($_POST['productImage']);
        	foreach($product_images AS $attach_id) {
        		array_push($gallery_array, $attach_id);
        	}
        	if (!empty($gallery_array) && count($gallery_array) > 0) {

                $gallery_img = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);
                if (!empty($gallery_img)) {
                    
                    $result = array_merge($gallery_img, $gallery_array);
                    update_post_meta($post_id, 'cartara_attachment_gallery_key', $result);
                } else {
                    update_post_meta($post_id, 'cartara_attachment_gallery_key', $gallery_array);
                }
            }
        }

        $_product_row_id_set = get_post_meta($post_id, '_product_row_id_set', true);

        $gallery_img = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);
        if (!empty($gallery_img[0])) {
            $set_primary_image = wp_get_attachment_url($gallery_img[0]);
            $this->cartara_set_primary_image($set_primary_image, $_product_row_id_set);
        }

        //*****************update video into postmeta*****************/
        if (!empty(sanitize($_POST['cartara_videos'])))  {
            $video_url = sanitize($_POST['cartara_videos']);
            $cartara_videos = get_post_meta($post_id, 'cartara_videos_key', true);
            if (!empty($cartara_videos)) {
                foreach ($video_url as $vdurl) {
                    if (!empty($vdurl))
                        array_push($cartara_videos, trim($vdurl));
                }
                update_post_meta($post_id, 'cartara_videos_key', $cartara_videos);
            } else {
                update_post_meta($post_id, 'cartara_videos_key', $video_url);
            }
        }
        if (sanitize(isset($_POST['video_short_series'])) && !empty(sanitize($_POST['video_short_series'])) ) {
            $video_short_series = sanitize($_POST['video_short_series']);
            $v_series = explode(',', $video_short_series);
            if (count($v_series) > 0) {
                update_post_meta($post_id, 'cartara_videos_short_series', $v_series);
            }
        }
        /* ------------------------------------------------------------ */
    }

    public function cartara_set_primary_image($primary_image_url, $pwc_product_row_id) {
        // GETTING CATEGORY LIST OF CARTARA
        $merchant_id = get_option('cartara_mar_api_key');
        $api_key = get_option('cartara_mar_api_signature');
        $target_url = 'https://secureinfossl.com/testapi/addProductImageFromWP.html';
          if (!empty($merchant_id) && !empty($api_key)) {
            $body = array('merchantid'=>$merchant_id, 'api_signature'=>$api_key);
        	$request = new WP_Http();
    		$response_full = $request->request($target_url, array('method' => 'POST', 'body' => $body));
    		if (isset($response_full->errors)) {
        		return array(500, 'Unknown Error');
    		}
    		$response_code = $response_full['response']['code'];
    		if ($response_code === 200) {
    			//convert xml string into an object
    			$response = $response_full['body'];
                    $xml = simplexml_load_string($response);
                    //convert into json
                    $json = json_encode($xml);
                    //convert into associative array
                    $pro_data = json_decode($json, true);
                    $res_main = $this->cartara_get_product_data($pro_data);
                    echo $res_main;
                    wp_die();
    		} else {
    			echo 'Unexpected HTTP code: ', $response_code, "\n";
    		}
        }

       
    }

    public function cartara_add_edit_form_multipart_encoding() {
        echo 'enctype="multipart/form-data"';
    }

    public function cartara_delete_attachment() {

        $post_id = intval($_POST['post_id']);
        $id = intval($_POST['id']);
        $img_arr = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);

        $shotred_series = get_post_meta($post_id, '_shotred_series', true);
        $srt_arrya = explode(',', $shotred_series);


        $key1 = array_search($id, $img_arr);
        unset($img_arr[$key1]);

        $key2 = array_search($id, $srt_arrya);
        unset($srt_arrya[$key2]);

        if (update_post_meta($post_id, 'cartara_attachment_gallery_key', $img_arr)) {
            update_post_meta($post_id, '_shotred_series', $srt_arrya);

            wp_delete_attachment($id, true);
            //delete image from folder

            $c = get_post_meta($post_id, 'cartara_attachment_gallery_key', true);
            if (!empty($c)) {
                echo '1';
            } else {
                echo '0';
            }
        }
        wp_die();
    }

    /* -----------------video upload from here --------------------- */

    public function cartara_delete_video_attachment() {

        $post_id = intval($_POST['post_id']);
        $url = esc_url($_POST['url']);

        $video_urls = get_post_meta($post_id, 'cartara_videos_key', true);
        $cartara_videos_short_series = get_post_meta($post_id, 'cartara_videos_short_series', true);
        $vd_exp = explode(',', $cartara_videos_short_series);

        $key1 = array_search($url, $video_urls);
        $key2 = array_search($url, $vd_exp);

        unset($video_urls[$key1]);
        unset($vd_exp[$key2]);


        if (update_post_meta($post_id, 'cartara_videos_key', $video_urls)) {
            update_post_meta($post_id, 'cartara_videos_short_series', $vd_exp);
            $c = get_post_meta($post_id, 'cartara_videos_key', true);
            if (!empty($c)) {
                echo '1';
            } else {
                echo '0';
            }
        }
        wp_die();
    }

    public function cartara_rip_tags($str) {
        $string = strip_tags($str);
        $string = html_entity_decode($string);
        // ----- remove HTML TAGs ----- 
        $string = preg_replace('/<[^>]*>/', ' ', $string);
        // ----- remove control characters ----- 
        $string = str_replace("\r", '', $string);    // --- replace with empty space
        $string = str_replace("\n", ' ', $string);   // --- replace with space
        $string = str_replace("\t", ' ', $string);   // --- replace with space
        // ----- remove multiple spaces ----- 
        $string = trim(preg_replace('/ {2,}/', ' ', $string));
        return $string;
    }
   
    public function cartara_update_postmeta_video_short() {
        $r = 0;
        if (sanitize(isset($_POST))) {
            $video_id = sanitize($_POST['meta_value']);
            $post_id = intval($_POST['post_id']);
            if (update_post_meta($post_id, 'cartara_videos_key', $video_id)) {
                $r = 1;
            }
        }
        echo $r;
        wp_die();
    }
    public function cartara_update_postmeta_image_short() {
        $r = 0;
        if (sanitize(isset($_POST))) {
            $img_id = sanitize($_POST['meta_value']);
            $post_id = intval($_POST['post_id']);
            if (update_post_meta($post_id, 'cartara_attachment_gallery_key', $img_id)) {
                $r = 1;
            }
        }
        echo $r;
        wp_die();
    }
    
    public function cartara_disable_editors($post) {
    	global $submenu_file, $current_screen, $pagenow, $_wp_post_type_features;
    	
    	$post_type = "cartara_product";
     	$feature = "editor";
    	if($pagenow == 'post.php' && $post->post_type == $post_type) {
         	if ( isset($_wp_post_type_features[$post_type][$feature])) {
         			
     			remove_post_type_support($post_type, $feature);
     			echo '<div class="welcome-panel">
                        <div class="welcome-panel-content">
                            <div class="tab-content">
                     				<div class="tab-pane fade in active">
                     					<div class="text-box-row">
                                        	<div><label>Long Description:</label></div> ';
                     						echo nl2br($post->post_content);
                     				echo '</div>
                                	</div>
                                	 </div>
                        </div>
                    </div>';
     			}
    	}
    	
    }
    
    function cartara_enqueue_admin()
	{
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
	}
	
	function cartara_get_post_id_by_meta_key_and_value($key, $value) {
		global $wpdb;
		$meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$wpdb->escape($key)."' AND meta_value='".$wpdb->escape($value)."'");
		if (is_array($meta) && !empty($meta) && isset($meta[0])) {
			$meta = $meta[0];
		}		
		if (is_object($meta)) {
			return $meta->post_id;
		}
		else {
			return false;
		}
	}
	
	
	function set_custom_cartara_product_columns($columns) {
    	unset( $columns['author'] );
    	unset( $columns['comments'] );
    	if(sanitize(isset($_GET['cartara_category'])) && sanitize($_GET['cartara_category'])!='')
    		$columns['product_order'] = __( 'Product Order');
    	

    	return $columns;
	}
	
	

	function manage_cartara_product_columns( $column ) {
		global $post;
		if($column == 'product_order') {
			$catObj = get_term_by( 'slug', sanitize($_GET['cartara_category']), 'cartara_category');
		 	$catID = $catObj->term_id;
			$meta_value = get_post_meta($post->ID, 'cartara_product_order_'.$catID, true);
			echo '<input type="text" size="10" name="textOrd'.$post->ID.'" onkeypress="return event.charCode === 0 || /\d/.test(String.fromCharCode(event.charCode));" maxlength="3" value="'.$meta_value.'">';
		}
	}
		
	function custom_js_to_head() {
	
		if(sanitize(isset($_GET['cartara_category'])) && sanitize($_GET['cartara_category'])!='') {
		
			$catObj = get_term_by( 'slug', sanitize($_GET['cartara_category']), 'cartara_category');
		 	$catName = $catObj->name;
		 	$catID = $catObj->term_id;
    ?>
    <script>
    	
    	jQuery(function(){
        	jQuery("body.post-type-cartara_product .tablenav-pages").prepend('<div style="float:left;padding-right:7px"><input type="button" class="button button-primary button-large" value="Save Order" id="save-order" onclick="doSaveOrder()" style="height: 28px !important;line-height: 25px;box-shadow: 0 0 0 !important;"/><div>');
        	
        	jQuery("body.post-type-cartara_product .wrap h1").append('&nbsp;in Category: <?=$catName?>');
        	jQuery("#posts-filter").append('<input type="hidden" name="cn_category_id" class="post_status_page" value="<?=$catID?>">');
    	});
    </script>
    <?php
    	} else if(sanitize(isset($_GET['taxonomy'])) && sanitize($_GET['taxonomy']) =='cartara_category') {
    ?>

    <script>
   jQuery(function($){

      $(".Column1ToolTip").tooltip();
      $(".Column3ToolTip").tooltip();
    });
    </script>
    	
    <?php	
    	}
	}
	
	
	
	function cn_save_order_action_hook_function() {
    	// do something
    	
    	if(intval(isset($_POST['cn_category_id'])) && intval($_POST['cn_category_id'])!=''){
    		$posts = get_posts(
    			array(
        			'posts_per_page' => -1,
        			'post_type' => 'cartara_product',
        			'tax_query' => array(
            			array(
                			'taxonomy' => 'cartara_category',
                			'field' => 'term_id',
                			'terms' => intval($_POST['cn_category_id']),
            			)
        			)
    			)
			);
			foreach ($posts as $value) {
        		$post_id = $value->ID;
        	
   					update_post_meta($post_id, 'cartara_product_order_'.intval($_POST['cn_category_id']), sanitize($_POST['textOrd'.$post_id]));
				
        	}
    	}
    	wp_safe_redirect( sanitize($_POST['_wp_http_referer'] ));
    	//echo 'saved'; exit();
	}
	
	function cn_save_post_callback($post_id){
    	global $post;
    	    if ('cartara_product' == get_post_type()){
    		$arrCategories = sanitize($_POST['tax_input']['cartara_category']);         
    		foreach($arrCategories AS $catId) {
    			if($catId > 0) {
        			$product_order_meta_value = get_post_meta($post_id, 'cartara_product_order_'.$catId, true);
            		if( empty($product_order_meta_value) ) {
            			update_post_meta($post_id, 'cartara_product_order_'.$catId, 1);
            		}
            	}
            }
    	}
    	wp_cache_flush();
	}

    function cn_save_survey_post_callback($post_id){
        global $post; 
        if ('cartara_product' == get_post_type()){
            $arrCategories = sanitize($_POST['tax_input']['cartara_category']); 
            foreach($arrCategories AS $catId) {
                if($catId > 0) {
                    $product_order_meta_value = get_post_meta($post_id, 'cartara_product_order_'.$catId, true);
                    if( empty($product_order_meta_value) ) {
                        update_post_meta($post_id, 'cartara_product_order_'.$catId, 1);
                    }
                }
            }
        }
        wp_cache_flush();
    }
	
	
}


?>