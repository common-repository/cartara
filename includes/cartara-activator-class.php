<?php
/**
 * Fired during plugin activation
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 *
 * @package    cartara
 * @subpackage cartara/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cartara
 * @subpackage Cartara/includes
 * @author     vll <vll@gmail.com>
 */
class Cartara_sync_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        ob_clean();
        global $wpdb;
      
        /* --------------------Create Table-------------------- */
        $version = get_option('CARTARA_SYNC_VERSION', '1.0');

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'cartara_products';

        $sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    sku varchar(10),
                    product_no smallint(5),
                    product_link_id varchar(100),
                    PRIMARY KEY (id)
            ) $charset_collate;";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        /* -------------Create Cart Table------------------- */
        $cart_table = $wpdb->prefix . 'cartara_incart_products';
        $cart_sql = "CREATE TABLE $cart_table (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    post_id mediumint(9) NOT NULL,
                    product_data longtext NOT NULL,
                    user_ip varchar(255) NOT NULL,
                    PRIMARY KEY (id)
            ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($cart_sql);

        /** ******************/
        $form_table = $wpdb->prefix . 'cartara_optin_form_data';
        $form_sql = "CREATE TABLE  $form_table (
                     id mediumint(255) NOT NULL AUTO_INCREMENT,
                     formname varchar(255) NOT NULL,
                     formdata longtext NOT NULL,
                     status varchar(255) NULL,
                     visible varchar(255) NOT NULL,
            PRIMARY KEY  (id)
            ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($form_sql);

        /** ******************/
        $survey_table = $wpdb->prefix . 'cartara_optin_survey_data';
        $survey_sql = "CREATE TABLE  $survey_table (
                     id mediumint(255) NOT NULL AUTO_INCREMENT,
                     formname varchar(255) NOT NULL,
                     formdata longtext NOT NULL,
                     status varchar(255) NULL,
                     visible varchar(255) NOT NULL,
            PRIMARY KEY  (id)
            ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($survey_sql);
/************************************/

        /* ----------------ATTER SET TABLE---------- */
        $attribute_table = $wpdb->prefix . 'cartara_product_attribute';
        $product_group_table = $wpdb->prefix . 'cartara_product_attribute_group';

        $attribute_qry = "CREATE TABLE $attribute_table (
                    attributeid mediumint(9) NOT NULL AUTO_INCREMENT,
                    grouprowid mediumint(255) NOT NULL,
                    attributerowid mediumint(255) NOT NULL,
                    attributename varchar(100) NOT NULL,
                    weight mediumint(255) NOT NULL,
                    
                    PRIMARY KEY  (attributeid)
            ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($attribute_qry);


        $product_group_qry = "CREATE TABLE $product_group_table (
                    grouprowid mediumint(255) NOT NULL,
                    groupname varchar(255) NOT NULL,
                    isrequired varchar(255) NOT NULL,
                  
                    PRIMARY KEY(grouprowid)
            ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($product_group_qry);



        /* ---------------Version upgrade-------------- */
        if (version_compare($version, '2.0') < 0) {
            $sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    sku varchar(10) NOT NULL,
                    product_no smallint(5) NOT NULL,
                    product_link_id varchar(100) NOT NULL,
                    UNIQUE KEY sku (sku),
                    PRIMARY KEY (id)
                    ) $charset_collate;";

            dbDelta($sql);

            $cart_sql = "CREATE TABLE $cart_table (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    post_id mediumint(9) NOT NULL,
                    product_data longtext NOT NULL,
                    user_ip varchar(255) NOT NULL,
                    PRIMARY KEY (id)
            ) $charset_collate;";

            dbDelta($cart_sql);


            $product_group_qrr = "CREATE TABLE $product_group_table (
                    grouprowid mediumint(255) NOT NULL,
                    groupname varchar(255) NOT NULL,
                    isrequired varchar(255) NOT NULL,
                    PRIMARY KEY(grouprowid)
                    ) $charset_collate;";
            dbDelta($product_group_qrr);



            $attribute_qrr = "CREATE TABLE $attribute_table (
                    attributeid mediumint(9) NOT NULL AUTO_INCREMENT,
                    grouprowid mediumint(255) NOT NULL,
                    attributerowid mediumint(255) NOT NULL,groupname varchar(255) NOT NULL,
                    attributename varchar(100) NOT NULL,
                    weight mediumint(255) NOT NULL,
                    PRIMARY KEY  (attributeid)
                    ) $charset_collate;";
            dbDelta($attribute_qrr);


            $form_sql = "CREATE TABLE  $form_table (
                     id mediumint(255) NOT NULL AUTO_INCREMENT,
                     formname varchar(255) NOT NULL,
                     formdata longtext NOT NULL,
                     status varchar(255) NULL,
                     visible varchar(255) NOT NULL,
            
                    
                    PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($form_sql);

            $survey_sql = "CREATE TABLE  $survey_table (
                     id mediumint(255) NOT NULL AUTO_INCREMENT,
                     formname varchar(255) NOT NULL,
                     formdata longtext NOT NULL,
                     status varchar(255) NULL,
                     visible varchar(255) NOT NULL,
            
                    
                    PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($survey_sql);


            update_option('CARTARA_SYNC_VERSION', '2.0');
        }
    }
}?>