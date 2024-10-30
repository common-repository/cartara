<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 *
 * @package    cartara
 * @subpackage cartara/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    cartara
 * @subpackage cartara/includes
 * @author     http://techexeitsolutions.com
 */
class Cartara {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Cartara_sync_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('CARTARA_SYNC_VERSION')) {
            $this->version = CARTARA_SYNC_VERSION;
        } else {
            $this->version = '12.0.0';
        }
        $this->plugin_name = 'cartara';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - cartara_Loader. Orchestrates the hooks of the plugin.
     * - cartara_i18n. Defines internationalization functionality.
     * - cartara_Admin. Defines all hooks for the admin area.
     * - cartara_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cartara-loader-class.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cartara-i18n-class.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/cartara-admin-class.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cartara-admin-notices.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/cartara-public-class.php';

        $this->loader = new Cartara_sync_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Cartara_sync_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Cartara_sync_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new cartara_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'cartara_enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'cartara_enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'cartara_personalised_cartara_menu');
        $this->loader->add_action('init', $plugin_admin, 'cartara_create_products_post_type');
        $this->loader->add_action('admin', $plugin_admin, 'cartara_add_new_product_page');
        $this->loader->add_action('wp_ajax_cartara_api_add_option', $plugin_admin, 'cartara_api_add_option');
        $this->loader->add_action('wp_ajax_nopriv_cartara_api_add_option', $plugin_admin, 'cartara_api_add_option');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'cartara_meta_box_add');
        $this->loader->add_action('wp_ajax_cartara_get_data_from_api', $plugin_admin, 'cartara_get_data_from_api');
        $this->loader->add_action('wp_ajax_nopriv_cartara_get_data_from_api', $plugin_admin, 'cartara_get_data_from_api');
        $this->loader->add_action('wp_ajax_cartara_delete_data', $plugin_admin, 'cartara_delete_data');
        $this->loader->add_action('wp_ajax_nopriv_cartara_delete_data', $plugin_admin, 'cartara_delete_data');
        $this->loader->add_action('wp_ajax_cartara_delete_video_attachment', $plugin_admin, 'cartara_delete_video_attachment');
        $this->loader->add_action('wp_ajax_nopriv_cartara_delete_video_attachment', $plugin_admin, 'cartara_delete_video_attachment');
        $this->loader->add_action('wp_ajax_cartara_delete_attachment', $plugin_admin, 'cartara_delete_attachment');
        $this->loader->add_action('wp_ajax_nopriv_cartara_delete_attachment', $plugin_admin, 'cartara_delete_attachment');
        $this->loader->add_action('restrict_manage_posts', $plugin_admin, 'cartara_filter_post_type_by_taxonomy');
        $this->loader->add_filter('parse_query', $plugin_admin, 'cartara_convert_id_to_term_in_query');
        
        //santanu
        $this->loader->add_action('parent_file', $plugin_admin, 'cartara_menu_highlight');        
        $this->loader->add_filter( "manage_edit-cartara_category_columns", $plugin_admin, 'cartara_category_column_header');
        $this->loader->add_filter( "manage_edit-cartara_category_columns", $plugin_admin, 'cartara_category_column_header');
        $this->loader->add_filter( "manage_edit-cartara_category_columns", $plugin_admin, 'cartara_category_column_header');
        $this->loader->add_filter( "manage_edit-cartara_category_columns", $plugin_admin, 'cartara_category_column_header');
        $this->loader->add_filter( "manage_edit-cartara_category_columns", $plugin_admin, 'cartara_category_column_header');
        $this->loader->add_filter( "manage_cartara_category_custom_column", $plugin_admin, 'cartara_category_column_content', 10, 3);
        $this->loader->add_action('edit_form_after_title', $plugin_admin, 'cartara_disable_editors');
        $this->loader->add_filter('cartara_category_row_actions', $plugin_admin, 'cartara_category_description_link', 10, 2);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'cartara_enqueue_admin');
        $this->loader->add_filter( "manage_cartara_product_posts_columns", $plugin_admin, 'set_custom_cartara_product_columns');
        $this->loader->add_action( 'manage_cartara_product_posts_custom_column', $plugin_admin, 'manage_cartara_product_columns' );
        $this->loader->add_action('admin_head', $plugin_admin, 'custom_js_to_head');
        $this->loader->add_action( 'admin_post_cn_save_order', $plugin_admin, 'cn_save_order_action_hook_function' );

        /* ----------------------------New cat function for cat input---------------------------- */
        $this->loader->add_action('wp_ajax_cartara_get_data_from_category_api', $plugin_admin, 'cartara_get_data_from_category_api');
        $this->loader->add_action('wp_ajax_nopriv_cartara_get_data_from_category_api', $plugin_admin, 'cartara_get_data_from_category_api');
        
        $this->loader->add_filter('admin', $plugin_admin, 'cartara_cat_api_add_option');
      
        /* ----------------------------New cat function for cat input---------------------------- */
        $this->loader->add_action('save_post', $plugin_admin, 'cartara_gall_save_post', 10, 2);
        $this->loader->add_action('post_edit_form_tag', $plugin_admin, 'cartara_add_edit_form_multipart_encoding');
        
        /* ----------------------------------SEND VIDEO REARANGEMENT END------------------------- */
        $this->loader->add_action('wp_ajax_cartara_update_postmeta_video_short', $plugin_admin, 'cartara_update_postmeta_video_short');
        $this->loader->add_action('wp_ajax_nopriv_cartara_update_postmeta_video_short', $plugin_admin, 'cartara_update_postmeta_video_short');
        /* ----------------------------------SEND IMAGE REARANGEMENT END------------------------- */
        $this->loader->add_action('wp_ajax_cartara_update_postmeta_image_short', $plugin_admin, 'cartara_update_postmeta_image_short');
        $this->loader->add_action('wp_ajax_nopriv_cartara_update_postmeta_image_short', $plugin_admin, 'cartara_update_postmeta_image_short');
        /* -----------delete shortcode form data------------------------------ */
        $this->loader->add_action('wp_ajax_cartara_delete_shortcode', $plugin_admin, 'cartara_delete_shortcode');
        $this->loader->add_action('wp_ajax_nopriv_cartara_delete_shortcode', $plugin_admin, 'cartara_delete_shortcode');

        /* ---------- delete shortcode form data------------------ */
        /* -----------delete shortcode survey data------------------------------ */
        $this->loader->add_action('wp_ajax_cartara_survey_delete_shortcode', $plugin_admin, 'cartara_survey_delete_shortcode');
        $this->loader->add_action('wp_ajax_nopriv_cartara_survey_delete_shortcode', $plugin_admin, 'cartara_survey_delete_shortcode');
        /* -----------delete shortcode survey data------------------------------ */

        /* -------------- Form shortcode function-------------- */
        add_shortcode('cartara_opt_form', array($plugin_admin, 'cartara_optin_display_shortcode'));
        $this->loader->add_action('admin_init', $plugin_admin, 'cartara_cr_form_save');
        $this->loader->add_action('save_post', $plugin_admin, 'cn_save_post_callback');

        /* -------------- Survey Form shortcode function-------------- */
        add_shortcode('cartara_survey_form', array($plugin_admin, 'cartara_optin_survey_display_shortcode'));
        $this->loader->add_action('admin_init', $plugin_admin, 'cartara_cr_survey_save');
       $this->loader->add_action('save_post', $plugin_admin, 'cn_save_survey_post_callback');
		
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new cartara_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'cartara_enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'cartara_enqueue_scripts');
        /* --------------------------Shortcode for display -------------- */
        add_shortcode('cartara_products_showcase', array($plugin_public, 'cartara_products_showcase'));
        add_shortcode('cartara_product_grid_view', array($plugin_public, 'cartara_page_display'));
		add_shortcode('cartara_product_single_view', array($plugin_public, 'cartara_product_single_view'));
		add_shortcode('cartara_category_product_1column_view', array($plugin_public, 'cartara_product_1column_view'));
		add_shortcode('cartara_category_product_3column_view', array($plugin_public, 'cartara_product_3column_view'));

        $this->loader->add_action('wp_ajax_cartara_products_showcase', $plugin_public, 'cartara_products_showcase');
        $this->loader->add_action('wp_ajax_nopriv_cartara_products_showcase', $plugin_public, 'cartara_products_showcase');
        
        /* --------------------------Shortcode for display -------------- */

        $this->loader->add_filter('single_template', $plugin_public, 'cartara_single_product', 999, 2);
        $this->loader->add_filter('template_include', $plugin_public, 'cartara_product_categories');

        /* ----------------------------------add to cart ------------------------- */
        $this->loader->add_action('wp_ajax_cartara_add_to_cartproduct', $plugin_public, 'cartara_add_to_cartproduct');
        $this->loader->add_action('wp_ajax_nopriv_cartara_add_to_cartproduct', $plugin_public, 'cartara_add_to_cartproduct');

        /* ----------------------------------Create User cart ------------------------- */
        $this->loader->add_action('wp_ajax_cartara_incart_data_byip', $plugin_public, 'cartara_incart_data_byip');
        $this->loader->add_action('wp_ajax_nopriv_cartara_incart_data_byip', $plugin_public, 'cartara_incart_data_byip');
        /* ----------------------------------Delete cart item------------------------- */
        $this->loader->add_action('wp_ajax_cartara_incart_item_remove', $plugin_public, 'cartara_incart_item_remove');
        $this->loader->add_action('wp_ajax_nopriv_cartara_incart_item_remove', $plugin_public, 'cartara_incart_item_remove');
        /* ----------------------------------Delete cart item------------------------- */
        $this->loader->add_action('wp_ajax_cartara_sl_getfront_ip', $plugin_public, 'cartara_sl_getfront_ip');
        $this->loader->add_action('wp_ajax_nopriv_cartara_sl_getfront_ip', $plugin_public, 'cartara_sl_getfront_ip');
        /* -------------------------cloudenet_add_popcart--------------- */

        /* ----------------------------------SEND ITEM COUNT FRONT END------------------------- */
        $this->loader->add_action('wp_ajax_cartara_get_cart_tip_count', $plugin_public, 'cartara_get_cart_tip_count');
        $this->loader->add_action('wp_ajax_nopriv_cartara_get_cart_tip_count', $plugin_public, 'cartara_get_cart_tip_count');

        /* ----------------------------------SEND ITEM COUNT FRONT END------------------------- */
        $this->loader->add_action('wp_ajax_cartara_procedtochekout', $plugin_public, 'cartara_procedtochekout');
        $this->loader->add_action('wp_ajax_nopriv_cartara_procedtochekout', $plugin_public, 'cartara_procedtochekout');

        /* -------------------------cloudenet_add_popcart--------------- */
        $this->loader->add_action('wp_footer', $plugin_public, 'cartara_add_popcart', 100);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run(
    ) {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Cartara_sync_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}?>