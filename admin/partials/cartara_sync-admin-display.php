<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://techexeitsolutions.com
 * @since      1.0.0
 *
 * @package    cartara
 * @subpackage cartara/admin/partials
 */
?>
<section>
    <div class="container">
        <div class="wrap col-sm-9">
            <h1>Welcome to Cartara</h1>
            <p>Congratulations! You are using Cartara for Managing ecommerce in your wordpress site</p>
            <a href="<?php echo admin_url();  ?>admin.php?page=api_setting">
                <button type="button" class="button button-primary">Setting</button></a>
            <button class="button btn btn-twitter btn-sm"><i class="fa fa-twitter"></i> Tweet</button>
        </div>
        <div class="wrap col-sm-2">
            <p>Version: 1.0.0</p>
        </div>
        <div class="wrap col-sm-12">
            <div class="welcome-panel">
                <div class="welcome-panel-content">
                    <div class="text-box-row">
                        <div class="col-sm-4 s_grid">
                            <img src="<?php echo CARTARA_SYNC__PLUGIN_URL; ?>admin/images/word_icon1.png">
                            <h4><a href="<?php echo admin_url(); ?>edit.php?post_type=cartara_product">Products</a></h4>
                            <p style="text-align: center;">Manage product listings and images.</p>
                        </div>
                        <div class="col-sm-4 s_grid">
                            <img src="<?php echo CARTARA_SYNC__PLUGIN_URL; ?>admin/images/word_icon2.png"/>
                            <h4><a href="https://www.cartara.pro/" target="_blank">Support</a></h4>
                            <h5 style="text-align: center;">
                               <p style="text-align: center;">View Tutorial Videos</p>
								<p style="text-align: center;">Live support available as well<p>
                            </h5>
                        </div>
                        <div class="col-sm-4 s_grid">
                            <img src="<?php echo CARTARA_SYNC__PLUGIN_URL; ?>admin/images/word_icon3.jpg" height="100"/>
                            <h4><a href="./admin.php?page=api_setting">API Settings</a></h4>
                            <p style="text-align: center;">
                                Connect your Cartara account for instantaneous ecommerce automation.
                            </p>
                        </div>
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>