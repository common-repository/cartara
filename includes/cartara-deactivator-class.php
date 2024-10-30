<?php
/**
 * Fired during plugin deactivation
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 *
 * @package    cartara
 * @subpackage cartara/includes
 */
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    cartara
 * @subpackage cartara/includes
 * @author     http://techexeitsolutions.com
 */
class Cartara_sync_Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'cartara_products';
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
        /* --------------- */
        $attribute_table = $wpdb->prefix . 'cartara_product_attribute';
        $sql2 = "DROP TABLE IF EXISTS $attribute_table;";
        $wpdb->query($sql2);
        /* --------------- */
        $product_group_table = $wpdb->prefix . 'cartara_product_attribute_group';
        $sql3 = "DROP TABLE IF EXISTS $product_group_table;";
        $wpdb->query($sql3);
        
        /* --------------- */
        $form_table = $wpdb->prefix . 'cartara_optin_form_data';
        $sql4 = "DROP TABLE IF EXISTS $form_table;";
        $wpdb->query($sql4);

        /* --------------- */
        $survey_table = $wpdb->prefix . 'cartara_optin_survey_data';
        $sql5 = "DROP TABLE IF EXISTS $survey_table;";
        $wpdb->query($sql5);


        /* --------------- */
        $incart_table = $wpdb->prefix . 'cartara_incart_products';
        $sql6 = "DROP TABLE IF EXISTS $incart_table;";
        $wpdb->query($sql6);

    }
}?>