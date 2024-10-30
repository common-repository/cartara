<?php
/* ------------------------------------------------------------------------------
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin  Cartara,India.
 * ******************************************************************************
 * @link              https://www.cartara.pro/
 * @since             1.0.0
 * @package           cartara
 * ******************************************************************************
 * @wordpress-plugin
 * Plugin Name:       Cartara
 * Plugin URI:        https://www.cartara.pro/
 * Description:       Instant sales automation with any theme and the proven strength of the Cartara small business sales automation platform. Easy to install, “copy / paste” simple to use anywhere on your site, and more powerful than anything else available today. </b>
 * Version:           1.0.3
 * Author:            GARY JEZORSKI 
 * Author URI:        https://www.gary-jezorski.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cartara
 * Domain Path:       /languages
 * ******************************************************************************
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('CARTARA_SYNC_VERSION', '1.0.0');
define('CARTARA_SYNC__MINIMUM_WP_VERSION', '4.0');
define('CARTARA_SYNC__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CARTARA_SYNC__PLUGIN_URL', plugin_dir_url(__FILE__));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/cartara-activator-class.php
 */
if (!function_exists('activate_cartara_sync')) {
    function activate_cartara_sync() {
        require_once( CARTARA_SYNC__PLUGIN_DIR . 'includes/cartara-activator-class.php' );
        Cartara_sync_Activator::activate();
    }
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/cartara-deactivator-class.php
 */
if (!function_exists('deactivate_cartara_sync')) {
    function deactivate_cartara_sync() {
        require_once( CARTARA_SYNC__PLUGIN_DIR . 'includes/cartara-deactivator-class.php');
        Cartara_sync_Deactivator::deactivate();
    }
}
register_activation_hook(__FILE__, 'activate_cartara_sync');
register_deactivation_hook(__FILE__, 'deactivate_cartara_sync');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once( CARTARA_SYNC__PLUGIN_DIR . 'includes/cartara-class.php');

require_once( CARTARA_SYNC__PLUGIN_DIR . 'includes/cartara-list-custom-taxonomy-widget.php');
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
/* * Add wp List table support */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if (!function_exists('run_cartara_sync')) {
    function run_cartara_sync() {
        $plugin = new Cartara();
        $plugin->run();
        add_filter('post_updated_messages', 'code_book_updated_messages');
        /**
         * Book update messages.
         *
         * See /wp-admin/edit-form-advanced.php
         *
         * @param array $messages Existing post update messages.
         *
         * @return array Amended post update messages with new CPT update messages.
         */
      
         function sanitize ($value) {
            // sanitize array or string values
            if (is_array($value)) {
                array_walk_recursive($value, 'sanitize_value');
            }
            else {
                sanitize_value($value);
            }

            return $value;
        }

        function sanitize_value (&$value) {
            $value = trim($value);
        }
        
        function code_book_updated_messages($messages) {
            $post = get_post();
            $post_type = get_post_type($post);
            $post_type_object = get_post_type_object($post_type);

            $messages['cartara_product'] = array(
                0 => '', // Unused. Messages start at index 1.
                1 => __('Product has been updated successfully.', 'CARTARA_SYNC'),
                2 => __('Custom field has been updated successfully.', 'CARTARA_SYNC'),
                3 => __('Custom field has been deleted successfully.', 'CARTARA_SYNC'),
                4 => __('Product has been updated successfully.', 'CARTARA_SYNC'),
                /* translators: %s: date and time of the revision */
                5 => sanitize(isset($_GET['revision'])) ? sprintf(__('Book restored to revision from %s', 'CARTARA_SYNC'), wp_post_revision_title((int) sanitize($_GET['revision']), false)) : false,
                6 => __('Product has been published.', 'CARTARA_SYNC'),
                7 => __('Product has been saved.', 'CARTARA_SYNC'),
                8 => __('Product has been submitted.', 'CARTARA_SYNC'),
                9 => sprintf(
                        __('Product scheduled for: <strong>%1$s</strong>.', 'CARTARA_SYNC'),
                        // translators: Publish box date format, see http://php.net/date
                        date_i18n(__('M j, Y @ G:i', 'CARTARA_SYNC'), strtotime($post->post_date))
                ),
                10 => __('Product draft has been updated successfully.', 'CARTARA_SYNC')
            );
            if ($post_type_object->publicly_queryable && 'cartara_product' === $post_type) {
                $permalink = get_permalink($post->ID);

                $view_link = sprintf(' <a href="%s">%s</a>', esc_url($permalink), __('View product', 'CARTARA_SYNC'));
                $messages[$post_type][1] .= $view_link;
                $messages[$post_type][6] .= $view_link;
                $messages[$post_type][9] .= $view_link;

                $preview_permalink = add_query_arg('preview', 'true', esc_url($permalink));
                $preview_link = sprintf(' <a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), __('Preview product', 'CARTARA_SYNC'));
                $messages[$post_type][8] .= $preview_link;
                $messages[$post_type][10] .= $preview_link;
            }
            return $messages;
        }
    }
    run_cartara_sync();
}
?>